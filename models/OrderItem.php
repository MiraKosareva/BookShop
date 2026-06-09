<?php
// models/OrderItem.php

namespace app\models;

use Yii;

/**
 * This is the model class for table "order_items".
 *
 * @property int $id
 * @property int $order_id
 * @property int $book_id
 * @property int $quantity
 * @property float $price
 * 
 * @property Orderbook $order
 * @property Book $book
 */
class OrderItem extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'order_items';
    }

    public function rules()
    {
        return [
            [['order_id', 'book_id', 'price'], 'required'],
            [['order_id', 'book_id', 'quantity'], 'integer'],
            [['price'], 'number'],
            [['quantity'], 'default', 'value' => 1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Заказ',
            'book_id' => 'Книга',
            'quantity' => 'Количество',
            'price' => 'Цена',
        ];
    }

    public function getOrder()
    {
        return $this->hasOne(Orderbook::class, ['id' => 'order_id']);
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    public function getTotalPrice()
    {
        return $this->price * $this->quantity;
    }
}