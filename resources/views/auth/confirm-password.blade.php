<x-guest-layout>
    <div class="mb-6 text-center">
        <h1 class="text-2xl font-semibold text-gray-900">{{ __('Confirm your password') }}</h1>
        <p class="mt-1 text-sm text-gray-600">{{ __('For your security, please confirm your password to continue') }}</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full mt-2">
            {{ __('Confirm') }}
        </x-primary-button>
    </form>
</x-guest-layout>
