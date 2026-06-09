<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%review}}`.
 */
class m240101_000001_create_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%review}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'rating' => $this->integer()->notNull()->defaultValue(5),
            'text' => $this->text()->notNull(),
            'status' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        // Добавляем индексы
        $this->createIndex('idx-review-user_id', '{{%review}}', 'user_id');
        $this->createIndex('idx-review-book_id', '{{%review}}', 'book_id');
        $this->createIndex('idx-review-status', '{{%review}}', 'status');
        $this->createIndex('idx-review-created_at', '{{%review}}', 'created_at');

        // Добавляем внешние ключи
        $this->addForeignKey(
            'fk-review-user_id',
            '{{%review}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-review-book_id',
            '{{%review}}',
            'book_id',
            '{{%book}}',
            'id',
            'CASCADE'
        );

        // Добавляем уникальный индекс для предотвращения дублирования отзывов
        $this->createIndex('idx-review-user_book', '{{%review}}', ['user_id', 'book_id'], true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-review-book_id', '{{%review}}');
        $this->dropForeignKey('fk-review-user_id', '{{%review}}');
        $this->dropTable('{{%review}}');
    }
}