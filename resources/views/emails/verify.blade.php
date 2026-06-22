<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Подтверждение электронной почты</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px 0;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 0;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .email-header {
            background-color: #333333;
            text-align: center;
            padding: 15px 10px;
        }

        .email-header img {
            max-width: 100px;
            height: auto;
        }

        .email-body {
            padding: 30px;
            color: #444444;
            line-height: 1.6;
        }

        .email-body h1 {
            color: #333333;
            font-size: 24px;
            margin-top: 0;
        }

        .email-body p {
            color:rgb(44, 44, 44);
            font-size: 14px;
            margin-top: 0;
        }

        .verification-code {
            background-color: #f0f0f0;
            border-radius: 5px;
            padding: 15px;
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 20px 0;
            color: #2c3e50;
            border: 1px dashed #cccccc;
        }

        .important-note {
            background-color: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 14px;
            color: #ff0000;
        }

        .email-footer {
            text-align: center;
            padding: 20px;
            background-color: #f5f5f5;
            font-size: 12px;
            color: #777777;
            border-top: 1px solid #eeeeee;
        }

        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #333333;
            color: white !important;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin: 10px 0;
        }

        .text-center {
            text-align: center;
        }

        .small-text {
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://neocalc.site/icon.png" alt="Логотип">
        </div>
        
        <div class="email-body">
            <h1>Подтверждение электронной почты</h1>
            
            <p>Здравствуйте!</p>
            
            <p>Вы получили это письмо, потому что зарегистрировались на нашем сайте. Для завершения регистрации используйте следующий код подтверждения:</p>
            
            <div class="verification-code">
                {{ $code }}
            </div>
            
            <div class="important-note">
                <strong>Важно:</strong> Код действителен в течение 30 минут. Никому не сообщайте этот код.
            </div>
            
            <p>Если вы не регистрировались на нашем сайте, проигнорируйте это письмо.</p>
            
        </div>
        
        <div class="email-footer">
            <p class="small-text">Это письмо отправлено автоматически. Пожалуйста, не отвечайте на него.</p>
            <p>&copy; {{ date('Y') }} NeoCalc. Все права защищены.</p>
        </div>
    </div>
</body>

</html>