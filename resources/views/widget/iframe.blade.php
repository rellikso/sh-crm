<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="api-base-url" content="{{ $apiBase }}">
    <title>{{ __('tickets.page_title') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased p-4">

<div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-6">
    <div class="flex-1 bg-white p-5 rounded-xl shadow-sm border border-gray-100 h-fit">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('tickets.pulse_title') }}</h3>
        <div id="stats-loader" class="text-sm text-gray-500 animate-pulse">{{ __('tickets.loading_metrics') }}</div>

        <div id="stats-content" class="hidden space-y-4">
            <div class="border-l-4 border-blue-500 pl-3 py-1" id="period-day">
                <h4 class="text-sm font-semibold text-gray-700">{{ __('tickets.period_day') }}</h4>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ __('tickets.meta_total') }}: <span class="total font-semibold text-gray-900">0</span> |
                    {{ __('tickets.meta_new') }}: <span class="new font-semibold text-orange-500">0</span>
                </p>
            </div>
            <div class="border-l-4 border-indigo-500 pl-3 py-1" id="period-week">
                <h4 class="text-sm font-semibold text-gray-700">{{ __('tickets.period_week') }}</h4>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ __('tickets.meta_total') }}: <span class="total font-semibold text-gray-900">0</span> |
                    {{ __('tickets.meta_new') }}: <span class="new font-semibold text-orange-500">0</span>
                </p>
            </div>
            <div class="border-l-4 border-purple-500 pl-3 py-1" id="period-month">
                <h4 class="text-sm font-semibold text-gray-700">{{ __('tickets.period_month') }}</h4>
                <p class="text-xs text-gray-500 mt-0.5">
                    {{ __('tickets.meta_total') }}: <span class="total font-semibold text-gray-900">0</span> |
                    {{ __('tickets.meta_new') }}: <span class="new font-semibold text-orange-500">0</span>
                </p>
            </div>
        </div>
    </div>

    <div class="flex-[2] bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ __('tickets.submit_title') }}</h3>
        <form id="ticket-form" class="space-y-3">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <input type="text" name="name" placeholder="{{ __('tickets.name_placeholder') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
                <input type="email" name="email" placeholder="{{ __('tickets.email_placeholder') }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            </div>
            <input type="text" name="phone" placeholder="{{ __('tickets.phone_placeholder') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <input type="text" name="subject" placeholder="{{ __('tickets.subject_placeholder') }}" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm">
            <textarea name="message" rows="4" placeholder="{{ __('tickets.message_placeholder') }}" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm"></textarea>
            <input type="file" name="attachments[]" multiple
                   class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

            <button type="submit" id="submit-btn" data-sending-text="{{ __('tickets.sending') }}"
                    class="w-full sm:w-auto px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50">
                {{ __('tickets.submit_btn') }}
            </button>
        </form>
        <p id="form-message" class="text-sm mt-3" data-success-text="{{ __('tickets.success_message') }}"></p>
    </div>
</div>

</body>
</html>