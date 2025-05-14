<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />
    <style>
        .green-button {
            display: inline-block;
            background-color: #4caf50;
            /* Green background color */
            color: white;
            /* White text color */
            padding: 10px 20px;
            /* Padding to make it look like a button */
            text-align: center;
            /* Center text alignment */
            text-decoration: none;
            /* Remove underline from the link */
            border-radius: 5px;
            /* Rounded corners */
            width: 380px;
            /* Set specific width */
            height: 50px;
            /* Set specific height */
            line-height: 30px;
            /* Align text vertically */
            font-size: 16px;
            /* Font size */
            font-weight: bold;
            /* Bold text */
        }

        .green-button:hover {
            background-color: #45a049;
            /* Darker green on hover */
        }
    </style>

    <form>

        @if (Route::has('login'))

            @auth
                <a href="{{ url('/dashboard') }}">Dashboard</a>
                <p>Role: {{ Auth::user()->getRoleNames()->first() }}</p>
            @else
                <div class="mt-4">
                    <a x-primary-button class="green-button" href="{{ route('login') }}">
                        {{ __('Administrator') }}
                    </a>
                </div>
                <div class="mt-4">
                    <a x-primary-button class="green-button" href="{{ route('login') }}">
                        {{ __('Dean') }}
                    </a>
                </div>

                <div class="mt-4">
                    <a x-primary-button class="green-button" href="{{ route('login') }}">
                        {{ __('Laboratory Technician') }}
                    </a>
                </div>

                <div class="mt-4">
                    <a x-primary-button class="green-button" href="{{ route('login') }}">
                        {{ __('SITE Office Secretary') }}
                    </a>
                </div>

                <div class="mt-4">
                    <a x-primary-button class="green-button" href="{{ route('login') }}">
                        {{ __('Instructor') }}
                    </a>
                </div>




                <div class="flex items-center justify-end mt-4">
                    @if (Route::has('register'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            href="{{ route('register') }}">
                            {{ __('You do not have an account?') }}
                        </a>
                    @endif
                @endauth
            </div>
        @endif

        </div>
    </form>

</x-guest-layout>
