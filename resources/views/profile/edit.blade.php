<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-400 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 min-h-screen bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden">
        <!-- Background Pattern (opsional) -->
        <div class="absolute inset-0 opacity-5 z-0 bg-cover bg-center" style="background-image: url('/images/bg-pattern.png');"></div>

        <div class="relative z-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Update Profile Info -->
            <div class="p-6 bg-gray-800 bg-opacity-90 backdrop-blur-md shadow-md sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-6 bg-gray-800 bg-opacity-90 backdrop-blur-md shadow-md sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="p-6 bg-gray-800 bg-opacity-90 backdrop-blur-md shadow-md sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
