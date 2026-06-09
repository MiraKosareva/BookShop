<?php

namespace app\controllers;

use app\models\Book;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Review;
use app\models\Subscriber;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

public function actionSubscribe()
{
    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

    // Проверка AJAX запроса
    if (!Yii::$app->request->isAjax) {
        throw new \yii\web\BadRequestHttpException('Только AJAX запросы');
    }

    // Получаем email из POST
    $email = Yii::$app->request->post('email');

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'message' => 'Введите корректный email'];
    }

    try {
        // Проверяем, есть ли уже подписка
        $existing = \app\models\Subscriber::findOne(['email' => $email]);
        if ($existing) {
            return ['success' => false, 'message' => 'Этот email уже подписан на рассылку'];
        }

        // Создаём нового подписчика
        $subscriber = new \app\models\Subscriber();
        $subscriber->email = $email;

        if (!$subscriber->save()) {
            $errors = implode(', ', array_values($subscriber->getFirstErrors()));
            return ['success' => false, 'message' => 'Ошибка сохранения: ' . $errors];
        }

        return ['success' => true, 'message' => 'Спасибо за подписку! Вы будете получать наши новости.'];

    } catch (\Throwable $e) {
        // Для отладки можно оставить trace
        return [
            'success' => false,
            'message' => 'Исключение: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ];
    }
}




    /**
     * Displays homepage.
     *
     * @return string
     */


    public function actionIndex()
    {
        
        // Получаем рекомендуемые книги
        $featuredBooks = Book::find()
            ->where(['is_featured' => 1])
            ->andWhere(['>', 'stock', 0])
            ->limit(6)
            ->all();
            
        // Получаем новинки
        $newBooks = Book::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(8)
            ->all();
            
        // Получаем книги со скидкой
        $discountBooks = Book::find()
            ->where(['>', 'old_price', 0])
            ->andWhere(['>', 'stock', 0])
            ->limit(4)
            ->all();
        
        // Получаем популярные категории (топ-5)
        $popularCategories = Book::find()
            ->select(['category', 'COUNT(*) as count'])
            ->groupBy('category')
            ->orderBy(['count' => SORT_DESC])
            ->limit(5)
            ->asArray()
            ->all();

            $reviews = Review::find()
        ->where(['status' => Review::STATUS_APPROVED])
        ->orderBy(['created_at' => SORT_DESC])
        ->limit(3)
        ->all();

        return $this->render('index', [
            'featuredBooks' => $featuredBooks,
            'newBooks' => $newBooks,
            'discountBooks' => $discountBooks,
            'popularCategories' => $popularCategories,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    // public function actionContact()
    // {
    //     $model = new ContactForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
    //         Yii::$app->session->setFlash('contactFormSubmitted');

    //         return $this->refresh();
    //     }
    //     return $this->render('contact', [
    //         'model' => $model,
    //     ]);
    // }

    public function actionContact()
{
    $model = new ContactForm();
    
    if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        // Сохраняем в базу
        $message = new \app\models\ContactMessage();
        $message->name = $model->name;
        $message->email = $model->email;
        $message->subject = $model->subject;
        $message->body = $model->body;
        
        if ($message->save()) {
            Yii::$app->session->setFlash('success', '✅ Спасибо! Мы получили ваше сообщение и ответим на указанную почту в ближайшее время.');
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка при отправке. Попробуйте позже.');
        }
        
        return $this->refresh();
    }
    
    return $this->render('contact', [
        'model' => $model,
    ]);
}

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

//     public function actionSubscribe()
// {
//     Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

//     $email = Yii::$app->request->post('email');

//     if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
//         return ['success' => false, 'message' => 'Некорректный email'];
//     }

//     // Модель подписки
//     $subscriber = new \app\models\Subscriber();
//     $subscriber->email = $email;

//     if ($subscriber->save()) {
//         return ['success' => true];
//     } else {
//         return ['success' => false, 'message' => 'Email уже подписан'];
//     }
// }

}
