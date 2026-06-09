<?php
// controllers/TestController.php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Book;

class TestController extends Controller
{
    public function actionAddTestData()
    {
        $books = [
            [
                'name' => 'Мастер и Маргарита',
                'author' => 'Михаил Булгаков',
                'description' => 'Великий роман о любви, искусстве и вечной борьбе добра со злом.',
                'price' => 450.00,
                'stock' => 15,
                'category' => 'Классика',
                'publisher' => 'Эксмо',
                'publication_year' => 1966,
                'old_price' => 600.00,
                'is_featured' => 1,
                'image' => '/images/books/master.jpg'
            ],
            [
                'name' => '1984',
                'author' => 'Джордж Оруэлл',
                'description' => 'Антиутопия о тоталитарном обществе и борьбе за свободу мысли.',
                'price' => 380.00,
                'stock' => 8,
                'category' => 'Фантастика',
                'publisher' => 'АСТ',
                'publication_year' => 1949,
                'old_price' => null,
                'is_featured' => 1,
                'image' => '/images/books/1984.jpg'
            ],
            [
                'name' => 'Преступление и наказание',
                'author' => 'Федор Достоевский',
                'description' => 'Глубокое философское произведение о морали и redemption.',
                'price' => 520.00,
                'stock' => 5,
                'category' => 'Классика',
                'publisher' => 'Речь',
                'publication_year' => 1866,
                'old_price' => 650.00,
                'is_featured' => 0,
                'image' => '/images/books/crime.jpg'
            ]
        ];

        foreach ($books as $bookData) {
            $book = new Book();
            $book->attributes = $bookData;
            if ($book->save()) {
                echo "Добавлена книга: " . $book->name . "<br>";
            } else {
                echo "Ошибка при добавлении: " . $book->name . " - " . print_r($book->errors, true) . "<br>";
            }
        }

        echo "<br>Готово! Проверьте каталог книг.";
    }

    public function actionCheckDb()
    {
        $books = Book::find()->all();
        
        if (empty($books)) {
            echo "No books found in database<br>";
        } else {
            echo "Found " . count($books) . " books:<br><br>";
            foreach ($books as $book) {
                echo "ID: " . $book->id . "<br>";
                echo "Name: " . $book->name . "<br>";
                echo "Price: " . $book->price . "<br>";
                echo "Stock: " . $book->stock . "<br>";
                echo "Category: " . $book->category . "<br>";
                echo "Featured: " . $book->is_featured . "<br>";
                echo "---<br>";
            }
        }
        
        // Проверяем структуру таблицы
        $tableSchema = Yii::$app->db->getTableSchema('book');
        echo "<br><br>Table columns:<br>";
        foreach ($tableSchema->columns as $column) {
            echo $column->name . " (" . $column->dbType . ")<br>";
        }
    }
}