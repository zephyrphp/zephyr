import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    content: [
        './app/Filament/**/*.php',
        './resources/views/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    presets: [preset],
}
