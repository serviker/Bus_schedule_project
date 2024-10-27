<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создание нового маршрута</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <style>
        /* Стили для контейнера формы */
        .form-container {
            width: 40%;
            max-width: 60%;
            top: 300px;
            height: auto; /* изменено для автоматической высоты */
            border-radius: 10px;
            position: relative;
            background-color: #f0f0f0;
            padding: 30px 10px;
            margin: auto;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        form {
            display: flex;
            flex-direction: column;
            width: 70%; /* Занимает 100% ширины контейнера */
            gap: 10px;
            height: auto;
            border: 3px solid #0b423b;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        /* Стили для заголовка */
        h2 {
            text-align: center;
            font-size: 1.5rem;
            color: #333;
        }

        /* Стили для полей формы */
        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="number"],
        input[type="time"],
        button {
            padding: 10px;
            font-size: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
            width: 100%; /* Занимает всю ширину контейнера */
        }

        button {
            background-color: #0b423b;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Стили для меню */
        .menu-container {
            width: 100%;
        }

        .menu-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 20px;
            font-weight: bold;
        }

        .menu-list li {
            width: 100%;
        }

        .menu-list a {
            display: block;
            padding: 15px;
            text-align: center;
            text-decoration: none;
            color: #333;
            background-color: #f5f5f5;
            border: 2px solid transparent;
            border-radius: 5px;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .menu-list a:hover {
            background-color: #d1e7dd;
            border-color: #0f5132;
        }

        .menu-list a:active {
            background-color: #c3e6cb;
            border-color: #0b423b;
            transform: translateY(2px);
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Добавить новый маршрут</h2>
    <form id="routeForm" method="POST" action="{{ route('api.store') }}">
        @csrf

        <label for="route_number">Номер маршрута:</label>
        <input type="number" id="route_number" name="route_number" required placeholder="Введите номер маршрута">

        <label for="initial_stop_name">Начальная остановка:</label>
        <input type="text" id="initial_stop_name" name="initial_stop_name" required placeholder="Название начальной остановки">

        <label for="departure_time">Время отправления:</label>
        <input type="time" id="departure_time" name="departure_time" required>

        <label for="bus_count">Количество автобусов:</label>
        <input type="number" id="bus_count" name="bus_count" min="1" value="1" required>

        <label for="bus_departure_interval">Интервал между отправлениями автобусов (в минутах):</label>
        <input type="number" id="bus_departure_interval" name="bus_departure_interval" min="1" value="30" required>

        <label for="stop_interval">Интервал между остановками (в минутах):</label>
        <input type="number" id="stop_interval" name="stop_interval" min="1" value="20" required>

        <button type="submit">Добавить маршрут</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('routeForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(this);

        axios.post('{{ route('api.store') }}', formData, {
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => {
                console.log('Маршрут и остановка успешно добавлены:', response.data);
                location.href = "{{ url('/api/buses') }}"; // перенаправление после добавления маршрута
            })
            .catch(error => {
                console.error('Произошла ошибка:', error);
            });
    });
</script>

</body>
</html>
