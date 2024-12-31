<footer class="mx-auto my-4 flex w-full flex-wrap items-center justify-center px-4 text-sm text-gray-500 fi-footer dark:text-gray-400 md:px-6 lg:px-8">
    <span>© {{ now()->format('Y') }} {{ config('app.name') }}</span>
    <nav class="flex gap-x-6 ms-auto sm:gap-x-8">
        <a class="hover:underline" href="/terms-of-service">
            {{ __('Terms of Service') }}
        </a>
        <a class="hover:underline" href="/privacy-policy">
            {{ __('Privacy Policy') }}
        </a>
    </nav>
</footer>
