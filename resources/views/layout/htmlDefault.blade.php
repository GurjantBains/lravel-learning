<!doctype html>
<html lang="en">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('page title',"Laravel 12 Todo List App")</title>
</head>
<body class=" @yield('classes') ">
@if(session()->has('success'))
    <div x-data="{flash:true}">
    <div @click="flash=false" x-show="flash" class="fixed top-[100px] right-[100px] bg-gray-700 text-white  p-5 rounded-xl">{{session('success')}}</div>
    </div>
@endif

@yield('content')
</body>
</html>
