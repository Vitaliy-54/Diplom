<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Окак - отказано в доступе</title>
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <style>
        body {
            background-color: #000;
            color: #fff;
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            height: 100vh;
            overflow-x: hidden;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .split-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            width: 100%;
            overflow: hidden;
        }

        .left-half {
            position: relative;
            width: 100%;
            min-height: 60%;
            flex-grow: 1;
            padding: 15px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            opacity: 1;
            order: 1;
            overflow: auto;
        }

        .left-half.visible {
            opacity: 1;
        }

        body.visible {
            opacity: 1;
        }

        .info-container {
            position: relative;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(255, 255, 255, 0.1);
            text-align: center;
            max-width: 100%;
            width: 100%;
            box-sizing: border-box;
            margin: 10px 0;
        }

        @keyframes flicker {
            0% { opacity: 0.3; }
            25% { opacity: 1; }
            50% { opacity: 0.5; }
            75% { opacity: 1; }
            100% { opacity: 0.3; }
        }

        @keyframes colorFade {
            0% { color: rgba(200, 200, 200, 0.7); }
            100% { color: #fff; }
        }

        .error-code {
            font-size: 4.5rem;
            position: relative;
            opacity: 0.7;
            text-shadow: 3px 3px 6px rgba(0, 0, 0, 0.5);
            animation: flicker 5s infinite alternate, colorFade 9s ease-in-out forwards;
            margin: 5px 0;
        }

        h1 {
            font-size: 1.6rem;
            color: #fff;
            margin-bottom: 10px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.6);
        }

        .message {
            font-size: 1rem;
            line-height: 1.4;
            color: #ddd;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            margin-bottom: 15px;
        }

        .home-link {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #555;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s;
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);
            font-size: 0.8rem;
        }

        .home-link:hover {
            background-color: #1b5f96;
        }

        .right-half {
            width: 100%;
            min-height: 40%;
            position: relative;
            order: 2;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
        }

        .image-container {
            width: 100%;
            height: 100%;
            max-height: 40vh;
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 3s ease-in-out;
            padding: 5px;
            box-sizing: border-box;
        }

        .image-container.visible {
            opacity: 1;
        }

        .image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            pointer-events: none;
            user-select: none;
        }

        /* Десктопные стили */
        @media only screen and (min-width: 769px) {
            .split-container {
                flex-direction: row;
                height: 100vh;
            }

            .left-half {
                position: absolute;
                left: -50%;
                height: 100%;
                width: 50%;
                min-height: auto;
                transition: left 2.5s ease;
                justify-content: center;
                padding: 40px;
                order: unset;
            }

            .left-half.visible {
                left: 0;
            }

            .right-half {
                width: 50%;
                height: 100%;
                min-height: auto;
                position: absolute;
                right: 0;
                order: unset;
            }

            .image-container {
                height: 88%;
                margin-top: 8%;
                max-height: none;
                padding: 10px;
            }

            .info-container {
                padding: 40px;
                max-width: 600px;
                margin: auto;
                transition: transform 0.3s ease-in-out;
            }

            .error-code {
                font-size: 8rem;
            }

            h1 {
                font-size: 3rem;
            }

            .message {
                font-size: 1.4rem;
            }

            .home-link {
                padding: 12px 24px;
                font-size: 1rem;
                transition: transform 0.2s;
            }

            .home-link:hover {
                transform: scale(1.05);
            }
        }

        /* Медиа-запрос для промежуточных размеров */
        @media only screen and (max-width: 768px) and (min-height: 700px) {
            .left-half {
                min-height: 50%;
            }
            .right-half {
                min-height: 50%;
            }
        }
    </style>
</head>

<body>
    <div class="split-container">
        <div class="left-half">
            <div class="info-container">
                <div class="error-code">403</div>
                <h1>Отказано в доступе</h1>
                <div class="message">
                    У вас нет прав для просмотра этой страницы.<br>
                    Доступ к странице доступен только определенному кругу пользователей.
                </div>
                <a href="/" class="home-link">Окак, на главную</a>
            </div>
        </div>

        <div class="right-half">
            <div class="image-container">
                <img src="/окак.jpg" alt="Мем Окак" oncontextmenu="return false;" draggable="false">
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const leftHalf = document.querySelector('.left-half');
            const body = document.body;
            const imageContainer = document.querySelector('.image-container');

            setTimeout(() => {
                body.classList.add('visible');
                leftHalf.classList.add('visible');
                imageContainer.classList.add('visible');
            }, 100);
        });
    </script>
</body>

</html>