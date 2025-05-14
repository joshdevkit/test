<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .text-white {
            color: white;
        }

        .center-text {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .old-english {
            font-family: 'Old English Text MT', serif;
            font-size: 22px;
        }

        .times-new-roman {
            font-family: 'Times New Roman', serif;
        }

        .half-bg-container {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 200px;
            margin-top: 20px;
            overflow: hidden;
            /* Ensures no content spills out */
        }

        .half-bg-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 50%;
            height: 100%;
            background-color: #fffece;
            z-index: -1;
        }

        .bg-image {
            background-image: url('{{ asset('bg6.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .center-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }

        .invent {
            font-size: 19px;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .half-bg-container {
                flex-direction: column;
                height: auto;
                margin-top: 10px;
            }


            .half-bg-container::before {
                width: 50%;
                /* Maintain half-width for yellow background */
                height: 100%;
                /* Ensure full height */
            }

            .old-english {
                font-size: 18px;
                /* Adjust font size for smaller screens */
            }

            .invent {
                font-size: 16px;
                /* Adjust font size for smaller screens */
            }

            .center-logo {
                margin-bottom: 10px;
            }
        }

        @media (max-width: 480px) {
            .half-bg-container {
                height: auto;
                padding: 10px;
            }

            .half-bg-container::before {
                width: 50%;
                /* Maintain yellow background's half-width */
                height: 100%;
                /* Ensure it remains the full height of the container */
            }

            .old-english {
                font-size: 16px;
                /* Further adjust font size for very small screens */
            }

            .invent {
                font-size: 14px;
            }
        }
    </style>


</head>

<body class="font-sans text-white-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 bg-image">
        <div>

            <img src="{{ asset('spup1.png') }}" alt="Logo" height="80" width="80">

        </div>

        <div class="center-text">
            <h1 class="old-english bold">St. Paul University Philippines</h1>
            <h6 class="times-new-roman">Tuguegarao City, 3500</h6>
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="center-logo">
                <img src="{{ asset('logo.png') }}" alt="Logo" style="width: 40px;">
                <div class="invent bold"> INVENTORY SYSTEM</div>
            </div>
            {{ $slot }}
        </div>
    </div>
</body>

</html>
