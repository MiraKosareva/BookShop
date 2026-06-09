<?php
// models/User.php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $passwordValidate;
    public $check;
    public $avatarFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_role'], 'default', 'value' => 1],
            [['fio', 'email', 'phone', 'username', 'password'], 'required', 'message' => 'Заполните поля!'],
            [['id_role'], 'integer'],
            [['fio', 'email', 'username', 'password'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 60],
            [['email'], 'unique' , 'message' => 'Такой аккаунт уже есть!'],
            [['phone'], 'unique' , 'message' => 'Такой аккаунт уже есть!'],
            [['username'], 'unique' , 'message' => 'Такой аккаунт уже есть!'],
            [['id_role'], 'exist', 'skipOnError' => true, 'targetClass' => Role::class, 'targetAttribute' => ['id_role' => 'id']],

            ['username', 'match', 'pattern' => '/^[A-Za-z0-9]{5,}$/', 'message' => 'Только латиница и не менее 5 символов!'],
            ['fio', 'match', 'pattern' => '/^[А-Яа-я\s\-]{7,}$/u', 'message' => 'Только кириллица и пробел!'],
            ['email', 'email', 'message' => 'Некорректный формат email!'],
            
            ['phone', 'match', 'pattern' => '/^\+?[0-9\-()\s]{10,18}$/', 'message' => 'Некорректный формат номера телефона'],

            ['password', 'match', 'pattern' => '/^[A-Za-z0-9_]{6,}$/', 'message' => 'Пароль должен содержать не менее 6 символов и только латиницу!'],

            ['passwordValidate', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],

            ['check', 'compare', 'compareValue' => true, 'message' => 'Необходимо ваше согласие'],

            [['avatar'], 'string', 'max' => 500],
            [['avatarFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg', 'maxSize' => 2 * 1024 * 1024],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fio' => 'ФИО',
            'email' => 'Email',
            'phone' => 'Телефон',
            'username' => 'Логин',
            'password' => 'Пароль',
            'passwordValidate' => 'Подтверждение пароля',
            'check' => 'Согласие  на обработку персональных данных',
            'id_role' => 'Роль',
            'avatarFile' => 'Фото профиля',
            'avatar' => 'Аватар',
            'created_at' => 'Дата регистрации',
        ];
    }

     public function getAvatarUrl()
    {
        if ($this->avatar && file_exists(Yii::getAlias('@webroot') . '/' . ltrim($this->avatar, '/'))) {
            return $this->avatar;
        }
        return null;
    }


    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        null;
    }

    public function validateAuthKey($authKey)
    {
        null;
    }


    public static function findByUsername($username): User|null{
        return User::findOne(condition:['username' => $username]);
    }
    
    public function validatePassword($password){
        return $this -> password == md5($password);
    }


    public function beforeSave($insert)
    {
        if($this -> isNewRecord)
        {
            $this -> password = md5($this->password);
        }
        return parent::beforeSave($insert);
    }

    public function isAdmin()
    {
        return $this -> id_role === 2;
    }

    // Получить заказы пользователя
    public function getOrders()
    {
        return $this->hasMany(Orderbook::class, ['id_user' => 'id'])
            ->orderBy(['created_at' => SORT_DESC]);
    }

    // Получить количество заказов
    public function getOrdersCount()
    {
        return $this->getOrders()->count();
    }

    // Получить общую сумму всех заказов
    public function getTotalSpent()
{
    return OrderItem::find()
        ->joinWith('order')
        ->where(['orderbook.id_user' => $this->id])
        ->sum('order_items.price * order_items.quantity') ?? 0;
}


    // Получить дату последнего заказа
    public function getLastOrderDate()
    {
        $lastOrder = $this->getOrders()->one();
        return $lastOrder ? $lastOrder->created_at : null;
    }

    // Получить статус покупателя
    // public function getCustomerStatus()
    // {
    //     $totalSpent = $this->getTotalSpent();
        
    //     if ($totalSpent > 10000) {
    //         return ['name' => 'VIP клиент', 'color' => 'gold', 'icon' => '👑'];
    //     } elseif ($totalSpent > 5000) {
    //         return ['name' => 'Постоянный клиент', 'color' => 'silver', 'icon' => '⭐'];
    //     } elseif ($totalSpent > 1000) {
    //         return ['name' => 'Активный покупатель', 'color' => 'bronze', 'icon' => '👍'];
    //     } else {
    //         return ['name' => 'Новый клиент', 'color' => 'blue', 'icon' => '👋'];
    //     }
    // }

    public function getReviews()
{
    return $this->hasMany(Review::class, ['user_id' => 'id']);
}

/**
 * Получает количество одобренных отзывов пользователя
 */
public function getApprovedReviewsCount()
{
    return $this->getReviews()
        ->where(['status' => Review::STATUS_APPROVED])
        ->count();
}
}