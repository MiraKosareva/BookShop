<?php
// models/PaymentTestForm.php

namespace app\models;

use Yii;
use yii\base\Model;

class PaymentTestForm extends Model
{
    public $cardNumber;
    public $cardExpiry;
    public $cardCvc;
    public $cardHolder;
    public $email;
    
    public function rules()
    {
        return [
            [['cardNumber', 'cardExpiry', 'cardCvc', 'cardHolder', 'email'], 'required'],
            ['email', 'email'],
            ['cardNumber', 'string', 'min' => 16, 'max' => 19],
            ['cardCvc', 'string', 'min' => 3, 'max' => 4],
            ['cardExpiry', 'match', 'pattern' => '/^\d{2}\/\d{2}$/'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'cardNumber' => 'Номер карты',
            'cardExpiry' => 'Срок действия',
            'cardCvc' => 'CVC/CVV код',
            'cardHolder' => 'Имя владельца',
            'email' => 'Email',
        ];
    }
}