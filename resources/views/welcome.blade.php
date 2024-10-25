<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание автобусов</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
</head>
<body>
<div class="welcome-container">
    <h1>Расписание автобусов!</h1>
    <div class="menu-container">
        <ul class="menu-list">
            <li><a href="{{ url('/api/buses') }}">View</a></li>
            <li><a href="{{ url('/api/find-bus') }}">Search</a></li>
            <li><a href="{{ url('/edit') }}">Edit</a></li>
        </ul>
    </div>
</div>
</body>
</html>
