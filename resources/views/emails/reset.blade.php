<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Сброс пароля</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #333333 !important;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color:rgb(245, 244, 244); /* светло-серый фон */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color:rgb(78, 78, 78);
            text-align: center;
            padding: 20px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .email-header img {
            width: 100px;
        }
        .email-body {
            padding: 20px;
        }
        .email-body h1 {
            font-size: 24px;
            color: #333333;
        }
        .email-body p {
            font-size: 16px;
            color: #555555;
        }
        .email-divider {
            margin: 20px 0;
            border-bottom: 1px solid #dddddd;
        }
        .email-footer {
            text-align: center;
            padding-top: 20px;
            font-size: 12px;
            color: #777777;
        }
        .reset-button {
            display: inline-block;
            background-color: #4CAF50;
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-header">
            <img src="https://neocalc.site/icon.png" alt="Логотип">
        </div>
        <div class="email-body">
            <h1>Здравствуйте!</h1>
            <p>Вы получили это письмо, потому что мы получили запрос на сброс пароля для вашей учетной записи.</p>
            <p>Для сброса пароля нажмите на кнопку ниже:</p>
            <p><a href="{{ $url }}" class="reset-button">Сбросить пароль</a></p> 
            <p>Открывайте ссылку в том же браузере, где вы запрашивали сброс пароля</p>
            <p>Срок действия этой ссылки для сброса пароля истечет через {{ config('auth.passwords.users.expire') }} минут.</p>
            <div class="email-divider"></div>
            <p>Если вы не запрашивали сброс пароля, никаких дальнейших действий не требуется.</p>
            <p>Если у вас возникли проблемы с нажатием кнопки "Сбросить пароль", скопируйте и вставьте приведенный ниже URL-адрес в свой веб-браузер: {{ $url }}</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} NeoCalc. Все права защищены.</p>
        </div>
    </div>
</body>

</html>
