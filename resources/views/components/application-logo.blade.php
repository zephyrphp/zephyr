<a class="flex items-center" href="/" title="{{ __('Home') }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
         stroke-width="2" stroke-linecap="round" stroke-linejoin="round" {{ $attributes }}>
        <path d="M12.8 19.6A2 2 0 1 0 14 16H2"/>
        <path d="M17.5 8a2.5 2.5 0 1 1 2 4H2"/>
        <path d="M9.8 4.4A2 2 0 1 1 11 8H2"/>
    </svg>

    <span class="text-xl font-bold leading-5 tracking-tight text-gray-950 ms-4 dark:text-white">{{ config('app.name') }}</span>
</a>
