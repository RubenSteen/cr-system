<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet" />
    <script src="{{ mix('/js/app.js') }}" defer></script>

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css"> {{-- https://tailwindui.com/documentation suggested font--}}

    @routes {{-- https://github.com/tightenco/ziggy --}}

    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    @inertia
</body>
</html>
