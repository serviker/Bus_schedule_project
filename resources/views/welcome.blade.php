<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Расписание автобусов</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        /* welcome.css */

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            border: 1px solid #0b423b;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .welcome-container {
            text-align: center;
        }

        .menu-container {
            margin: 0 auto;
            padding: 20px;
            width: 350px;
            background-color: #ffffff;
            border: 1px solid #0b423b;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .menu-list li {
            margin: 15px 0;
        }

        .menu-list a {
            text-decoration: none;
            color: #0b423b;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        .menu-list a:hover {
            color: orange;
        }

    </style>
</head>
<body>
<div class="welcome-container">
    <h1>Расписание автобусов!</h1>
    <div class="menu-container">
        <ul class="menu-list">
            <li><a href="{{ url('/api/buses') }}">Посмотреть все маршруты</a></li>
            <li><a href="{{ url('/api/find-bus') }}">Поиск маршрута</a></li>
            <li><a href="{{ route('create-route') }}">Добавить новый маршрут</a></li>
        </ul>
    </div>
</div>
</body>
</html>
