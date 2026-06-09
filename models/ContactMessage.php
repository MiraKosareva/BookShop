<?php
// models/ContactMessage.php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class ContactMessage extends ActiveRecord
{
    const STATUS_NEW = 0;
    const STATUS_READ = 1;
    const STATUS_REPLIED = 2;

    public static function tableName()
    {
        return 'contact_messages';
    }

    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            [['body', 'admin_reply'], 'string'],
            [['status'], 'integer'],
            [['name', 'email', 'subject'], 'string', 'max' => 255],
            ['email', 'email'],
            [['status'], 'default', 'value' => self::STATUS_NEW],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'email' => 'Email',
            'subject' => 'Тема',
            'body' => 'Сообщение',
            'status' => 'Статус',
            'admin_reply' => 'Ответ админа',
            'created_at' => 'Дата',
        ];
    }

    public function getStatusText()
    {
        return [
            self::STATUS_NEW => 'Новое',
            self::STATUS_READ => 'Прочитано',
            self::STATUS_REPLIED => 'Отвечено',
        ][$this->status] ?? 'Неизвестно';
    }
}