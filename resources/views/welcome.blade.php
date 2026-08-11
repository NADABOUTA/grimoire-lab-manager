<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Grimoire</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-10 rounded-2xl shadow-lg text-center">

        <h1 class="text-4xl font-bold text-gray-800 mb-3">
            Grimoire
        </h1>

        <p class="text-gray-600 mb-8">
            Laboratoire de recherche
        </p>

        <div class="flex gap-4 justify-center">

            <a href="{{ route('login') }}"
               class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                🔐 Login
            </a>

            <a href="{{ route('register') }}"
               class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                📝 Register
            </a>

        </div>

    </div>

</body>
</html>