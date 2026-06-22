<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component
{
    public string $password = '';

    public function deleteUser(Logout $logout): void
    {
        $this->validate([
            'password' => ['required', 'string', 'current_password'],
        ]);

        tap(Auth::user(), $logout(...))->delete();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center gap-3">
        <div class="p-2 bg-gradient-to-br from-red-500 to-pink-600 rounded-xl shadow-lg">
            <svg class="w-6 h-6 text-white"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
            </svg>
        </div>

        <div>
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                Удаление аккаунта
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Полное удаление аккаунта и всех данных
            </p>
        </div>
    </div>

    <!-- Warning card -->
    <div class="bg-gradient-to-r from-red-100 to-pink-100 
                border border-gray-900/20 
                dark:border-gray-600
                dark:from-red-950/30 
                dark:to-pink-950/30 
                rounded-xl p-4">

        <div class="flex items-center justify-between flex-wrap gap-4">

            <div class="flex items-center gap-3">
                <div class="shrink-0 w-10 h-10 bg-red-200 dark:bg-red-900/50 rounded-full flex items-center justify-center">
                    <svg 
                        class="w-5 h-5 text-red-600 dark:text-red-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v5m0 4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"
                        />
                    </svg>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        Это действие необратимо
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Все данные, связанные с вашим аккаунтом, будут удалены навсегда
                    </p>
                </div>
            </div>

            <button
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="group relative inline-flex items-center px-5 py-2.5
                       bg-gradient-to-r from-red-600 to-pink-600
                       hover:from-red-700 hover:to-pink-700
                       rounded-lg font-medium text-sm text-white
                       shadow-md hover:shadow-lg
                       transition-all duration-200
                       transform hover:-translate-y-0.5"
            >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform duration-200"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3M4 7h16"/>
                </svg>

                Удалить аккаунт
            </button>
        </div>
    </div>

    <!-- Modal -->
    <x-modal name="confirm-user-deletion"
             :show="$errors->isNotEmpty()"
             focusable>

        <form wire:submit="deleteUser" class="p-6">

            <div class="flex items-start gap-4 text-left">

    <!-- Icon -->
    <div class="shrink-0 w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
        <svg 
            class="w-6 h-6 text-red-600 dark:text-red-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M12 8v5m0 4h.01M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10z"
            />
        </svg>
    </div>

    <!-- Text -->
    <div>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white leading-snug">
            Подтвердите удаление
        </h2>

        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-md">
            Введите пароль для подтверждения удаления аккаунта
        </p>
    </div>

</div>

            <!-- Password -->
            <div class="mt-6">
                <x-input-label
                    for="password"
                    value="Пароль"
                    class="sr-only"
                />

                <x-text-input
                    wire:model="password"
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-xl"
                    placeholder="Введите пароль"
                />

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-2"
                />
            </div>

            <!-- Actions -->
            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button
                    x-on:click="$dispatch('close')"
                    class="rounded-lg"
                >
                    Отмена
                </x-secondary-button>

                <button
                    type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r 
                           from-red-600 to-pink-600
                           hover:from-red-700 hover:to-pink-700
                           rounded-lg text-white text-sm font-medium
                           transition-all duration-200"
                >
                    Удалить навсегда
                </button>
            </div>

        </form>
    </x-modal>
</div>