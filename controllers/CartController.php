<?php
// controllers/CartController.php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use app\models\Book;
use app\models\Orderbook;
use app\models\Delivery;
use app\models\Pay;

class CartController extends Controller
{
    // Корзина (просмотр)
    public function actionIndex()
{
    $cart = Yii::$app->session->get('cart', []);
    $books = [];
    $total = 0;
    $totalItems = 0;

    foreach ($cart as $bookId => $item) {
        $book = Book::findOne($bookId);
        if ($book) {
            $quantity = $item['quantity'];
            // Вместо установки свойства создаем массив с данными
            $books[] = [
                'book' => $book,
                'quantity' => $quantity,
                'item_total' => $book->price * $quantity
            ];
            $total += $book->price * $quantity;
            $totalItems += $quantity;
        }
    }

    return $this->render('index', [
        'books' => $books,
        'total' => $total,
        'totalItems' => $totalItems,
    ]);
}

public function actionAdd()
{
    $request = Yii::$app->request;
    $id = $request->post('id', $request->get('id'));

    if (!$id) {
        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => 'ID товара не передан'];
        }

        Yii::$app->session->setFlash('error', 'ID товара не передан');
        return $this->redirect(['/catalog/index']);
    }

    $book = Book::findOne($id);

    if (!$book) {
        if ($request->isAjax) {
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => false, 'message' => 'Товар не найден'];
        }

        Yii::$app->session->setFlash('error', 'Товар не найден');
        return $this->redirect(['/catalog/index']);
    }

    $cart = Yii::$app->session->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity']++;
    } else {
        $cart[$id] = [
            'id' => $book->id,
            'name' => $book->name,
            'price' => $book->price,
            'quantity' => 1,
        ];
    }

    Yii::$app->session->set('cart', $cart);

    $message = '📚 "' . $book->name . '" добавлен в корзину!';

    if ($request->isAjax) {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return [
            'success' => true,
            'message' => $message,
            'cartCount' => array_sum(array_column($cart, 'quantity'))
        ];
    }

    Yii::$app->session->setFlash('success', $message);
    return $this->redirect($request->referrer ?: ['/catalog/index']);
}

    // Удалить из корзины
    public function actionRemove($id)
    {
        $cart = Yii::$app->session->get('cart', []);
        if (isset($cart[$id])) {
            unset($cart[$id]);
            Yii::$app->session->set('cart', $cart);
            Yii::$app->session->setFlash('success', 'Товар удален из корзины');
        }

        return $this->redirect(['index']);
    }

    // Обновить количество
    public function actionUpdate()
    {
    Yii::$app->response->format = Response::FORMAT_JSON;
    
    $bookId = Yii::$app->request->post('id');
    $quantity = Yii::$app->request->post('quantity', 1);
    
    $book = Book::findOne($bookId);
    if (!$book) {
        return ['success' => false, 'message' => 'Товар не найден'];
    }

    $cart = Yii::$app->session->get('cart', []);
    
    if ($quantity <= 0) {
        unset($cart[$bookId]);
        $message = 'Товар удален из корзины';
    } else {
        if ($quantity > $book->stock) {
            return ['success' => false, 'message' => 'Недостаточно товара на складе. Доступно: ' . $book->stock];
        }
        $cart[$bookId]['quantity'] = $quantity;
        $message = 'Количество обновлено';
    }
    
    Yii::$app->session->set('cart', $cart);
    
    $cartCount = 0;
    $total = 0;
    foreach ($cart as $id => $item) {
        $cartBook = Book::findOne($id);
        if ($cartBook) {
            $cartCount += $item['quantity'];
            $total += $cartBook->price * $item['quantity'];
        }
    }
    
    $itemTotal = isset($cart[$bookId]) ? $book->price * $cart[$bookId]['quantity'] : 0;
    
    return [
        'success' => true,
        'message' => $message,
        'cartCount' => $cartCount,
        'itemTotal' => $itemTotal,
        'total' => $total
    ];
    }
    // Очистить корзину
    public function actionClear()
    {
        Yii::$app->session->remove('cart');
        Yii::$app->session->setFlash('success', 'Корзина очищена');
        return $this->redirect(['index']);
    }

    // Оформление заказа
    public function actionCheckout()
    {
        if (Yii::$app->user->isGuest) {
            Yii::$app->session->setFlash('error', 'Для оформления заказа необходимо авторизоваться');
            return $this->redirect(['/site/login']);
        }

        $cart = Yii::$app->session->get('cart', []);
        if (empty($cart)) {
            Yii::$app->session->setFlash('error', 'Корзина пуста');
            return $this->redirect(['index']);
        }

        $model = new Orderbook();
        $deliveryMethods = Delivery::find()->all();
        $payMethods = Pay::find()->all();
        $total = 0;
        $cartBooks = [];
        foreach ($cart as $bookId => $item) {
            $book = Book::findOne($bookId);
            if ($book) {
                $quantity = $item['quantity'];
                if ($quantity > $book->stock) {
                    Yii::$app->session->setFlash('error', "Товар '{$book->name}' недоступен в количестве {$quantity}. Доступно: {$book->stock}");
                    return $this->redirect(['index']);
                }
                $cartBooks[$bookId] = [
                    'book' => $book,
                    'quantity' => $quantity
                ];
                $total += $book->price * $quantity;
            }
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->id_user = Yii::$app->user->identity->id;
            $model->total_amount = $total;
            $model->cart_items = $cart;

            if ($model->save()) {
        foreach ($cart as $bookId => $item) {
        $book = Book::findOne($bookId);

        if ($book) {
            $orderItem = new \app\models\OrderItem();

            $orderItem->order_id = $model->id;
            $orderItem->book_id = $book->id;
            $orderItem->quantity = $item['quantity'];
            $orderItem->price = $book->price;

            $orderItem->save(false);
        }

        if ($book) {
            $book->stock -= $item['quantity'];
            $book->save(false);
        }
    }
    
    // СОХРАНЯЕМ ID ЗАКАЗА И СУММУ В СЕССИИ ДЛЯ ОПЛАТЫ
    Yii::$app->session->set('last_order_id', $model->id);
    Yii::$app->session->set('last_order_amount', $total);
    Yii::$app->session->set('last_order_pay', $model->id_pay);
    Yii::$app->session->set('last_order_delivery', $model->id_delivery);
    
    // ПЕРЕХОДИМ НА ИМИТАЦИЮ ОПЛАТЫ
    return $this->redirect(['/payment-test/index', 
        'amount' => $total,
        'order_id' => $model->id
    ]);
    
            }
        }

        return $this->render('checkout', [
            'model' => $model,
            'deliveryMethods' => $deliveryMethods,
            'payMethods' => $payMethods,
            'cartBooks' => $cartBooks,
            'total' => $total,
        ]);
}

    // Мини-корзина (для AJAX)
    public function actionMiniCart()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        
        $cart = Yii::$app->session->get('cart', []);
        $books = [];
        $total = 0;
        $count = 0;

        foreach ($cart as $bookId => $item) {
            $book = Book::findOne($bookId);
            if ($book) {
                $quantity = $item['quantity'];
                $books[] = [
                    'id' => $book->id,
                    'name' => $book->name,
                    'price' => $book->price,
                    'quantity' => $quantity,
                    'image' => $book->getImageUrl(),
                    'total' => $book->price * $quantity
                ];
                $total += $book->price * $quantity;
                $count += $quantity;
            }
        }

        return [
            'success' => true,
            'count' => $count,
            'total' => $total,
            'items' => $books
        ];
    }

    private function getCartCount()
    {
        $cart = Yii::$app->session->get('cart', []);
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantity'];
        }
        return $count;
    }

    private function getCartTotal()
    {
        $cart = Yii::$app->session->get('cart', []);
        $total = 0;
        foreach ($cart as $bookId => $item) {
            $book = Book::findOne($bookId);
            if ($book) {
                $total += $book->price * $item['quantity'];
            }
        }
        return $total;
    }
}