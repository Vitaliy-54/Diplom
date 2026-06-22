<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Программы') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Программы') }}
    </x-slot>

    <!-- Main Content -->
    <div class="py-6 bg-gray-200">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mb-5">
            <div class="p-3 p-lg-4 bg-light rounded-3 text-center">
                <div class="flex justify-center bg-white">
                    <iframe
                        src="{{ $embeddedLink }}"
                        frameborder="0"
                        width="100%"
                        height="600"
                        class="max-w-full rounded-lg shadow-lg border border-gray-500"></iframe>
                </div>
                <p class="text-sm text-muted mt-4">Альтернативная ссылка: <a
                        href="{{ $googleDriveLink }}"
                        target="_blank" class="text-blue-500 hover:text-blue-700 font-bold">перейти</a></p>
            </div>
        </div>
    </div>
</x-app-layout>