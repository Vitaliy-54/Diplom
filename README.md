# Neocalc — Веб-приложение для изучения излучательных переходов иона Nd³⁺

[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-777BB4?style=flat&logo=php)](https://php.net)
[![Laravel Version](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat&logo=laravel)](https://laravel.com)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql)](https://mysql.com)
[![Livewire](https://img.shields.io/badge/Livewire-3-4FB1A1?style=flat)](https://livewire.laravel.com)
[![License](https://img.shields.io/badge/License-Proprietary-red?style=flat)](LICENSE)

## 📌 О проекте

**Neocalc** — это научно-образовательное веб-приложение для моделирования и анализа излучательных переходов иона Nd³⁺ (неодима). Система позволяет исследователям, преподавателям и студентам выполнять расчёты параметров излучательных переходов, оптимизировать коэффициенты по экспериментальным данным, а также вести базу знаний с заметками, комментариями и справочными материалами.

Проект разработан в рамках дипломной работы и ориентирован на использование в учебных и научно-исследовательских целях.

---

## 🚀 Основные возможности

### 🔬 Калькулятор Nd³⁺
- Расчёт вероятностей излучательных переходов и радиационного времени жизни
- Визуализация зависимости времени жизни от температуры (графики Chart.js)
- Поддержка мультиплетов: ⁴F₃/₂, ⁴J₉/₂, ⁴J₁₁/₂
- Экспорт результатов в CSV

### 📊 Оптимизация параметров
- Оптимизация коэффициентов J9C, J11C и Kc по экспериментальным данным
- Автоматический расчёт суммы квадратов отклонений и среднеквадратичной ошибки
- Таблица сравнения параметров с процентным изменением

### 📚 Заметки
- Создание и редактирование заметок с форматированием (CKEditor)
- Прикрепление файлов к заметкам
- Теги для категоризации
- Публичные и приватные заметки
- Комментарии с древовидной структурой
- Система реакций (лайки, дизлайки, сердечки, смайлы)

### 🔐 Аутентификация и безопасность
- Стандартная сессионная аутентификация (Laravel)
- **WebAuthn (Passkeys)** — беcпарольный вход по биометрии (отпечаток пальца, Face ID, PIN-код)
- Подтверждение email через OTP-код (6 цифр, срок действия 30 минут)
- Ролевая модель RBAC (user / admin)
- Аудит действий пользователей (логины, посещения страниц, IP-адреса)

### 🔗 Публичные ссылки
- Генерация публичных ссылок на расчёты
- Защита паролем и ограничение срока действия
- Статистика просмотров
- QR-код для быстрого доступа
- Возможность копирования расчётов другими пользователями

### 📈 Администрирование
- Управление пользователями
- Модерация заметок и комментариев
- Просмотр статистики активности

---

## 🛠 Технологический стек

### Backend
| Технология | Назначение |
|------------|------------|
| **PHP 8.1+** | Язык программирования |
| **Laravel 11** | Основной фреймворк |
| **Laravel WebAuthn** | Беcпарольная аутентификация |
| **MySQL 8.0** | Реляционная база данных |
| **Eloquent ORM** | Объектно-реляционное отображение |
| **Laravel Queues / Jobs** | Асинхронная обработка |
| **Laravel Policies** | Разграничение прав доступа |

### Frontend
| Технология | Назначение |
|------------|------------|
| **Laravel Blade** | Шаблонизатор |
| **Livewire 3** | Динамические компоненты |
| **Alpine.js** | Интерактивные элементы |
| **Tailwind CSS / Bootstrap 5** | Адаптивный дизайн |
| **Chart.js** | Визуализация графиков |
| **Axios** | HTTP-запросы |
| **CKEditor 4** | WYSIWYG-редактор |
| **Highlight.js** | Подсветка синтаксиса |
| **QRCode.js** | Генерация QR-кодов |

### Инструменты разработки
| Инструмент | Назначение |
|------------|------------|
| **Visual Studio Code** | Среда разработки |
| **Composer** | Менеджер зависимостей PHP |
| **NPM** | Менеджер пакетов JavaScript |
| **Git / GitHub** | Система контроля версий |

### Тестирование
| Инструмент | Назначение |
|------------|------------|
| **Pest PHP (PHPUnit)** | Модульное и функциональное тестирование |
| **Laravel Testing Suite** | Утилиты тестирования |
| **Livewire Testing** | Тестирование Livewire-компонентов |
| **DatabaseTransactions / RefreshDatabase** | Управление состоянием БД в тестах |

---

## 📁 Структура проекта

```
neocalc/
├── app/
│   ├── Console/
│   │   └── Commands/                # Пользовательские команды Artisan
│   ├── Exceptions/                  # Обработчики исключений
│   ├── Http/
│   │   ├── Controllers/             # Контроллеры
│   │   │   ├── Auth/                # Контроллеры аутентификации
│   │   │   └── Admin/               # Административные контроллеры
│   │   ├── Middleware/              # Middleware (auth, IsAdmin, TrackUserActivity)
│   │   ├── Livewire/                # Livewire-компоненты
│   │   │   ├── Comments/            # Компоненты комментариев
│   │   │   ├── Notes/               # Компоненты заметок
│   │   │   └── Notifications/       # Компонент уведомлений
│   │   └── Requests/                # Form Request-классы валидации
│   ├── Models/                      # Модели Eloquent
│   │   ├── User.php                 # Модель пользователя
│   │   ├── Note.php                 # Модель заметки
│   │   ├── Tag.php                  # Модель тега
│   │   ├── Comment.php              # Модель комментария
│   │   ├── Task.php                 # Модель задачи
│   │   └── ...
│   ├── Notifications/               # Кастомные уведомления
│   │   ├── CustomVerifyEmail.php    # Уведомление верификации email
│   │   └── CustomPasswordReset.php  # Уведомление сброса пароля
│   ├── Policies/                    # Политики авторизации
│   │   └── CommentPolicy.php        # Политика для комментариев
│   └── Services/                    # Бизнес-логика (Сервисный слой)
│       ├── Nd3CalculatorService.php     # Расчёты Nd³⁺
│       ├── Nd3OptimizationService.php   # Оптимизация Nd³⁺
│       ├── Ho3CalculatorService.php     # Расчёты Ho³⁺
│       ├── Ho3OptimizationService.php   # Оптимизация Ho³⁺
│       └── NoteService.php              # Управление заметками
│
├── bootstrap/
│   ├── app.php                    # Инициализация приложения
│   └── cache/                     # Кэш конфигурации
│
├── config/                        # Конфигурационные файлы
│   ├── app.php
│   ├── auth.php
│   ├── database.php
│   ├── cache.php
│   └── ...
│
├── database/
│   ├── factories/                 # Фабрики для генерации тестовых данных
│   ├── migrations/                # Миграции базы данных
│   │   ├── 0001_01_01_000000_create_users_table.php
│   │   ├── 0001_01_01_000001_create_notes_table.php
│   │   └── ...
│   ├── seeders/                   # Начальные данные (сиды)
│   │   ├── DatabaseSeeder.php
│   │   ├── UserSeeder.php
│   │   └── TagSeeder.php
│   └── sql/                       # SQL-дампы (опционально)
│
├── public/                        # Публичные файлы (точка входа)
│   ├── index.php                  # Точка входа в приложение
│   ├── .htaccess                  # Конфигурация веб-сервера
│   ├── css/                       # Скомпилированные CSS-файлы
│   ├── js/                        # Скомпилированные JavaScript-файлы
│   ├── images/                    # Изображения
│   └── storage/                   # Публично доступные файлы
│       └── avatars/               # Аватары пользователей
│
├── resources/
│   ├── views/                     # Blade-шаблоны
│   │   ├── layouts/
│   │   │   └── app.blade.php      # Основной макет
│   │   ├── auth/                  # Шаблоны аутентификации
│   │   │   ├── login.blade.php
│   │   │   ├── register.blade.php
│   │   │   ├── verify-email.blade.php
│   │   │   └── reset-password.blade.php
│   │   ├── notes/                 # Шаблоны заметок
│   │   │   ├── index.blade.php
│   │   │   ├── show.blade.php
│   │   │   ├── create.blade.php
│   │   │   └── edit.blade.php
│   │   ├── calculator/            # Шаблоны калькулятора
│   │   │   ├── nd3.blade.php
│   │   │   └── ho3.blade.php
│   │   ├── profile/               # Шаблоны профиля
│   │   ├── admin/                 # Административные шаблоны
│   │   └── components/            # Переиспользуемые компоненты
│   └── lang/                      # Файлы локализации
│       └── en/
│
├── routes/
│   ├── web.php                    # Веб-маршруты
│   ├── api.php                    # API-маршруты
│   ├── console.php                # Консольные маршруты
│   └── channels.php               # WebSocket-маршруты
│
├── storage/                       # Хранилище файлов
│   ├── app/
│   │   ├── public/                # Публичные загрузки
│   │   │   ├── files/             # Прикреплённые файлы к заметкам
│   │   │   └── literature/        # Справочные материалы
│   │   └── private/               # Приватные файлы
│   ├── framework/                 # Кэш, сессии, представления
│   └── logs/                      # Логи приложения
│
├── tests/                         # Модульные и функциональные тесты
│   ├── Feature/
│   │   ├── Auth/
│   │   │   ├── AuthenticationTest.php
│   │   │   └── EmailVerificationTest.php
│   │   ├── Notes/
│   │   │   └── NoteTest.php
│   │   └── ...
│   └── Unit/
│       ├── Models/
│       │   └── UserTest.php
│       └── Services/
│           ├── Nd3CalculatorServiceTest.php
│           ├── Nd3OptimizationServiceTest.php
│           └── NoteServiceTest.php
│
├── .env                           # Переменные окружения
├── .env.example                   # Пример переменных окружения
├── .gitignore                     # Исключения Git
├── composer.json                  # Зависимости PHP
├── composer.lock                  # Lock-файл Composer
├── package.json                   # Зависимости JavaScript
├── package-lock.json              # Lock-файл NPM
├── vite.config.js                 # Конфигурация Vite (сборщик)
├── tailwind.config.js             # Конфигурация Tailwind CSS
├── phpunit.xml                    # Конфигурация PHPUnit
├── artisan                        # Консольный интерфейс Laravel
└── README.md                      # Описание проекта
```

---

## 📊 Заметки

Основные таблицы:

| Таблица | Назначение |
|---------|------------|
| `users` | Пользователи системы |
| `web_authn_credentials` | WebAuthn-ключи (Passkeys) |
| `nd3_calculation_history` | История расчётов Nd³⁺ |
| `ho_calculation_history` | История расчётов Ho³⁺ |
| `calculation_share_links` | Публичные ссылки на расчёты |
| `notes` | Заметки базы знаний |
| `tags` / `note_tag` | Теги для категоризации заметок |
| `comments` | Комментарии (поддержка вложенности) |
| `note_reactions` / `comment_reactions` | Реакции пользователей |
| `tasks` | Персональные задачи пользователя |
| `user_statistic` / `user_logs` | Аудит активности |

> Подробная ER-диаграмма представлена в документации.

---

## 🧪 Тестирование

Проект покрыт модульными и функциональными тестами:

| Класс тестов | Кол-во тестов | Описание |
|--------------|---------------|----------|
| `AuthenticationTest` | 3 | Вход с корректными/некорректными данными, выход |
| `EmailVerificationTest` | 3 | Отправка кода, успешная верификация, отклонение |
| `UserTest` | 4 | Создание, роли, кэш, связи |
| `Nd3CalculatorServiceTest` | 7 | Арифметика, исключения |
| `Nd3OptimizationServiceTest` | 2 | Оптимизация, ошибки |
| `NoteServiceTest` | 4 | CRUD заметок |
| `VolumeTestingTest` | 1 | Объёмное тестирование производительности |

### Запуск тестов
```bash
php artisan test
# или
vendor/bin/pest
```

## 🚀 Установка и запуск

### Требования

- PHP 8.1+
- Composer
- MySQL 8.0+
- Node.js & NPM

### Инструкция

```bash
# 1. Клонирование репозитория
git clone https://github.com/yourusername/neocalc.git
cd neocalc

# 2. Установка зависимостей PHP
composer install

# 3. Установка зависимостей JavaScript
npm install

# 4. Компиляция assets
npm run build

# 5. Настройка окружения
cp .env.example .env
php artisan key:generate

# 6. Настройка базы данных в .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=neocalc
# DB_USERNAME=root
# DB_PASSWORD=

# 7. Запуск миграций и сидеров
php artisan migrate --seed

# 8. Запуск сервера разработки
php artisan serve
```

## 📄 Лицензия

Проект разработан в рамках дипломной работы. Все права защищены.

---

## 👤 Автор

**Андреев Виталий, группа ИТ-12**

[![GitHub](https://img.shields.io/badge/GitHub-@Vitaliy--54-181717?style=flat&logo=github)](https://github.com/Vitaliy-54)
[![Email](https://img.shields.io/badge/Email-andreev.vstu@gmail.com-EA4335?style=flat&logo=gmail)](mailto:andreev.vstu@gmail.com)
