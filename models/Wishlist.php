<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Wishlist extends ActiveRecord
{
    public static function tableName()
    {
        return 'wishlist';
    }

    public function rules()
    {
        return [
            [['user_id', 'book_id'], 'required'],
            [['user_id', 'book_id'], 'integer'],
        ];
    }

    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    // Добавить/удалить книгу в избранное
    public static function toggle($userId, $bookId)
    {
        $model = self::findOne(['user_id' => $userId, 'book_id' => $bookId]);
        
        if ($model) {
            $model->delete();
            return false; // Удалено
        } else {
            $model = new self(['user_id' => $userId, 'book_id' => $bookId]);
            $model->save();
            return true; // Добавлено
        }
    }

    // Проверить, в избранном ли
    public static function isInWishlist($userId, $bookId)
    {
        return self::find()->where(['user_id' => $userId, 'book_id' => $bookId])->exists();
    }
}