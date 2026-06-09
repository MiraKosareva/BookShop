<?php

namespace app\controllers;

use app\models\Book;
use app\models\BookImage;
use app\models\Category;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * BookController implements the CRUD actions for Book model.
 */
class BookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Book models.
     *
     * @return string
     */
    
    public function actionIndex()
{
    $dataProvider = new ActiveDataProvider([
        'query' => Book::find(), // Без where category_id
        'pagination' => [
            'pageSize' => 12,
        ],
        'sort' => [
            'defaultOrder' => [
                'name' => SORT_ASC,
            ]
        ],
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
    ]);
    
}
        


    /**
     * Displays a single Book model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $book = Book::findOne($id);
        if (!$book) {
            throw new NotFoundHttpException('Книга не найдена.');
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'book' => $book,
        ]);
    }

    /**
     * Creates a new Book model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
{
    $model = new Book();

    if (Yii::$app->request->isPost) {
        if ($model->load(Yii::$app->request->post())) {
            $model->file = UploadedFile::getInstance($model, 'file');
            
            if ($model->save()) {
                // Основное фото
                if ($model->file) {
                    $fileName = Yii::$app->security->generateRandomString() . '.' . $model->file->extension;
                    $uploadPath = Yii::getAlias('@webroot/uploads/books/');
                    
                    if (!file_exists($uploadPath)) {
                        mkdir($uploadPath, 0777, true);
                    }
                    
                    $filePath = $uploadPath . $fileName;
                    
                    if ($model->file->saveAs($filePath)) {
                        $model->image = '/uploads/books/' . $fileName;
                        $model->save(false);
                    }
                }
                
                // Дополнительные фото
                $extraFiles = UploadedFile::getInstancesByName('extra_images');
                foreach ($extraFiles as $file) {
                    $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                    $uploadPath = Yii::getAlias('@webroot/uploads/books/');
                    $filePath = $uploadPath . $fileName;
                    
                    if ($file->saveAs($filePath)) {
                        $image = new BookImage();
                        $image->book_id = $model->id;
                        $image->image_path = '/uploads/books/' . $fileName;
                        $image->save();
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Книга успешно добавлена!');
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
    }

    return $this->render('create', ['model' => $model]);
}

    /**
     * Updates an existing Book model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                // Получаем загруженный файл
                $model->file = UploadedFile::getInstance($model, 'file');
                
                if ($model->save()) {
                    // Сохраняем фото если загружено
                    if ($model->file) {
                        $fileName = Yii::$app->security->generateRandomString() . '.' . $model->file->extension;
                        $uploadPath = Yii::getAlias('@webroot/uploads/books/');
                        
                        if (!file_exists($uploadPath)) {
                            mkdir($uploadPath, 0777, true);
                        }
                        
                        $filePath = $uploadPath . $fileName;
                        
                        if ($model->file->saveAs($filePath)) {
                            // Удаляем старый файл
                            if ($model->image && file_exists(Yii::getAlias('@webroot') . $model->image)) {
                                @unlink(Yii::getAlias('@webroot') . $model->image);
                            }
                            $model->image = '/uploads/books/' . $fileName;
                            $model->save(false);
                        }
                    }
                    
                    Yii::$app->session->setFlash('success', 'Книга успешно обновлена!');
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUploadImages($id)
    {
        $book = $this->findModel($id);
        
        if (Yii::$app->request->isPost) {
            $uploadPath = Yii::getAlias('@webroot/uploads/books/');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }
            
            // Получаем массив файлов
            $files = UploadedFile::getInstancesByName('images');
            
            foreach ($files as $file) {
                $fileName = Yii::$app->security->generateRandomString() . '.' . $file->extension;
                $filePath = $uploadPath . $fileName;
                
                if ($file->saveAs($filePath)) {
                    $image = new BookImage();
                    $image->book_id = $book->id;
                    $image->image_path = '/uploads/books/' . $fileName;
                    $image->sort_order = 0;
                    $image->save();
                }
            }
            
            Yii::$app->session->setFlash('success', 'Дополнительные фото загружены!');
        }
        
        return $this->redirect(['update', 'id' => $book->id]);
    }
    
    // Удаление дополнительного фото
    public function actionDeleteImage($id)
    {
        $image = BookImage::findOne($id);
        if ($image) {
            $bookId = $image->book_id;
            // Удаляем файл
            $filePath = Yii::getAlias('@webroot') . $image->image_path;
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            $image->delete();
            Yii::$app->session->setFlash('success', 'Фото удалено!');
            return $this->redirect(['update', 'id' => $bookId]);
        }
        throw new NotFoundHttpException('Фото не найдено.');
    }

    /**
     * Deletes an existing Book model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    // BookController.php
// public function destroy($id)
// {
//     $book = Book::find($id);
    
//     if (!$book) {
//         return redirect()->back()->with('error', 'Книга не найдена');
//     }
    
//     $book->delete();
    
//     return redirect()->route('books.index')
//         ->with('success', 'Книга успешно удалена');
// }

    /**
     * Finds the Book model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Book the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Book::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    

    public function actionSearch($q = '')
    {
        $query = Book::search($q);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => false, // Уже отсортировано в search методе
        ]);

        return $this->render('search', [
            'dataProvider' => $dataProvider,
            'searchQuery' => $q,
            'totalCount' => $query->count(),
        ]);
    }
    
    /**
     * Показать книги по категории
     */
    public function actionCategory($name)
    {
        $query = Book::getByCategory($name);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
        ]);

        return $this->render('category', [
            'dataProvider' => $dataProvider,
            'categoryName' => $name,
            'totalCount' => $query->count(),
        ]);
    }
    
    /**
     * Показать все категории
     */
    public function actionCategories()
    {
        $categories = Book::getAllCategories();
        
        // Подсчет книг в каждой категории
        $categoriesWithCount = [];
        foreach ($categories as $category) {
            $count = Book::find()
                ->where(['category' => $category])
                ->count();
            $categoriesWithCount[] = [
                'name' => $category,
                'count' => $count
            ];
        }

        return $this->render('categories', [
            'categories' => $categoriesWithCount,
        ]);
    }
}
