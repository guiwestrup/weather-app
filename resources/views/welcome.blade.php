<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Weather app</title>
    <script src="https://cdn.jsdelivr.net/npm/places.js@1.18.2" defer></script>
    <link href="{{ mix('css/main.css') }}" rel="stylesheet">
    <script src="{{ mix('js/app.js') }}" defer></script>

    
</head>
<body class="bg-blue-200">
    <div id="app" class="flex justify-center pt-16">
        <weather-app></weather-app>
    </div>
</body>
</html>