<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Профиль') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400 animate__animated animate__fadeIn">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>

    <x-slot name="title">
        {{ __('Профиль') }}
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-gray-300 dark:bg-gray-800 shadow rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-gray-300 dark:bg-gray-800 shadow rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <!-- НОВЫЙ БЛОК: Биометрическая аутентификация -->
            <div class="p-4 sm:p-8 bg-gray-300 dark:bg-gray-800 shadow rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.webauthn-keys-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-gray-300 dark:bg-gray-800 shadow rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>