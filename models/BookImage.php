<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class BookImage extends ActiveRecord
{
    public static function tableName()
    {
        return 'book_images';
    }

    public function rules()
    {
        return [
            [['book_id', 'image_path'], 'required'],
            [['book_id', 'sort_order'], 'integer'],
            [['image_path'], 'string', 'max' => 500],
        ];
    }
    
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }
}