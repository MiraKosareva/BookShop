<?php
// migrations/m240102_120000_fix_structure.php

use yii\db\Migration;

/**
 * Class m240102_120000_fix_structure
 */
class m240102_120000_fix_structure extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Создаем таблицу для элементов заказа
        $this->createTable('order_items', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'book_id' => $this->integer()->notNull(),
            'quantity' => $this->integer()->notNull()->defaultValue(1),
            'count' => $this->decimal(10, 2)->notNull(),
        ]);

        // Добавляем внешние ключи
        $this->addForeignKey(
            'fk_order_items_order',
            'order_items',
            'order_id',
            'orderbook',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk_order_items_book',
            'order_items',
            'book_id',
            'book',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Обновляем таблицу orderbook - убираем id_catalog
        $this->dropColumn('orderbook', 'id_catalog');
        
        // Добавляем общие поля в orderbook
        $this->addColumn('orderbook', 'total_amount', $this->decimal(10, 2)->defaultValue(0));
        $this->addColumn('orderbook', 'created_at', $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Откат изменений
        $this->dropForeignKey('fk_order_items_book', 'order_items');
        $this->dropForeignKey('fk_order_items_order', 'order_items');
        $this->dropTable('order_items');
        
        $this->dropColumn('orderbook', 'total_amount');
        $this->dropColumn('orderbook', 'created_at');
        
        // Восстанавливаем старую структуру (если нужно)
        $this->addColumn('orderbook', 'id_catalog', $this->integer());
    }
}