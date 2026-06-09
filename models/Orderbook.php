<?php
// models/Orderbook.php

namespace app\models;

use Yii;

/**
 * This is the model class for table "orderbook".
 */
class Orderbook extends \yii\db\ActiveRecord
{
    public $cart_items; // временное свойство для корзины
    public $total_amount;

    public static function tableName()
    {
        return 'orderbook';
    }

    public function rules()
    {
        return [
            [['id_status'], 'default', 'value' => 1],
             [['total_amount'], 'default', 'value' => 0],
            [['id_user', 'fio', 'contact', 'adres', 'id_delivery', 'id_pay'], 'required', 'message' => 'Заполните поля!'],
            [['id_user', 'id_delivery', 'id_pay', 'id_status'], 'integer'],
            [['total_amount'], 'number'], 
            [['fio', 'contact', 'adres'], 'string', 'max' => 255],
            [['cart_items'], 'safe'], // для хранения элементов корзины
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_user' => 'Пользователь',
            'fio' => 'ФИО',
            'contact' => 'Телефон',
            'adres' => 'Адрес доставки',
            'id_delivery' => 'Способ доставки',
            'id_pay' => 'Способ оплаты',
            'id_status' => 'Статус',
            'total_amount' => 'Общая сумма',
            'created_at' => 'Дата заказа',
        ];
    }

    public function getItems()
    {
        return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
    }
    // public function getBook()
    // {
    //     return $this->hasOne(Book::class, ['id' => 'book_id']);
    // }

    public function getStatus()
    {
        return $this->hasOne(Status::class, ['id' => 'id_status']);
    }

    public function getDelivery()
    {
        return $this->hasOne(Delivery::class, ['id' => 'id_delivery']);
    }

    public function getPay()
    {
        return $this->hasOne(Pay::class, ['id' => 'id_pay']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'id_user']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        // Сохраняем элементы заказа если есть cart_items
        if ($insert && !empty($this->cart_items)) {
            foreach ($this->cart_items as $bookId => $quantity) {
                $book = Book::findOne($bookId);
                if ($book) {
                    $orderItem = new OrderItem([
                        'order_id' => $this->id,
                        'book_id' => $bookId,
                        'quantity' => $quantity,
                        'price' => $book->price,
                    ]);
                    $orderItem->save();
                }
            }
        }
    }

    public function getOrderItems()
{
    return $this->hasMany(OrderItem::class, ['order_id' => 'id']);
}

    public function updateTotal()
{
    $this->total_amount = OrderItem::find()
        ->where(['order_id' => $this->id])
        ->sum('price * quantity') ?? 0;

    return $this->save(false);
}


}