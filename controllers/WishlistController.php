<?php

namespace app\controllers;

use Yii;
use app\models\Wishlist;
use app\models\Book;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\Response;

class WishlistController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Просмотр избранного пользователя
     */
    public function actionIndex()
    {
        $query = Wishlist::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->with('book');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 12,
            ],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Добавить/удалить из избранного
     */
    public function actionToggle()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        if (Yii::$app->user->isGuest) {
            return [
                'success' => false, 
                'message' => 'Требуется авторизация'
            ];
        }

        $bookId = Yii::$app->request->post('bookId');
        
        if (!$bookId) {
            return [
                'success' => false, 
                'message' => 'ID книги не передан'
            ];
        }

        $book = Book::findOne($bookId);
        if (!$book) {
            return [
                'success' => false, 
                'message' => 'Книга не найдена'
            ];
        }

        $userId = Yii::$app->user->id;
        $result = Wishlist::toggle($userId, $bookId);
        
        $wishlistCount = Wishlist::find()
            ->where(['user_id' => $userId])
            ->count();
        
        if ($result) {
            return [
                'success' => true, 
                'status' => 'added', 
                'wishlistCount' => $wishlistCount, 
                'message' => '📚 "' . $book->name . '" добавлено в избранное'
            ];
        } else {
            return [
                'success' => true, 
                'status' => 'removed', 
                'wishlistCount' => $wishlistCount, 
                'message' => 'Удалено из избранного'
            ];
        }
    }
}