<?php
// models/Book.php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Book extends ActiveRecord
{
    public $file;
    public static function tableName()
    {
        return 'book';
    }

    public function rules()
    {
        return [
            [['name', 'author', 'description', 'price'], 'required'],
            [['image'], 'string', 'max' => 500],
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 2 * 1024 * 1024],
            [['price', 'old_price'], 'number'],
            [['stock', 'publication_year'], 'integer'],
            [['stock'], 'default', 'value' => 0],
            [['name', 'author', 'category', 'publisher'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['is_featured'], 'boolean'],
            [['publication_year'], 'integer', 'min' => 1900, 'max' => date('Y')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'image' => 'Изображение',
            'file' => 'Изображение книги',
            'author' => 'Автор',
            'description' => 'Описание',
            'price' => 'Цена',
            'old_price' => 'Старая цена',
            'stock' => 'Количество на складе',
            'category' => 'Категория',
            'publisher' => 'Издательство',
            'publication_year' => 'Год издания',
            'is_featured' => 'Рекомендуемый товар',
            'created_at' => 'Дата добавления',
        ];
    }

    public function getImageUrl()
    {
        if ($this->image && file_exists(Yii::getAlias('@webroot') . $this->image)) {
            return $this->image;
        }
        return '/images/books/default.jpg';
    }

    public function getImages()
{
    return $this->hasMany(BookImage::class, ['book_id' => 'id'])
        ->orderBy(['sort_order' => SORT_ASC]);
}

    public function getDiscountPercent()
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        return 0;
    }

    public function isInStock()
    {
        return $this->stock > 0;
    }

    public function getStockStatus()
    {
        if ($this->stock > 10) {
            return ['text' => 'В наличии', 'class' => 'text-success'];
        } elseif ($this->stock > 0) {
            return ['text' => 'Мало на складе', 'class' => 'text-warning'];
        } else {
            return ['text' => 'Нет в наличии', 'class' => 'text-danger'];
        }
    }

    /**
 * Gets query for [[Reviews]].
 *
 * @return \yii\db\ActiveQuery
 */
public function getReviews()
{
    return $this->hasMany(Review::class, ['book_id' => 'id']);
}

/**
 * Получает одобренные отзывы
 */
public function getApprovedReviews()
{
    return $this->getReviews()
        ->where(['status' => Review::STATUS_APPROVED])
        ->orderBy(['created_at' => SORT_DESC])
        ->all();
}

/**
 * Получает средний рейтинг книги
 */
public function getAverageRating()
{
    $reviews = $this->getReviews()
        ->where(['status' => Review::STATUS_APPROVED])
        ->all();
    
    if (empty($reviews)) {
        return 0;
    }
    
    $total = 0;
    foreach ($reviews as $review) {
        $total += $review->rating;
    }
    
    return round($total / count($reviews), 1);
}

/**
 * Получает количество отзывов
 */
public function getReviewsCount()
{
    return $this->getReviews()
        ->where(['status' => Review::STATUS_APPROVED])
        ->count();
}

/**
 * Проверяет, оставлял ли текущий пользователь отзыв
 */
public function hasUserReview($userId = null)
{
    if ($userId === null && Yii::$app->user->isGuest) {
        return false;
    }
    
    if ($userId === null) {
        $userId = Yii::$app->user->id;
    }
    
    return Review::find()
        ->where([
            'book_id' => $this->id,
            'user_id' => $userId,
        ])
        ->exists();
}

public static function search($keyword)
    {
        return self::find()
            ->where(['or',
                ['like', 'name', $keyword],
                ['like', 'author', $keyword],
                ['like', 'description', $keyword],
                ['like', 'category', $keyword]
            ])
            ->orderBy(['name' => SORT_ASC]);
    }
    
    /**
     * Получить все уникальные категории
     */
    public static function getAllCategories()
    {
        return self::find()
            ->select(['category'])
            ->distinct()
            ->where(['not', ['category' => null]])
            ->where(['!=', 'category', ''])
            ->orderBy(['category' => SORT_ASC])
            ->column();
    }
    
    /**
     * Получить книги по категории
     */
    public static function getByCategory($category)
    {
        return self::find()
            ->where(['category' => $category])
            ->orderBy(['name' => SORT_ASC]);
    }
}