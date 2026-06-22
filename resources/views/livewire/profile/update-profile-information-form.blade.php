<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;
use Livewire\WithFileUploads;

new class extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $code = '';
    public $avatar;
    public bool $avatarModal = false;

    public function mount(): void
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;

        // Инициализация аватара
        $avatarDir = "avatars/{$user->id}";
        if (!Storage::exists($avatarDir)) {
            $this->storeSvgAvatar($user->name);
        }
    }

    private function generateSvgAvatar(string $initials, int $size = 128): string
    {
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$size}" height="{$size}">
    <rect width="100%" height="100%" fill="#4f46e5" />
    <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" font-size="48" fill="white" font-family="Arial, sans-serif">
        {$initials}
    </text>
</svg>
SVG;

        return $svg;
    }

    private function generateInitials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name));

        if (count($parts) >= 2) {
            return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1));
        }

        return strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[0], 1, 1));
    }

    private function storeSvgAvatar(string $name): void
    {
        $initials = $this->generateInitials($name);
        $svg = $this->generateSvgAvatar($initials);
        $user = Auth::user();
        $avatarDir = "avatars/{$user->id}";

        Storage::makeDirectory($avatarDir);
        Storage::put("{$avatarDir}/avatar.svg", $svg);
    }

    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'max:10240'],
        ]);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $avatarDir = "avatars/{$user->id}";

        // Создаем директорию если не существует
        if (!Storage::exists($avatarDir)) {
            Storage::makeDirectory($avatarDir);
        }

        // Обработка загрузки нового аватара
        if ($this->avatar) {
            // Удаляем старые аватары
            foreach (Storage::files($avatarDir) as $file) {
                if (str_starts_with(basename($file), 'avatar.')) {
                    Storage::delete($file);
                }
            }

            // Сохраняем новый аватар
            $ext = $this->avatar->getClientOriginalExtension();
            $filename = "avatar.{$ext}";
            Storage::putFileAs($avatarDir, $this->avatar, $filename);
            $this->avatar = null;
        }
        // Если аватар не загружали и нет пользовательского аватара - генерируем SVG
        elseif (!collect(Storage::files($avatarDir))->contains(fn($f) => preg_match('/avatar\.(?!svg)/', basename($f)))) {
            $this->storeSvgAvatar($user->name);
        }

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function deleteAvatar(): void
    {
        $user = Auth::user();
        $avatarDir = "avatars/{$user->id}";

        foreach (Storage::files($avatarDir) as $file) {
            if (str_starts_with(basename($file), 'avatar.')) {
                Storage::delete($file);
            }
        }

        $this->storeSvgAvatar($user->name);

        // Обновляем данные компонента
        $this->name = $user->name;
        $this->email = $user->email;

        $this->dispatch('profile-updated', name: $user->name);
    }

    public function sendVerification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));
            return;
        }

        $code = random_int(100000, 999999);
        Cache::put('email_verification:' . $user->email, [
            'code' => $code,
            'user_id' => $user->id,
        ], now()->addMinutes(10));

        $this->code = '';
        $user->sendEmailVerificationNotification();
        Session::flash('status', 'verification-link-sent');
    }

    public function verifyEmailCode(): void
    {
        $this->validate(['code' => ['required', 'numeric', 'digits:6']]);
        $user = Auth::user();
        $cached = Cache::get('email_verification:' . $user->email);

        if (!$cached || $cached['code'] != $this->code || $cached['user_id'] != $user->id) {
            $this->addError('code', 'Неверный код подтверждения.');
            return;
        }

        $user->markEmailAsVerified();
        Cache::forget('email_verification:' . $user->email);

        Session::flash('status', 'email-verified');
    }
};
?>

<section class="space-y-6">
    {{-- Заголовок с иконкой --}}
    <div class="flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                {{ __('Профиль') }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __("Здесь вы можете обновить свои данные.") }}
            </p>
        </div>
    </div>
    

    {{-- Основная форма --}}
    <div class="bg-gradient-to-r from-gray-200 to-gray-300 dark:from-gray-900/50 dark:to-gray-800/50 border border-gray-900/20 dark:border-gray-600 rounded-xl p-4 sm:p-6">
        @php
        $userId = auth()->id();
        $files = collect(Storage::files("avatars/{$userId}"));
        $customAvatar = $files->first(fn($f) => !str_ends_with($f, '.svg'));
        $avatarName = $customAvatar ? basename($customAvatar) : 'avatar.svg';
        $avatarHash = $customAvatar ? md5_file(Storage::path("avatars/{$userId}/{$avatarName}")) : md5($userId);
        $avatarUrl = route('avatar.serve', ['user' => $userId, 'filename' => $avatarName]);
        @endphp

           {{-- Модальное окно для аватара --}}
    @if ($avatarModal)
        <div
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-60 px-4 sm:px-2"
            x-data
            x-on:click.self="$wire.set('avatarModal', false)">
            <div
                class="relative bg-gray-300 dark:bg-gray-900 rounded-xl shadow-xl p-6 w-full max-w-md mx-auto space-y-5 animate-fade-in"
                @click.stop>

            <div class="flex items-center gap-3">
                <div class="p-2 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Аватарка</h2>
            </div>

            {{-- Кнопка закрытия --}}
            <button
                wire:click="$set('avatarModal', false)"
                class="absolute top-4 right-4 text-red-400 hover:text-red-500 dark:hover:text-red-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </button>

            {{-- Загрузка файла --}}
            <form action="{{ route('avatar.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300" for="avatar">
                    Загрузить аватарку:
                </label>
<div
    id="dropZone"
    class="relative w-full border-2 border-dashed border-gray-400 dark:border-gray-600 rounded-lg p-4 text-center cursor-pointer hover:border-blue-600 dark:hover:border-blue-500 transition"
    ondragover="handleDragOver(event)"
    ondragleave="handleDragLeave(event)"
    ondrop="handleDrop(event)"
    onclick="document.getElementById('avatar').click()">

    <div id="previewContainer" class="hidden flex flex-col items-center">
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Предпросмотр аватарки:</p>
        <img id="avatarPreview" class="w-24 h-24 rounded-full object-cover shadow border border-gray-300 dark:border-gray-600" />
    </div>
    
    <div id="uploadPlaceholder" class="flex flex-col items-center">
        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <p class="text-gray-700 dark:text-gray-300">Перетащите изображение сюда или нажмите для выбора</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Допустимые форматы: JPG, PNG, GIF</p>
    </div>
    
    <input
        type="file"
        name="avatar"
        id="avatar"
        accept="image/jpeg,image/png,image/gif"
        required
        onchange="validateAndPreviewAvatar(event)"
        class="hidden" />
</div>

                <div id="fileError" class="text-sm text-red-600 dark:text-red-400 hidden"></div>

                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Размер изображения не более 10 МБ.<br>
                    Допустимые форматы: JPG, PNG, GIF.
                </p>

                <button type="submit"
                    id="uploadButton"
                    disabled
                    class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-gradient-to-r dark:from-green-500 dark:to-lime-500 dark:hover:from-green-600 dark:hover:to-lime-600 from-green-600 to-lime-600 hover:from-green-700 hover:to-lime-700 rounded-lg font-medium text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Загрузить аватарку
                </button>
            </form>

            {{-- Кнопки --}}
            @if ($customAvatar)
            <div class="pt-2">
                <button
                    wire:click="deleteAvatar"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-70 cursor-not-allowed"
                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-600 hover:bg-red-700 rounded-lg font-medium text-sm text-white transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Удалить аватарку
                </button>
            </div>
            @else
            <p class="text-sm text-amber-700 dark:text-amber-400 bg-amber-100 dark:bg-amber-950/30 p-3 rounded-lg">
                Сейчас используется аватарка по умолчанию
            </p>
            @endif
        </div>
    </div>
    @endif

        <div class="flex items-center gap-6 mb-6">
            {{-- Аватар с иконкой "+" --}}
            <div class="relative">
                <img
                    src="{{ $avatarUrl }}?v={{ now()->timestamp }}"
                    alt="Аватарка"
                    class="w-24 h-24 rounded-full object-cover border border-gray-400 dark:border-gray-500 shadow bg-gray-300 dark:bg-gray-700 opacity-0 transition-opacity duration-700"
                    onload="this.classList.remove('opacity-0')">

                <button wire:click="$set('avatarModal', true)"
                    class="absolute bottom-0 right-0 bg-gradient-to-r from-gray-700 to-gray-600 hover:from-gray-800 hover:to-gray-700 text-white rounded-full p-1.5 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                    </svg>
                </button>
            </div>

            {{-- Имя и email --}}
            <div>
                <div class="text-xl font-semibold text-gray-900 dark:text-white">
                    {{ $name }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $email }}
                </div>
            </div>
        </div>

        <form wire:submit="updateProfileInformation" class="space-y-6">
            <div>
                <x-input-label for="name" :value="__('Имя')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                <x-text-input
                    wire:model="name"
                    id="name"
                    name="name"
                    type="text"
                    maxlength="30"
                    placeholder="Ваше имя"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    required
                    autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                <x-text-input 
                    wire:model="email" 
                    id="email" 
                    name="email" 
                    type="email" 
                    placeholder="your@mail.com"
                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                    required 
                    autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if (session('status') === 'email-verified')
                <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                    {{ __('Email успешно подтверждён.') }}
                </p>
                @endif
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit"
                    class="group relative inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 rounded-lg font-medium text-sm text-white shadow-md hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                    <svg class="w-4 h-4 mr-2 transition-transform duration-200 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Сохранить') }}
                </button>

                <x-action-message class="text-green-600 dark:text-green-400" on="profile-updated">
                    {{ __('Сохранено') }}
                </x-action-message>
            </div>
        </form>

        {{-- Неподтверждённый email --}}
        @if (auth()->user() instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !auth()->user()->hasVerifiedEmail())
        <div class="mt-6 p-4 bg-gradient-to-r from-red-50 to-orange-50 dark:from-red-950/30 dark:to-orange-950/30 border border-red-900/20 dark:border-red-600 rounded-xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-1.5 bg-red-500 rounded-lg">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        Email не подтверждён
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Ваша почта: <span class="font-semibold">{{ auth()->user()->email }}</span>
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <button
                    wire:click.prevent="sendVerification"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium text-white transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Отправить письмо с подтверждением
                </button>

                @if (session('status') === 'verification-link-sent')
                <p class="text-sm font-medium text-green-600 dark:text-green-400">
                    {{ __('На Ваш Email отправлено письмо с подтверждением.') }}
                </p>
                @endif

                <form wire:submit.prevent="verifyEmailCode" class="flex flex-col sm:flex-row gap-3 items-end">
                    <div class="flex-1">
                        <x-input-label for="code" :value="__('Код подтверждения')" class="text-sm font-medium text-gray-700 dark:text-gray-300" />
                        <x-text-input
                            wire:model="code"
                            id="code"
                            name="code"
                            type="text"
                            maxlength="6"
                            class="mt-1 block w-full text-center tracking-[0.5em] font-mono rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            placeholder="••••••"
                            inputmode="numeric"
                            pattern="[0-9]*"
                            required />
                        <x-input-error :messages="$errors->get('code')" class="mt-1" />
                    </div>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 hover:from-blue-700 hover:to-cyan-700 rounded-lg font-medium text-sm text-white shadow-md hover:shadow-lg transition-all duration-200">
                        Подтвердить
                    </button>
                </form>

                @if (session('status') === 'email-verified')
                <p class="text-sm font-medium text-green-600 dark:text-green-400">
                    {{ __('Email успешно подтверждён.') }}
                </p>
                @endif
            </div>
        </div>
        @endif
    </div>
</section>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Проверяем существование элементов перед выполнением операций
        const avatarPreview = document.getElementById('avatarPreview');
        const uploadButton = document.getElementById('uploadButton');
        const fileError = document.getElementById('fileError');
        const dropZone = document.getElementById('dropZone');
        const avatarInput = document.getElementById('avatar');

        if (avatarPreview && uploadButton && fileError && dropZone && avatarInput) {
            loadAvatarFromStorage();
            setupEventListeners();
        }
    });

    function loadAvatarFromStorage() {
        const savedAvatar = localStorage.getItem('userAvatar');
        if (savedAvatar) {
            const preview = document.getElementById('avatarPreview');
            const uploadButton = document.getElementById('uploadButton');

            if (preview && uploadButton) {
                preview.src = savedAvatar;
                preview.classList.remove('hidden');
                uploadButton.disabled = false;
            }
        }
    }

    function setupEventListeners() {
        const dropZone = document.getElementById('dropZone');
        const avatarInput = document.getElementById('avatar');

        if (dropZone && avatarInput) {
            dropZone.addEventListener('dragover', handleDragOver);
            dropZone.addEventListener('dragleave', handleDragLeave);
            dropZone.addEventListener('drop', handleDrop);
            dropZone.addEventListener('click', () => avatarInput.click());
        }
    }

    function validateAndPreviewAvatar(event) {
        const input = event.target;
        const preview = document.getElementById('avatarPreview');
        const uploadButton = document.getElementById('uploadButton');
        const fileError = document.getElementById('fileError');

        resetFileInputState(preview, uploadButton, fileError);

        if (!input.files || !input.files[0]) return;

        const file = input.files[0];
        if (!validateFile(file, fileError)) {
            resetFileInput(input, preview, uploadButton);
            return;
        }

        processImageFile(file, preview, uploadButton, fileError);
    }

    function validateFile(file, errorElement) {
        const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        const validExtensions = ['.jpg', '.jpeg', '.png', '.gif'];
        const maxSize = 10 * 1024 * 1024; // 10MB
        
        // Проверка MIME типа
        if (!validTypes.includes(file.type)) {
            showError(errorElement, 'Недопустимый тип файла. Разрешены только JPG, PNG, GIF.');
            return false;
        }
        
        // Проверка расширения файла
        const fileName = file.name.toLowerCase();
        const hasValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
        
        if (!hasValidExtension) {
            showError(errorElement, 'Недопустимое расширение файла. Разрешены только JPG, PNG, GIF.');
            return false;
        }

        if (file.size > maxSize) {
            showError(errorElement, 'Файл слишком большой. Максимальный размер: 10 МБ.');
            return false;
        }

        return true;
    }

    function processImageFile(file, previewElement, buttonElement, errorElement) {
        const reader = new FileReader();

        reader.onload = (e) => {
            const imageDataUrl = e.target.result;
            displayImagePreview(imageDataUrl, previewElement, buttonElement);
            storeAvatarInLocalStorage(imageDataUrl, file.name);
        };

        reader.onerror = () => {
            showError(errorElement, 'Ошибка чтения файла');
            buttonElement.disabled = true;
        };

        reader.readAsDataURL(file);
    }

    function displayImagePreview(imageData, previewElement, buttonElement) {
        previewElement.src = imageData;
        
        // Показываем контейнер с предпросмотром
        const previewContainer = document.getElementById('previewContainer');
        const placeholder = document.getElementById('uploadPlaceholder');
        
        if (previewContainer) {
            previewContainer.classList.remove('hidden');
        }
        if (placeholder) {
            placeholder.classList.add('hidden');
        }
        
        buttonElement.disabled = false;
    }

    function storeAvatarInLocalStorage(imageData, fileName) {
        try {
            if (imageData.length > 5000000) {
                console.warn('Изображение слишком большое для LocalStorage');
                return;
            }

            localStorage.setItem('userAvatar', imageData);
            localStorage.setItem('avatarLastUpdated', Date.now());
            localStorage.setItem('avatarOriginalName', fileName);
        } catch (error) {
            console.error('Ошибка сохранения в LocalStorage:', error);
        }
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.currentTarget.classList.add('border-indigo-600', 'bg-indigo-50', 'dark:bg-indigo-900/20');
    }

    function handleDragLeave(event) {
        event.preventDefault();
        event.currentTarget.classList.remove('border-indigo-600', 'bg-indigo-50', 'dark:bg-indigo-900/20');
    }

    function handleDrop(event) {
        event.preventDefault();
        const dropZone = event.currentTarget;
        dropZone.classList.remove('border-indigo-600', 'bg-indigo-50', 'dark:bg-indigo-900/20');

        const fileError = document.getElementById('fileError');
        const files = event.dataTransfer.files;

        if (!files || files.length === 0) return;

        const file = files[0];
        if (!validateFile(file, fileError)) return;

        const input = document.getElementById('avatar');
        input.files = files;
        validateAndPreviewAvatar({
            target: input
        });
    }

    function resetFileInputState(preview, button, errorElement) {
        errorElement.classList.add('hidden');
        errorElement.textContent = '';
        preview.src = '';
        
        // Скрываем предпросмотр и показываем плейсхолдер
        const previewContainer = document.getElementById('previewContainer');
        const placeholder = document.getElementById('uploadPlaceholder');
        
        if (previewContainer) {
            previewContainer.classList.add('hidden');
        }
        if (placeholder) {
            placeholder.classList.remove('hidden');
        }
        
        button.disabled = true;
    }

    function showError(element, message) {
        element.textContent = message;
        element.classList.remove('hidden');
    }

    function resetFileInput(input, preview, button) {
        input.value = '';
        preview.src = '';
        preview.classList.add('hidden');
        button.disabled = true;
    }
</script>