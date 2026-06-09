<?php
/** @var string $email */
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Добро пожаловать в нашу рассылку</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #a8c3d5 0%, #b8c4b8 100%); padding: 20px; text-align: center; }
        .content { background: #f8f5f0; padding: 30px; }
        .footer { background: #e8e6e1; padding: 20px; text-align: center; font-size: 12px; color: #666; }
        .btn { display: inline-block; background: #a8c3d5; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: white; margin: 0;">📚 Книжный магазин</h1>
        </div>
        
        <div class="content">
            <h2>Спасибо за подписку!</h2>
            <p>Здравствуйте!</p>
            <p>Вы успешно подписались на рассылку нашего книжного магазина. Теперь вы будете первыми узнавать о:</p>
            <ul>
                <li>🔥 Горячих скидках и акциях</li>
                <li>📚 Новинках и бестселлерах</li>
                <li>🎁 Специальных предложениях</li>
                <li>📖 Рекомендациях от наших экспертов</li>
            </ul>
            
            <p>Если вы не подписывались на нашу рассылку, просто проигнорируйте это письмо.</p>
            
            <p style="text-align: center;">
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/site/index']) ?>" class="btn">
                    Перейти в магазин
                </a>
            </p>
        </div>
        
        <div class="footer">
            <p>© <?= date('Y') ?> Книжный магазин. Все права защищены.</p>
            <p>
                <a href="<?= Yii::$app->urlManager->createAbsoluteUrl(['/site/unsubscribe', 'email' => $email]) ?>" 
                   style="color: #666; font-size: 12px;">
                    Отписаться от рассылки
                </a>
            </p>
        </div>
    </div>
</body>
</html>