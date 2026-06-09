<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * This is the model class for table "review".
 *
 * @property int $id
 * @property int $user_id
 * @property int $book_id
 * @property int $rating
 * @property string $text
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $user
 * @property Book $book
 */
class Review extends ActiveRecord
{
    const STATUS_PENDING = 0;
    const STATUS_APPROVED = 1;
    const STATUS_REJECTED = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
public function rules()
{
    return [
        [['rating', 'text'], 'required', 'message' => 'Пожалуйста, поставьте оценку и напишите отзыв'],
        [['user_id', 'book_id', 'rating', 'status', 'created_at', 'updated_at'], 'integer'],
        [['text'], 'string', 'max' => 1000],
        ['rating', 'integer', 'min' => 1, 'max' => 5, 'message' => 'Оценка должна быть от 1 до 5'],
        [['book_id'], 'exist', 'skipOnError' => true, 'targetClass' => Book::class, 'targetAttribute' => ['book_id' => 'id']],
        [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
    ];
}

/**
 * {@inheritdoc}
 */
public function scenarios()
{
    $scenarios = parent::scenarios();
    $scenarios['create'] = ['rating', 'text', 'book_id'];
    $scenarios['update'] = ['rating', 'text'];
    return $scenarios;
}

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'book_id' => 'Книга',
            'rating' => 'Рейтинг',
            'text' => 'Текст отзыва',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            [
                'class' => BlameableBehavior::class,
                'createdByAttribute' => 'user_id',
                'updatedByAttribute' => null,
            ],
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Book]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBook()
    {
        return $this->hasOne(Book::class, ['id' => 'book_id']);
    }

    /**
     * Проверяет, может ли пользователь редактировать отзыв
     */
    public function canEdit($userId = null)
    {
        if ($userId === null) {
            $userId = Yii::$app->user->id;
        }
        
        return $this->user_id == $userId || Yii::$app->user->can('admin');
    }

    /**
     * Получает статус в виде текста
     */
    public function getStatusText()
    {
        $statuses = [
            self::STATUS_PENDING => 'На модерации',
            self::STATUS_APPROVED => 'Одобрен',
            self::STATUS_REJECTED => 'Отклонен',
        ];
        
        return $statuses[$this->status] ?? 'Неизвестно';
    }

    /**
     * Получает HTML для рейтинга
     */
    public function getRatingStars()
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '★';
            } else {
                $stars .= '☆';
            }
        }
        return $stars;
    }

    /**
     * Получает инициалы пользователя
     */
    public function getUserInitials()
    {
        if (!$this->user) {
            return 'Г';
        }
        
        $name = $this->user->username;
        $words = explode(' ', $name);
        $initials = '';
        
        foreach ($words as $word) {
            if ($word) {
                $initials .= mb_substr($word, 0, 1, 'UTF-8');
            }
        }
        
        return mb_strtoupper($initials, 'UTF-8') ?: 'Г';
    }

    /**
     * Получает цвет для аватара
     */
    public function getAvatarColor()
    {
        $colors = [
            'bg-soft-blue',
            'bg-dusty-rose', 
            'bg-sage',
            'bg-warm-gray',
            'bg-primary',
            'bg-success',
            'bg-info',
        ];
        
        $index = $this->user_id % count($colors);
        return $colors[$index];
    }
}