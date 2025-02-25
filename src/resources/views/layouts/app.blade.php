<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Laravel App')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('kanban.index') }}">Kanban Board</a></li>
            @if(auth()->check() && auth()->user()->role === 'admin')
                <li><a href="{{ route('admin.users.index') }}">User Management</a></li>
            @endif
        </ul>
    </nav>

    <div class="container">
        @yield('content')
    </div>
</body>
</html>
