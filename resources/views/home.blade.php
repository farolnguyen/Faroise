<x-app-layout>
    <x-slot name="title">Find Your Sound</x-slot>

    <div
        x-data="soundPlayer(@js($mixData))"
        class="flex flex-col"
        style="min-height: calc(100vh - 64px)"
        @keydown.escape.window="sleepMode && exitSleepMode()"
    >
        @include('home._soundmeta')
        @include('home._mix-banner')
        @include('home._toolbar')
        @include('home._stage')
        @include('home._sound-library')
        @include('home._bottom-bar')
        @include('home._sleep-overlay')
        @include('home._save-mix-modal')
        @include('home._timer-modal')
    </div>
</x-app-layout>
