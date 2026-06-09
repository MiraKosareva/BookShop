<?php
// models/Cart.php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\Book;

class Cart extends Model
{
    private $session;
    private $cartKey = 'cart';
    
    public function init()
    {
        parent::init();
        $this->session = Yii::$app->session;
        if (!$this->session->isActive) {
            $this->session->open();
        }
    }
    
    /**
     * Добавить товар в корзину
     */
    public function addItem($bookId, $quantity = 1)
    {
        // Проверяем существование книги
        $book = Book::findOne($bookId);
        if (!$book) {
            throw new \Exception('Книга не найдена');
        }
        
        // Получаем текущую корзину
        $cart = $this->getCart();
        
        // Добавляем или обновляем количество
        if (isset($cart[$bookId])) {
            $cart[$bookId] += $quantity;
        } else {
            $cart[$bookId] = $quantity;
        }
        
        // Сохраняем в сессию
        $this->session->set($this->cartKey, $cart);
        
        return true;
    }
    
    /**
     * Получить содержимое корзины
     */
    public function getCart()
    {
        return $this->session->get($this->cartKey, []);
    }
    
    /**
     * Получить общее количество товаров в корзине
     */
    public function getTotalCount()
    {
        $cart = $this->getCart();
        return array_sum($cart);
    }
    
    /**
     * Получить общую стоимость
     */
    public function getTotalPrice()
    {
        $total = 0;
        $cart = $this->getCart();
        
        foreach ($cart as $bookId => $quantity) {
            $book = Book::findOne($bookId);
            if ($book) {
                $total += $book->price * $quantity;
            }
        }
        
        return $total;
    }
    
    /**
     * Очистить корзину
     */
    public function clear()
    {
        $this->session->remove($this->cartKey);
    }
    
    /**
     * Удалить товар из корзины
     */
    public function removeItem($bookId)
    {
        $cart = $this->getCart();
        unset($cart[$bookId]);
        $this->session->set($this->cartKey, $cart);
    }
    
    /**
     * Обновить количество товара
     */
    public function updateQuantity($bookId, $quantity)
    {
        if ($quantity <= 0) {
            $this->removeItem($bookId);
            return;
        }
        
        $cart = $this->getCart();
        $cart[$bookId] = $quantity;
        $this->session->set($this->cartKey, $cart);
    }
}
?>