<x-guest-layout>
    <div class="mx-auto mt-10 bg-white shadow-lg rounded-lg p-6 text-center max-w-md">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Welcome to {{ config('app.name') }}</h1>

        <p class="text-gray-900 mb-2">
            Hello, <strong>{{ $user->name }}</strong>!
        </p>
        <p class="text-gray-900 mb-6">
            Your email: <strong>{{ $user->email }}</strong>
        </p>

        <p class="text-gray-700 mb-6">
            Thank you for registering. Your account has been created successfully.
        </p>

        <p class="text-gray-600 mb-6">
            Please wait while an administrator reviews your information. You’ll be notified once your account is
            verified.
        </p>

        <div class="text-sm text-gray-500">
            If you have any questions, feel free to contact support.
        </div>
    </div>
</x-guest-layout>