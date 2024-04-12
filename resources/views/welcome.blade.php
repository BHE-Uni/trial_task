<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Management</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<script>
    window.APP_URL = "{{ env('APP_URL') }}";
</script>
<body>
    <div id="app"></div>
</body>
</html>