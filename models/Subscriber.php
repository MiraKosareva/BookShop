<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "subscribers".
 *
 * @property int $id
 * @property string $email
 * @property int $created_at
 */
class Subscriber extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscribers';
    }

     public function rules()
    {
        return [
            [['email'], 'required'],
            [['email'], 'email'],
            [['email'], 'unique', 'message' => 'Этот email уже подписан'],
            [['email'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'created_at' => 'Дата подписки',
        ];
    }

    /**
     * {@inheritdoc}
     */
    // public function beforeSave($insert)
    // {
    //     if (parent::beforeSave($insert)) {
    //         if ($insert) {
    //             $this->created_at = time();
    //         }
    //         return true;
    //     }
    //     return false;
    // }
    
    /**
     * Подписывает пользователя
     */
    public static function subscribe($email)
    {
        $subscriber = self::findOne(['email' => $email]);
        
        if ($subscriber) {
            return $subscriber; // Уже подписан
        }
        
        $subscriber = new self();
        $subscriber->email = $email;
        
        if (!$subscriber->save()) {
            throw new \Exception('Ошибка при сохранении: ' . implode(', ', $subscriber->getFirstErrors()));
        }
        
        return $subscriber;
    }
}