<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @livewireScriptConfig
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Livewire</title>
</head>
<body>
<div class="container mx-auto mt-10">
    <livewire:tree.container/>
    <livewire:tree.form/>
</div>
</body>
</html>
