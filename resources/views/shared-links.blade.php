@php
    if (!function_exists('pluralForm')) {
        function pluralForm($number, $one, $two, $many) {
            $number = abs($number);
            $remainder10 = $number % 10;
            $remainder100 = $number % 100;
            
            if ($remainder100 >= 11 && $remainder100 <= 19) return $many;
            if ($remainder10 == 1) return $one;
            if ($remainder10 >= 2 && $remainder10 <= 4) return $two;
            return $many;
        }
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Мои публичные ссылки') }}
            </h2>
            <div class="text-sm text-gray-800 dark:text-gray-400">
                {{ now()->translatedFormat('d F Y') }}
            </div>
        </div>
    </x-slot>
    
    <x-slot name="title">
        {{ __('Мои публичные ссылки') }}
    </x-slot>

    <div class="py-6 px-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Статистика -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-4 text-white shadow-lg">
                    <div class="text-3xl font-bold">{{ $stats['total'] ?? 0 }}</div>
                    <div class="text-sm opacity-90">Всего ссылок</div>
                </div>
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 text-white shadow-lg">
                    <div class="text-3xl font-bold">{{ $stats['active'] ?? 0 }}</div>
                    <div class="text-sm opacity-90">Активных</div>
                </div>
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-lg">
                    <div class="text-3xl font-bold">{{ number_format($stats['total_views'] ?? 0) }}</div>
                    <div class="text-sm opacity-90">Всего просмотров</div>
                </div>
            </div>
            
            <!-- Таблица ссылок -->
            <div class="bg-gray-300 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 border-b border-gray-400 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Список ссылок</h3>
                </div>
                
                @if(isset($shareLinks) && count($shareLinks) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-400 dark:divide-gray-800">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Название</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Тип</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Просмотры</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Статус</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Срок действия</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Создана</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-700 dark:text-gray-300 uppercase">Действия</th>
                            <tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($shareLinks as $link)
                            <tr class="hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-150">
                                <td class="px-6 py-4 text-left">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $link->display_title }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-700 dark:text-gray-400">
                                    @php
                                        $type = class_basename($link->calculable_type);
                                        echo $type == 'Nd3CalculationHistory' ? 'Nd³⁺' : ($type == 'Ho3CalculationHistory' ? 'Ho³⁺' : '-');
                                    @endphp
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ number_format($link->views) }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($link->is_active && (!$link->expires_at || $link->expires_at > now()))
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-200 text-green-900 dark:bg-green-900 dark:text-green-300">Активна</span>
                                    @elseif(!$link->is_active)
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-200 text-red-900 dark:bg-red-900 dark:text-red-300">Отключена</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-200 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-300">Истекла</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-700 dark:text-gray-400">
                                    @if($link->expires_at)
                                        {{ $link->expires_at->format('d.m.Y H:i') }}
                                        @php
                                            $now = now();
                                            $expires = \Carbon\Carbon::parse($link->expires_at);
                                            $isPast = $expires->isPast();
                                            
                                            // Рассчитываем общее количество дней правильно
                                            $totalDays = $expires->diffInDays($now, false);
                                            $totalHours = $expires->diffInHours($now, false);
                                            $totalMinutes = $expires->diffInMinutes($now, false);
                                            
                                            $days = floor(abs($totalDays));
                                            $hours = abs($totalHours) % 24;
                                            $minutes = abs($totalMinutes) % 60;
                                            
                                            $parts = [];
                                            if ($days > 0) $parts[] = $days . ' ' . pluralForm($days, 'день', 'дня', 'дней');
                                            if ($hours > 0) $parts[] = $hours . ' ' . pluralForm($hours, 'час', 'часа', 'часов');
                                            if ($minutes > 0 || ($days == 0 && $hours == 0)) $parts[] = $minutes . ' ' . pluralForm($minutes, 'минута', 'минуты', 'минут');
                                            
                                            $timeText = implode(' ', $parts);
                                            
                                            if ($isPast) {
                                                echo '<span class="text-red-700 dark:text-red-400 block text-xs">просрочена на ' . $timeText . '</span>';
                                            } else {
                                                // Всегда показываем остаток, с подсветкой если <= 7 дней
                                                $remainingClass = $days <= 7 ? 'text-yellow-700 dark:text-yellow-500' : 'text-gray-700 dark:text-gray-400';
                                                echo '<span class="' . $remainingClass . ' block text-xs">осталось ' . $timeText . '</span>';
                                            }
                                        @endphp
                                    @else
                                        <span class="text-green-700 dark:text-green-400">Бессрочно</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center text-sm text-gray-700 dark:text-gray-400">
                                    {{ $link->created_at->format('d.m.Y H:i') }}
                                </td>
<td class="px-6 py-4 text-center">
    <div class="grid grid-cols-2 gap-1 sm:flex sm:items-center sm:justify-center sm:gap-2 sm:flex-wrap">
        <!-- Копировать ссылку -->
        <button onclick="copyToClipboard('{{ $link->url }}')" 
                class="p-1.5 text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
                title="Копировать ссылку">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
            </svg>
        </button>
        
        <!-- QR-код -->
        <button onclick="showQRCode('{{ $link->url }}', '{{ addslashes($link->display_title) }}')" 
                class="p-1.5 text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
                title="Показать QR-код">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
        </button>
        
        <!-- Редактировать -->
        <button onclick="editShareLink({{ $link->id }}, '{{ addslashes($link->title) }}', '{{ addslashes($link->description) }}', '{{ $link->expires_at }}', {{ $link->allow_copy_to_account ? 'true' : 'false' }}, {{ $link->password_hash ? 'true' : 'false' }})" 
                class="p-1.5 text-gray-700 hover:text-yellow-600 dark:text-gray-400 dark:hover:text-yellow-400 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
                title="Редактировать ссылку">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
            </svg>
        </button>
        
        <!-- Просмотр -->
        <a href="{{ route('public.calculation.show', $link->token) }}" target="_blank" 
           class="p-1.5 text-gray-700 hover:text-indigo-600 dark:text-gray-400 dark:hover:text-indigo-400 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
           title="Просмотреть расчёт">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
            </svg>
        </a>
        
        <!-- Статистика -->
        <a href="{{ route('public.calculation.stats', $link->token) }}" 
           class="p-1.5 text-gray-700 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-400 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
           title="Статистика ссылки">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </a>
        
        <!-- Удалить -->
        <button onclick="deleteLink({{ $link->id }}, '{{ addslashes($link->display_title) }}')" 
                class="p-1.5 text-gray-700 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-700"
                title="Удалить ссылку">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
        </button>
    </div>
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4">
                    <div class="flex justify-center">
                        {{ $shareLinks->links() }}
                    </div>
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m1.102-4.486a4 4 0 00-5.656 0l-1.102 1.102"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Нет ссылок</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">У вас ещё нет созданных публичных ссылок.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Модальное окно для QR-кода -->
    <div id="qrModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80 backdrop-blur-sm"></div>
        <div class="relative flex items-center justify-center h-full p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">QR-код</h3>
                    <button onclick="closeQRModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 transition-colors">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex justify-center mb-4">
                    <div id="qrCodeContainer" class="bg-white p-4 rounded-lg"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center break-all" id="qrUrl"></p>
                <div class="flex gap-3 mt-4">
                    <button onclick="downloadQRCode()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Скачать
                    </button>
                    <button onclick="closeQRModal()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        let currentQRCode = null;
        let currentQRUrl = '';
        
        // ==========================================
        // ВСПОМОГАТЕЛЬНЫЕ ФУНКЦИИ
        // ==========================================
        
        function escapeHtml(text) {
            if (!text) return '';
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-4 py-2 rounded-lg shadow-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }
        
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            showToast('Ссылка скопирована!');
        }
        
        // ==========================================
        // QR-КОД
        // ==========================================
        
        function showQRCode(url, title) {
            currentQRUrl = url;
            document.getElementById('qrUrl').textContent = url;
            
            const modal = document.getElementById('qrModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
            
            const container = document.getElementById('qrCodeContainer');
            container.innerHTML = '';
            
            const wrapper = document.createElement('div');
            wrapper.className = 'bg-white p-6 rounded-xl shadow-sm inline-block';
            container.appendChild(wrapper);
            
            currentQRCode = new QRCode(wrapper, {
                text: url,
                width: 200,
                height: 200,
                colorDark: '#000000',
                colorLight: '#ffffff',
                correctLevel: QRCode.CorrectLevel.H
            });
        }
        
        function closeQRModal() {
            const modal = document.getElementById('qrModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex', 'items-center', 'justify-center');
        }
        
        function downloadQRCode() {
            const container = document.getElementById('qrCodeContainer');
            const canvas = container.querySelector('canvas');
            
            if (canvas) {
                try {
                    const originalCanvas = canvas;
                    const padding = 30;
                    
                    const tempCanvas = document.createElement('canvas');
                    const ctx = tempCanvas.getContext('2d');
                    
                    tempCanvas.width = originalCanvas.width + (padding * 2);
                    tempCanvas.height = originalCanvas.height + (padding * 2);
                    
                    ctx.fillStyle = '#ffffff';
                    ctx.fillRect(0, 0, tempCanvas.width, tempCanvas.height);
                    ctx.drawImage(originalCanvas, padding, padding, originalCanvas.width, originalCanvas.height);
                    
                    const link = document.createElement('a');
                    link.download = 'qrcode.png';
                    link.href = tempCanvas.toDataURL('image/png');
                    link.click();
                    
                    showToast('QR-код сохранён');
                } catch (error) {
                    showToast('Ошибка при сохранении QR-кода', 'error');
                }
            } else {
                showToast('QR-код ещё не сгенерирован', 'error');
            }
        }
        
        // ==========================================
        // РЕДАКТИРОВАНИЕ ССЫЛКИ
        // ==========================================
        
        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input) {
                input.type = input.type === 'password' ? 'text' : 'password';
            }
        }
        
        function closeEditModal() {
            const modal = document.getElementById('edit-link-modal');
            if (modal) {
                modal.remove();
            }
        }
        
        function editShareLink(linkId, currentTitle, currentDescription, currentExpiresAt, currentAllowCopy, hasPassword) {
            const existingModal = document.getElementById('edit-link-modal');
            if (existingModal) {
                existingModal.remove();
            }
            
            let expiresValue = '';
            if (currentExpiresAt && currentExpiresAt !== 'null') {
                try {
                    const expiresDate = new Date(currentExpiresAt);
                    if (!isNaN(expiresDate.getTime())) {
                        const now = new Date();
                        const diffDays = Math.ceil((expiresDate - now) / (1000 * 60 * 60 * 24));
                        if (diffDays === 7) expiresValue = '7';
                        else if (diffDays === 30) expiresValue = '30';
                        else if (diffDays === 90) expiresValue = '90';
                        else if (diffDays === 365) expiresValue = '365';
                    }
                } catch(e) {}
            }
            
            const modal = document.createElement('div');
            modal.id = 'edit-link-modal';
            modal.className = 'fixed inset-0 z-50 hidden';
            modal.innerHTML = `
                <div class="absolute inset-0 bg-gray-900/70 dark:bg-gray-900/80 backdrop-blur-sm"></div>
                <div class="relative flex items-center justify-center h-full p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 max-w-md w-full mx-auto max-h-[90vh] overflow-y-auto">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Редактировать ссылку</h3>
                            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <form id="edit-link-form" autocomplete="off">
                            <input type="hidden" id="edit-link-id" value="${linkId}">
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Заголовок</label>
                                <input type="text" id="edit-link-title" value="${escapeHtml(currentTitle || '')}" 
                                    autocomplete="off"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    placeholder="Введите заголовок">
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Описание</label>
                                <textarea id="edit-link-description" rows="3" autocomplete="off"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        placeholder="Введите описание">${escapeHtml(currentDescription || '')}</textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Срок действия</label>
                                <select id="edit-link-expires" autocomplete="off"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="7" ${expiresValue === '7' ? 'selected' : ''}>7 дней</option>
                                    <option value="30" ${expiresValue === '30' ? 'selected' : ''}>30 дней</option>
                                    <option value="90" ${expiresValue === '90' ? 'selected' : ''}>90 дней</option>
                                    <option value="365" ${expiresValue === '365' ? 'selected' : ''}>1 год</option>
                                    <option value="" ${expiresValue === '' ? 'selected' : ''}>Бессрочно</option>
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Пароль</label>
                                <div class="relative">
                                    <input type="password" id="edit-link-password" 
                                        autocomplete="new-password"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white pr-10"
                                        placeholder="Оставьте пустым для общего доступа">
                                    <button type="button" 
                                            onclick="togglePasswordVisibility('edit-link-password')"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div class="mt-2 p-2 rounded-md ${hasPassword ? 'bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700' : 'bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600'}">
                                    <div class="flex items-center gap-2">
                                        <svg class="h-4 w-4 ${hasPassword ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            ${hasPassword ? 
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>' :
                                                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>'
                                            }
                                        </svg>
                                        <span class="text-sm ${hasPassword ? 'text-green-700 dark:text-green-300' : 'text-gray-600 dark:text-gray-400'}">
                                            ${hasPassword ? '🔒 Ссылка защищена паролем' : '🔓 Парольная защита не установлена'}
                                        </span>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ml-6">
                                        ${hasPassword ? 
                                            'Оставьте поле пустым, чтобы сохранить текущий пароль. Введите новый пароль для его изменения.' : 
                                            'Введите пароль для защиты ссылки, или оставьте поле пустым.'}
                                    </p>
                                    ${hasPassword ? `
                                    <div class="mt-2 ml-6">
                                        <button type="button" 
                                                onclick="removePassword()"
                                                class="text-xs text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 underline">
                                            ✕ Удалить парольную защиту
                                        </button>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" id="edit-link-allow-copy" ${currentAllowCopy ? 'checked' : ''}
                                        autocomplete="off"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Разрешить копирование в аккаунт</span>
                                </label>
                            </div>
                            
                            <div class="flex gap-3">
                                <button type="submit" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                    Сохранить изменения
                                </button>
                                <button type="button" onclick="closeEditModal()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors duration-150">
                                    Отмена
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'items-center', 'justify-center');
            
            window.removePassword = function() {
                if (confirm('Вы уверены, что хотите удалить парольную защиту? Ссылка станет публично доступной.')) {
                    const passwordInput = document.getElementById('edit-link-password');
                    passwordInput.value = 'DELETE_PASSWORD';
                    passwordInput.placeholder = 'Пароль будет удален';
                    passwordInput.classList.add('bg-red-50', 'dark:bg-red-900/20', 'border-red-300', 'dark:border-red-700');
                    
                    const infoDiv = passwordInput.closest('.mb-4').querySelector('.bg-green-100, .bg-gray-100');
                    if (infoDiv) {
                        infoDiv.innerHTML = `
                            <div class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                </svg>
                                <span class="text-sm text-red-700 dark:text-red-300">⚠️ Пароль будет удален при сохранении</span>
                            </div>
                        `;
                    }
                }
            };
            
            document.getElementById('edit-link-form').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const linkId = document.getElementById('edit-link-id').value;
                const title = document.getElementById('edit-link-title').value;
                const description = document.getElementById('edit-link-description').value;
                const expiresInDays = document.getElementById('edit-link-expires').value;
                let password = document.getElementById('edit-link-password').value;
                const allowCopy = document.getElementById('edit-link-allow-copy').checked;
                
                if (password === 'DELETE_PASSWORD') {
                    password = '';
                }
                
                const formData = {
                    title: title || null,
                    description: description || null,
                    expires_in_days: expiresInDays ? parseInt(expiresInDays) : null,
                    password: password || null,
                    allow_copy_to_account: allowCopy
                };
                
                const submitBtn = e.target.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Сохранение...';
                submitBtn.disabled = true;
                
                try {
                    const response = await fetch(`/share-links/${linkId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        showToast('Ссылка успешно обновлена!', 'success');
                        closeEditModal();
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast(data.error || 'Ошибка обновления ссылки', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showToast('Ошибка сети при обновлении ссылки', 'error');
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            });
        }
        
        // ==========================================
        // УДАЛЕНИЕ ССЫЛКИ
        // ==========================================
        
        function deleteLink(id, title) {
            if (confirm(`Вы уверены, что хотите удалить ссылку "${title}"? Это действие нельзя отменить.`)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/share-links/${id}`;
                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }
        
        // ==========================================
        // ЗАКРЫТИЕ МОДАЛЬНЫХ ОКОН ПО ESCAPE
        // ==========================================
        
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeQRModal();
                closeEditModal();
            }
        });
    </script>
</x-app-layout>