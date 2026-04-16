<x-app-layout>
    <x-slot name="title">Profile</x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="px-4 py-6 sm:p-8 bg-slate-800/60 border border-slate-700 sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="px-4 py-6 sm:p-8 bg-slate-800/60 border border-slate-700 sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="px-4 py-6 sm:p-8 bg-slate-800/60 border border-red-900/40 sm:rounded-2xl">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
