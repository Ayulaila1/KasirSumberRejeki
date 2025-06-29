<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Auth Page</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @livewireStyles
</head>

<body>
    {{ $slot }}
    @livewireScripts
</body>

</html>