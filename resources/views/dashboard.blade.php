  <!DOCTYPE html>
  <html lang="ru" class="scroll-smooth">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Главная</title>
      <!-- Подключение Bootstrap Icons -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">
      <!-- Подключение Tailwind CSS с анимациями -->
      <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
      <!-- Animate.css -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
      <!-- Подключение ScrollReveal в head с defer -->
      <script src="https://unpkg.com/scrollreveal@4.0.9/dist/scrollreveal.min.js" defer></script>

      <style>
          .dark .gradient-text {
              background: linear-gradient(90deg, #3b82f6, #8b5cf6);
              -webkit-background-clip: text;
              background-clip: text;
              color: transparent;
          }

          .gradient-text {
              background: linear-gradient(90deg, rgb(48, 105, 196), rgb(124, 85, 216));
              -webkit-background-clip: text;
              background-clip: text;
              color: transparent;
          }

          .card-hover-effect {
              transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
              box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
              position: relative;
              overflow: hidden;
          }

          .card-hover-effect::before {
              content: '';
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
              opacity: 0;
              transition: opacity 0.4s ease;
          }

          .card-hover-effect:hover {
              box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
              transform: translateY(-8px);
          }

          .card-hover-effect:hover::before {
              opacity: 1;
          }

          .card-hover-effect:hover .icon-container {
              transform: rotate(15deg) scale(1.1);
              background: linear-gradient(135deg, #3b82f6, #8b5cf6);
          }

          .icon-container {
              transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
          }

          .wave {
              animation: wave 3s linear infinite;
          }

          @keyframes wave {

              0%,
              100% {
                  transform: rotate(0deg);
              }

              25% {
                  transform: rotate(5deg);
              }

              75% {
                  transform: rotate(-5deg);
              }
          }

          body {
              font-size: 0.9375rem;
          }

          h1 {
              font-size: 1.75rem;
          }

          h2 {
              font-size: 1.25rem;
          }

          .email-link {
              transition: all 0.3s ease;
          }

          .email-link:hover {
              color: #3b82f6;
              transform: translateX(5px);
          }

          /* Анимация конверта */
          .envelope-icon {
              animation: float 4s ease-in-out infinite;
          }

          @keyframes float {

              0%,
              100% {
                  transform: translateY(0) rotate(0deg);
              }

              25% {
                  transform: translateY(-5px) rotate(5deg);
              }

              50% {
                  transform: translateY(0) rotate(0deg);
              }

              75% {
                  transform: translateY(-5px) rotate(-5deg);
              }
          }

          /* Анимация при наведении на блок с контактами */
          .contact-block:hover .envelope-icon {
              animation: shake 0.5s ease-in-out;
          }

          @keyframes shake {

              0%,
              100% {
                  transform: rotate(0deg);
              }

              20% {
                  transform: rotate(10deg);
              }

              40% {
                  transform: rotate(-10deg);
              }

              60% {
                  transform: rotate(10deg);
              }

              80% {
                  transform: rotate(-10deg);
              }
          }

          /* Анимация пламени */
          .fire-container {
              text-align: center;
              margin-bottom: 1rem;
          }

          .fire-svg {
              width: 100%;
              height: 100%;
          }

          .cls-3 {
              fill: url(#linear-gradient-1);
              animation: flicker 3s ease-in-out infinite alternate;
              transform-origin: 50% 50%;
          }

          .cls-4 {
              fill: #fc9502;
              animation: flicker 2s ease-in-out infinite alternate;
              animation-delay: 0.5s;
              transform-origin: 50% 50%;
          }

          .cls-5 {
              fill: #fce202;
              animation: flicker 1.5s ease-in-out infinite alternate;
              animation-delay: 0.2s;
              transform-origin: 50% 50%;
          }

          @keyframes flicker {

              0%,
              100% {
                  transform: scale(1);
                  opacity: 1;
              }

              25% {
                  transform: scale(0.98, 1.02);
                  opacity: 0.95;
              }

              50% {
                  transform: scale(1.02, 0.98);
                  opacity: 0.98;
              }

              75% {
                  transform: scale(0.99, 1.01);
              }
          }

          /* Мягкий ареол с легкой анимацией (на 20% ярче) */
          .fire-svg {
              filter:
                  drop-shadow(0 0 4px rgba(255, 180, 60, 0.48)) drop-shadow(0 0 7px rgba(255, 120, 40, 0.36)) drop-shadow(0 0 10px rgba(255, 80, 20, 0.24));
              animation: soft-glow 3s ease-in-out infinite alternate;
          }

          @keyframes soft-glow {
              0% {
                  filter:
                      drop-shadow(0 0 4px rgba(255, 180, 60, 0.48)) drop-shadow(0 0 7px rgba(255, 120, 40, 0.36)) drop-shadow(0 0 10px rgba(255, 80, 20, 0.24));
              }

              100% {
                  filter:
                      drop-shadow(0 0 5px rgba(255, 190, 70, 0.6)) drop-shadow(0 0 9px rgba(255, 130, 50, 0.48)) drop-shadow(0 0 14px rgba(255, 90, 30, 0.36));
              }
          }

          /* Для лучшей производительности можно добавить will-change */
          .fire-wrapper {
              will-change: filter;
          }

          /* Адаптация для мобильных */
          @media (max-width: 767px) {
              .fire-wrapper {
                  width: 100px;
                  height: 100px;
                  margin: 0 auto 0.5rem;
              }

              .user-stats {
                  order: 2;
                  width: 100%;
              }

              .fire-wrapper {
                  order: 1;
              }

              .stats-container {
                  flex-direction: column;
                  align-items: center;
              }
          }

          @media (min-width: 768px) {
              .fire-wrapper {
                  width: 150px;
                  height: 150px;
                  margin-left: 2rem;
              }

              .stats-container {
                  flex-direction: row;
                  align-items: flex-start;
              }
          }
      </style>
  </head>

  <body class="bg-gray-50 dark:bg-gray-900">
      <x-app-layout>
          <x-slot name="header">
              <div class="flex items-center justify-between">
                  <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                      {{ __('Главная') }}
                  </h2>
                  <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                      {{ now()->translatedFormat('d F Y') }}
                  </div>
              </div>
          </x-slot>

          <x-slot name="title">
              {{ __('Главная') }}
          </x-slot>

          <div class="py-6 px-4">
                <!-- Блок "Добро пожаловать" с анимацией -->
                <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 animate__animated animate__fadeIn">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-700 overflow-hidden shadow-xl sm:rounded-lg transform transition-all duration-500 hover:shadow-2xl">
                        <div class="p-6 sm:p-8 text-center">
                            <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight mb-4 animate__animated animate__fadeInDown">
                                <span class="wave inline-block">👋</span> {{ __('Добро пожаловать!') }}
                            </h1>
                            <p class="text-base text-blue-100 dark:text-gray-300 leading-relaxed max-w-4xl mx-auto animate__animated animate__fadeIn animate__delay-1s">
                                {{ __('На нашем сайте представлены интерактивные калькуляторы для расчета времен жизни возбужденных состояний редкоземельных ионов. 
                                Инструменты позволяют исследовать температурные зависимости люминесценции и анализировать влияние кристаллического поля на спектроскопические характеристики материалов.') }}
                            </p>
                        </div>
                    </div>
                </div>

              <!-- Статистика пользователей с анимированным пламенем -->
              <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8 stats-container flex flex-col md:flex-row justify-center items-center md:items-start">
                  <!-- Текстовая статистика - теперь занимает больше места -->
                  <div class="user-stats md:max-w-2xl animate__animated animate__fadeIn" style="animation-duration: 2s; animation-delay: 0.4s;">
                      <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center md:text-left">
                          <span class="gradient-text">
                              {{ __('Топ пользователей по посещениям страниц') }}
                          </span>
                      </h2>

                      <ul class="bg-gray-200 dark:bg-gray-800 border border-gray-400 dark:border-gray-700 rounded-xl shadow-lg overflow-hidden animate__animated animate__fadeIn">
                          @foreach($userStats as $index => $stat)
                          <li class="group hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors duration-200 ease-in-out">
                              <!-- Добавляем разделитель, кроме первого элемента -->
                              @if($index > 0)
                              <div class="border-t border-gray-400 dark:border-gray-700"></div>
                              @endif

                              <div class="flex items-center justify-between px-4 py-3">
                                  <!-- Левая часть с аватаркой и именем -->
                                  <div class="flex items-center space-x-4">
                                      <div class="relative">
                                          <!-- Аватарка пользователя с ссылкой -->
                                          <a href="{{ route('user.info', ['user' => $stat->user->id]) }}" class="block">
                                              @php
                                              $avatarDir = "avatars/{$stat->user->id}";
                                              $avatarFile = collect(Storage::files($avatarDir))
                                              ->first(fn($f) => preg_match('/^avatars\/' . $stat->user->id . '\/avatar\.(jpg|jpeg|png|svg|gif)$/i', $f));
                                              @endphp

                                              <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 overflow-hidden flex items-center justify-center">
                                                  @if($avatarFile)
                                                  <img src="{{ route('avatar.serve', [
                'user' => $stat->user->id,
                'filename' => basename($avatarFile)
            ]) }}"
                                                      alt="Avatar"
                                                      class="h-full w-full object-cover">
                                                  @else
                                                  @if($stat->user->role === 'admin')
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                  </svg>
                                                  @else
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                  </svg>
                                                  @endif
                                                  @endif
                                              </div>
                                          </a>

                                          <!-- Индикатор активности -->
                                          @php
                                          $isOnline = $stat->user->id === auth()->id() ||
                                          ($stat->user->lastSession && $stat->user->lastSession->last_activity > now()->subMinutes(5)->timestamp);
                                          @endphp
                                          <span class="absolute bottom-0 right-0 w-3 h-3 rounded-full border-2 border-white dark:border-gray-800
                {{ $isOnline ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                      </div>

                                      <!-- Имя пользователя с ссылкой -->
                                      <div>
                                          <a href="{{ route('user.info', ['user' => $stat->user->id]) }}" class="group">
                                              <h3 class="font-semibold text-gray-800 dark:text-gray-100 hover:text-blue-700 dark:hover:text-blue-400 transition-colors">
                                                  {{ $stat->user->name ?? 'Пользователь' }}
                                              </h3>
                                              <p class="text-xs text-gray-600 dark:text-gray-400">
                                                  @if($isOnline)
                                                  <span class="text-green-700 dark:text-green-400">Сейчас онлайн</span>
                                                  @elseif($stat->user->logs->first() && $stat->user->logs->first()->last_activity_at)
                                                  @php
                                                  $lastActivity = \Carbon\Carbon::parse($stat->user->logs->first()->last_activity_at);
                                                  @endphp
                                                  Был в сети:
                                                  @if($lastActivity->isToday())
                                                  сегодня в {{ $lastActivity->format('H:i') }}
                                                  @elseif($lastActivity->isYesterday())
                                                  вчера в {{ $lastActivity->format('H:i') }}
                                                  @else
                                                  {{ $lastActivity->format('d.m.Y H:i') }}
                                                  @endif
                                                  @else
                                                  <span class="text-gray-600 dark:text-gray-500">Нет данных о посещениях</span>
                                                  @endif
                                              </p>
                                          </a>
                                      </div>
                                  </div>

                                  <!-- Правая часть с количеством посещений -->
                                  <div class="flex items-center">
                                      <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs sm:text-sm font-medium bg-blue-600 text-blue-100 dark:bg-blue-900 dark:text-blue-200">
                                          <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 sm:h-4 sm:w-4 mr-0.5 sm:mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                          </svg>
                                          <span class="hidden sm:inline">{{ $stat->visits }} {{ trans_choice('посещение|посещения|посещений', $stat->visits) }}</span>
                                          <span class="sm:hidden">{{ $stat->visits }}</span>
                                      </span>
                                  </div>
                              </div>
                          </li>
                          @endforeach
                      </ul>
                      <span class="block text-xs mt-1 text-gray-500/80 dark:text-gray-500 italic transition-opacity hover:opacity-100 opacity-90">
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                          </svg>
                          Администратор может очистить статистику
                      </span>
                  </div>

                  <!-- Анимированное пламя - увеличено и выровнено -->
                  <div class="fire-wrapper w-24 h-24 md:w-48 md:h-48 relative animate__animated animate__fadeIn" style="animation-duration: 2s; animation-delay: 0.4s;">
                      <svg class="fire-svg" viewBox="0 0 200 255" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
                          <defs>
                              <linearGradient id="linear-gradient-1" gradientUnits="userSpaceOnUse" x1="94.141" y1="255" x2="94.141" y2="0.188">
                                  <stop offset="0" stop-color="#ff4c0d" />
                                  <stop offset="1" stop-color="#fc9502" />
                              </linearGradient>
                          </defs>
                          <g id="fire">
                              <path d="M187.899,164.809 C185.803,214.868 144.574,254.812 94.000,254.812 C42.085,254.812 -0.000,211.312 -0.000,160.812 C-0.000,154.062 -0.121,140.572 10.000,117.812 C16.057,104.191 19.856,95.634 22.000,87.812 C23.178,83.513 25.469,76.683 32.000,87.812 C35.851,94.374 36.000,103.812 36.000,103.812 C36.000,103.812 50.328,92.817 60.000,71.812 C74.179,41.019 62.866,22.612 59.000,9.812 C57.662,5.384 56.822,-2.574 66.000,0.812 C75.352,4.263 100.076,21.570 113.000,39.812 C131.445,65.847 138.000,90.812 138.000,90.812 C138.000,90.812 143.906,83.482 146.000,75.812 C148.365,67.151 148.400,58.573 155.999,67.813 C163.226,76.600 173.959,93.113 180.000,108.812 C190.969,137.321 187.899,164.809 187.899,164.809 Z" id="path-1" class="cls-3" fill-rule="evenodd" />
                              <path d="M94.000,254.812 C58.101,254.812 29.000,225.711 29.000,189.812 C29.000,168.151 37.729,155.000 55.896,137.166 C67.528,125.747 78.415,111.722 83.042,102.172 C83.953,100.292 86.026,90.495 94.019,101.966 C98.212,107.982 104.785,118.681 109.000,127.812 C116.266,143.555 118.000,158.812 118.000,158.812 C118.000,158.812 125.121,154.616 130.000,143.812 C131.573,140.330 134.753,127.148 143.643,140.328 C150.166,150.000 159.127,167.390 159.000,189.812 C159.000,225.711 129.898,254.812 94.000,254.812 Z" id="path-2" class="cls-4" fill-rule="evenodd" />
                              <path d="M95.000,183.812 C104.250,183.812 104.250,200.941 116.000,223.812 C123.824,239.041 112.121,254.812 95.000,254.812 C77.879,254.812 69.000,240.933 69.000,223.812 C69.000,206.692 85.750,183.812 95.000,183.812 Z" id="path-3" class="cls-5" fill-rule="evenodd" />
                          </g>
                      </svg>
                  </div>
              </div>

              <!-- Блоки с анимациями -->
              <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-6">
                  <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4 text-center animate__animated animate__fadeIn">
                      <span class="gradient-text">{{ __('Наши ресурсы') }}</span>
                  </h2>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                      @foreach ($blocks as $index => $block)
                      <a href="{{ $block->link }}" target="_blank"
                          class="card-hover-effect bg-gray-300 dark:bg-gray-800 rounded-xl overflow-hidden border border-gray-400 dark:border-gray-700 animate__animated animate__fadeInUp animate-on-scroll"
                          style="animation-delay: {{ $index * 0.1 + 0.3 }}s">
                          <div class="p-6 text-center relative z-10">
                              <div class="icon-container bg-gradient-to-r from-blue-400 to-purple-500 text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-5">
                                  <i class="{{ $block->icon }} text-3xl"></i>
                              </div>
                              <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-3">{{ $block->title }}</h2>
                              <p class="text-gray-600 dark:text-gray-400 mb-4">
                                  {{ $block->description }}
                              </p>
                          </div>
                      </a>
                      @endforeach
                  </div>
              </div>

              <!-- Дополнительный информационный блок -->
              <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 mt-12 contact-block animate-on-scroll">
                  <div class="bg-gray-300 dark:bg-gray-800 rounded-xl shadow-md overflow-hidden animate__animated animate__fadeIn animate__delay-3s transform transition-all duration-300">
                      <div class="md:flex">
                          <div class="md:flex-shrink-0 md:w-1/3 bg-gradient-to-br from-blue-300 to-purple-400 dark:from-blue-300 dark:to-purple-400 flex items-center justify-center p-8">
                              <i class="bi bi-envelope text-6xl text-blue-600 dark:text-blue-600 envelope-icon"></i>
                          </div>
                          <div class="p-8 md:w-2/3">
                              <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Свяжитесь с нами</h2>
                              <p class="text-gray-600 dark:text-gray-300 mb-6">
                                  Есть вопросы или предложения? Напишите нам на почту:
                              </p>
                              <a href="mailto:andreev.vstu@gmail.com" class="email-link inline-flex items-center text-lg text-blue-700 dark:text-blue-400 font-medium">
                                  <i class="bi bi-envelope-fill mr-3 text-2xl"></i>
                                  andreev.vstu@gmail.com
                                  <i class="bi bi-arrow-right-short ml-2 transition-transform duration-300"></i>
                              </a>
                              <p class="text-gray-700 dark:text-gray-400 text-sm mt-4">
                                  Мы ответим вам в течение 1-2 рабочих дней
                              </p>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </x-app-layout>

      <!-- Скрипт для анимаций -->
      <script>
          // Ждем полной загрузки страницы и библиотек
          document.addEventListener('DOMContentLoaded', function() {
              // Функция инициализации ScrollReveal
              function initScrollReveal() {
                  if (typeof ScrollReveal === 'function') {
                      ScrollReveal().reveal('.animate-on-scroll', {
                          delay: 200,
                          distance: '20px',
                          origin: 'bottom',
                          interval: 100,
                          reset: true
                      });
                  } else {
                      setTimeout(initScrollReveal, 100);
                  }
              }

              // Запускаем инициализацию
              initScrollReveal();

              // Анимация при загрузке
              const elements = document.querySelectorAll('.animate__animated');
              elements.forEach((el, index) => {
                  setTimeout(() => {
                      el.style.opacity = 1;
                  }, index * 100);
              });

              // Анимация для email ссылки
              const emailLink = document.querySelector('.email-link');
              if (emailLink) {
                  emailLink.addEventListener('mouseenter', function() {
                      const arrow = this.querySelector('.bi-arrow-right-short');
                      arrow.style.transform = 'translateX(5px)';
                  });
                  emailLink.addEventListener('mouseleave', function() {
                      const arrow = this.querySelector('.bi-arrow-right-short');
                      arrow.style.transform = 'translateX(0)';
                  });
              }

              // Анимация при наведении на блок контактов
              const contactBlock = document.querySelector('.contact-block');
              if (contactBlock) {
                  contactBlock.addEventListener('mouseenter', function() {
                      const envelope = this.querySelector('.envelope-icon');
                      envelope.style.animation = 'shake 0.5s ease-in-out';

                      setTimeout(() => {
                          envelope.style.animation = 'float 4s ease-in-out infinite';
                      }, 500);
                  });
              }
          });
      </script>
  </body>

  </html>