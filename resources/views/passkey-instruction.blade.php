<!DOCTYPE html>
<html lang="ru" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Как создать ключ входа?</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Figtree', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: #111827;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px 0;
        }

        /* Карточка инструкции */
        .instruction-card {
            background: #1f2937;
            border-radius: 32px;
            padding: 28px 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            border: 1px solid #374151;
        }

        /* Заголовок */
        .header {
            text-align: center;
            margin-bottom: 28px;
        }

        .fingerprint-icon {
            width: 64px;
            height: 64px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
        }

        .fingerprint-icon svg {
            width: 32px;
            height: 32px;
            color: white;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            color: #f3f4f6;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #9ca3af;
            font-size: 14px;
        }

        /* Секции */
        .section {
            margin-bottom: 28px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 600;
            color: #f3f4f6;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #374151;
        }

        .section-title .step-number {
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 700;
        }

        /* Карточка биометрии */
        .biometric-card {
            background: #374151;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            border: 1px solid #4b5563;
        }

        .biometric-card:hover {
            border-color: #6366f1;
        }

        /* Список */
        .step-list {
            list-style: none;
            padding: 0;
        }

        .step-list li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
            font-size: 14px;
            color: #9ca3af;
            line-height: 1.5;
        }

        .step-list .check {
            width: 20px;
            height: 20px;
            background: #10b981;
            color: white;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Примечание */
        .note {
            background: #422c1c;
            border-left: 4px solid #f59e0b;
            padding: 14px 16px;
            border-radius: 12px;
            margin-top: 20px;
            font-size: 13px;
            color: #fcd34d;
            line-height: 1.5;
        }

        .note-icon {
            display: inline-block;
            margin-right: 8px;
        }

        /* Кнопка — полное удаление любых выделений на мобильных устройствах */
        .btn-back {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 40px;
            font-weight: 600;
            font-size: 16px;
            margin-top: 24px;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            
            /* Ключевые стили для удаления синего прямоугольника на мобильных */
            outline: none;
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            user-select: none;
            
            /* Дополнительная защита от WebKit-ов */
            -webkit-appearance: none;
            appearance: none;
            
            position: relative;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.4);
        }

        /* Для фокуса с клавиатуры (доступность) — аккуратное свечение, БЕЗ синего прямоугольника */
        .btn-back:focus-visible {
            outline: none;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.5), 0 0 0 1px #1f2937;
            transform: scale(0.99);
        }

        /* При активном нажатии (тач) — никакой синей подсветки, только легкое изменение */
        .btn-back:active {
            transform: translateY(1px);
            background: linear-gradient(135deg, #4f46e5, #3730a3);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            /* Гарантированное удаление обводки при нажатии */
            outline: none;
        }

        /* Дополнительный глобальный сброс для всех элементов на случай нежелательных выделений */
        button, 
        button:focus, 
        button:active,
        .btn-back,
        .btn-back:focus,
        .btn-back:active {
            outline: none;
            -webkit-tap-highlight-color: transparent;
        }
        
        .fingerprint-svg {
            fill: white;
            stroke: white;
        }

        /* Адаптивность */
        @media (max-width: 480px) {
            body {
                padding: 12px;
            }
            
            .instruction-card {
                padding: 20px 16px;
            }
            
            h1 {
                font-size: 20px;
            }
            
            .section-title {
                font-size: 16px;
            }
            
            .step-list li {
                font-size: 13px;
            }
        }
        
        /* iOS Safari специфичный фикс для синего оверлея */
        @supports (-webkit-touch-callout: none) {
            .btn-back {
                -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
                -webkit-tap-highlight-color: transparent;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="instruction-card">
            <div class="header">
                <div class="fingerprint-icon">
                    <svg class="fingerprint-svg" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
                        <path d="M140.424 38.019a3.6 3.6 0 0 1-1.777-.462C123.81 29.934 110.983 26.7 95.528 26.7c-15.223 0-29.75 3.619-42.964 10.857-1.854 1.001-4.172.308-5.254-1.54-1.005-1.848-.31-4.235 1.545-5.236C63.228 22.85 78.992 19 95.528 19c16.537 0 30.91 3.619 46.673 11.55 1.932 1.155 2.628 3.465 1.623 5.313-.695 1.386-1.932 2.156-3.4 2.156ZM29.846 78.444a4.036 4.036 0 0 1-2.24-.693c-1.624-1.232-2.165-3.619-.928-5.39 7.65-10.78 17.386-19.25 28.977-25.179 24.419-12.474 55.328-12.551 79.669-.077 11.591 5.929 21.327 14.245 28.977 25.025 1.237 1.694.773 4.158-.927 5.39-1.777 1.232-4.173.847-5.409-.77-6.955-9.856-15.764-17.479-26.196-22.792-22.177-11.319-50.536-11.319-72.636.077-10.51 5.39-19.319 13.09-26.273 22.715-.618 1.155-1.778 1.694-3.014 1.694Zm48.296 92.939c-1.005 0-1.932-.385-2.705-1.155-6.722-6.699-10.354-11.011-15.532-20.328-5.332-9.471-8.113-21.021-8.113-33.418 0-22.869 19.627-41.503 43.736-41.503 24.11 0 43.737 18.634 43.737 41.503 0 1.021-.407 2-1.132 2.722a3.87 3.87 0 0 1-5.464 0 3.844 3.844 0 0 1-1.131-2.722c0-18.634-16.15-33.803-36.01-33.803-19.859 0-36.01 15.169-36.01 33.803 0 11.088 2.474 21.329 7.187 29.568 4.946 8.932 8.346 12.705 14.296 18.711a3.943 3.943 0 0 1 0 5.467c-.927.77-1.855 1.155-2.86 1.155Zm55.405-14.245c-9.196 0-17.309-2.31-23.955-6.853-11.514-7.777-18.391-20.405-18.391-33.803 0-1.021.407-2 1.132-2.722a3.871 3.871 0 0 1 5.464 0 3.843 3.843 0 0 1 1.131 2.722c0 10.857 5.564 21.098 14.991 27.412 5.487 3.696 11.9 5.467 19.628 5.467 1.854 0 4.945-.231 8.036-.77 2.087-.385 4.173 1.001 4.482 3.157.386 2.002-1.005 4.081-3.168 4.466-4.405.847-8.268.924-9.35.924ZM118.015 173h-1.005c-12.286-3.542-20.323-8.085-28.745-16.324-10.819-10.626-16.769-24.948-16.769-40.194 0-12.474 10.664-22.638 23.8-22.638 13.137 0 23.801 10.164 23.801 22.638 0 8.239 7.341 14.938 16.072 14.938 8.887 0 16.073-6.699 16.073-14.938 0-29.029-25.114-52.591-56.023-52.591-21.945 0-42.191 12.166-51.077 31.031-3.014 6.237-4.56 13.552-4.56 21.56 0 6.006.541 15.477 5.178 27.797.773 2.002-.232 4.235-2.241 4.928-2.01.693-4.25-.308-4.946-2.233-3.863-10.087-5.64-20.174-5.64-30.492 0-9.24 1.777-17.633 5.254-24.948 10.277-21.483 33.073-35.42 58.032-35.42 35.082 0 63.751 27.027 63.751 60.291 0 12.474-10.664 22.638-23.801 22.638-13.136 0-23.8-10.164-23.8-22.638 0-8.239-7.186-14.938-16.073-14.938-8.886 0-16.072 6.699-16.072 14.938 0 13.167 5.1 25.487 14.45 34.727 7.341 7.238 14.373 11.242 25.268 14.168 2.086.616 3.246 2.772 2.705 4.774-.387 1.771-2.009 2.926-3.632 2.926Z"/>
                    </svg>
                </div>
                <h1>Создание ключа входа</h1>
                <p class="subtitle">Входите в аккаунт с помощью отпечатка пальца, Face ID или PIN-кода</p>
            </div>

            <!-- Шаг 1 -->
            <div class="section">
                <div class="section-title">
                    <span class="step-number">1</span>
                    <span>Перейдите в профиль</span>
                </div>
                <ul class="step-list">
                    <li>
                        <span class="check">✓</span>
                        <span>Войдите в свой аккаунт</span>
                    </li>
                    <li>
                        <span class="check">✓</span>
                        <span>Нажмите на своё имя в правом верхнем углу</span>
                    </li>
                    <li>
                        <span class="check">✓</span>
                        <span>Выберите пункт <strong>«Профиль»</strong> в выпадающем меню</span>
                    </li>
                </ul>
            </div>

            <!-- Шаг 2 -->
            <div class="section">
                <div class="section-title">
                    <span class="step-number">2</span>
                    <span>Добавьте ключ</span>
                </div>
                <ul class="step-list">
                    <li>
                        <span class="check">✓</span>
                        <span>Найдите блок <strong>«Биометрическая аутентификация»</strong></span>
                    </li>
                    <li>
                        <span class="check">✓</span>
                        <span>Нажмите кнопку <strong>«Добавить биометрию»</strong></span>
                    </li>
                    <li>
                        <span class="check">✓</span>
                        <span>Введите название устройства (например, «iPhone 15» или «Домашний ПК») или оставьте по умолчанию</span>
                    </li>
                </ul>
            </div>

            <!-- Шаг 3 -->
            <div class="section">
                <div class="section-title">
                    <span class="step-number">3</span>
                    <span>Подтвердите биометрию</span>
                </div>
                <div class="biometric-card">
                    <div style="font-size: 48px; margin-bottom: 12px;">🔐</div>
                    <p style="font-size: 15px; color: #f3f4f6; line-height: 1.6; margin-bottom: 0;">
                        Следуйте инструкции на вашем устройстве
                    </p>
                    <p style="font-size: 13px; color: #9ca3af; margin-top: 8px;">
                        Отпечаток пальца • Face ID • PIN-код • Пароль
                    </p>
                </div>
            </div>

            <!-- Готово -->
            <div class="section">
                <div class="section-title">
                    <span class="step-number">✓</span>
                    <span>Готово!</span>
                </div>
                <ul class="step-list">
                    <li>
                        <span class="check">✓</span>
                        <span>Ключ успешно добавлен — теперь вы можете входить по биометрии</span>
                    </li>
                    <li>
                        <span class="check">✓</span>
                        <span>Ключ хранится ТОЛЬКО на вашем устройстве и не покидает его</span>
                    </li>
                    <li>
                        <span class="check">✓</span>
                        <span>Никто, включая нас, не может использовать ваш ключ без вашего разрешения</span>
                    </li>
                </ul>
            </div>

            <!-- Примечание -->
            <div class="note">
                <span class="note-icon">🔒</span>
                <strong>Безопасно и конфиденциально</strong><br>
                Ваш отпечаток пальца (или лицо) никогда не покидает ваше устройство. Мы получаем только криптографический ключ, который не может быть использован для восстановления биометрических данных.
            </div>

            
            <!-- Back Button -->
            <a href="{{ route('login') }}" class="btn-back" wire:navigate>
                {{ __('Назад к входу') }}
             </a>
       
        </div>
    </div>
</body>
</html>