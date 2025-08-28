<!doctype html>
@vite('resources/js/app.js')
<html lang="en">
<head>
    <meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Document</title>
</head>
<body>
<h1>
    @isset($metfhod)
        {{ $metfhod }}
    @endisset dd
</h1>
</body>
</html>
