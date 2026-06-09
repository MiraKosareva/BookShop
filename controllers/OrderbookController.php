<?php

namespace app\controllers;

use app\models\Delivery;
use app\models\Orderbook;
use app\models\Pay;
use app\models\Status;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrderbookController implements the CRUD actions for Orderbook model.
 */
class OrderbookController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
        'access' => [
                'class' => AccessControl::class,
                'only' => ['admin', 'update'],
                'rules' => [
                    [
                        'actions' => ['admin', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $actions)
                        {
                            return Yii::$app->user->identity->isAdmin();
                        }
                    ],
                ],
            ],
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
     * Lists all Orderbook models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $pay = Pay::find()->all();
        $pay = Delivery::find()->All();
        $dataProvider = new ActiveDataProvider([
            'query' => Orderbook::find()->where(['id_user' => Yii::$app->user->identity->id]),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider, 
            'pay' => $pay,
        ]);
    }

     public function actionAdmin()
    {
        $pay = Pay::find()->all();
        $delivery = Delivery::find()->All();
        $dataProvider = new ActiveDataProvider([
            'query' => Orderbook::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('admin', [
            'dataProvider' => $dataProvider,
            'pay' => $pay,
            'delivery' => $delivery,
        ]);
    }

    /**
     * Displays a single Orderbook model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orderbook model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Orderbook();
    $pay = Pay::find()->all();
    $delivery = Delivery::find()->all();
    
    // Получаем корзину
    $cart = new \app\models\Cart();
    $cartItems = $cart->getCart();
    $totalAmount = $cart->getTotalPrice();
    
    // Если корзина пуста
    if (empty($cartItems)) {
        Yii::$app->session->setFlash('error', 'Ваша корзина пуста.');
        return $this->redirect(['site/catalog']); // перенаправляем куда нужно
    }
    
    if ($model->load(Yii::$app->request->post())) {
        $model->id_user = Yii::$app->user->identity->id;
        $model->total_amount = $totalAmount;
        $model->cart_items = $cartItems;
        
        if ($model->save()) {
            $cart->clear();
            Yii::$app->session->setFlash('success', 'Заказ успешно оформлен!');
            return $this->redirect(['index']);
        }
    } else {
        // Заполняем поля при первом открытии формы
        $user = Yii::$app->user->identity;
        if ($user) {
            $model->fio = $user->fio;
            $model->contact = $user->phone;
        }
        $model->total_amount = $totalAmount;
        $model->cart_items = $cartItems;
    }

    return $this->render('create', [
        'model' => $model,
        'pay' => $pay,
        'delivery' => $delivery,
        'cartItems' => $cartItems, // передаем в представление
        'totalAmount' => $totalAmount, // передаем в представление
    ]);
    }

    /**
     * Updates an existing Orderbook model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $status = Status::find()->all();

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['admin']);
        }

        return $this->render('update', [
            'model' => $model,
            'status' => $status,
        ]);
    }

    /**
     * Deletes an existing Orderbook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['orderbook/admin']);
    }

    /**
     * Finds the Orderbook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Orderbook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orderbook::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
