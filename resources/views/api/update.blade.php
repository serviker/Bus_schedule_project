<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обновление маршрута</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            margin: 20px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            align-content: center;
        }

    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align: center">Обновление маршрута: </h1>
    <h1 style="text-align: center">{{ $route->name }}</h1>

    <!-- Форма для обновления маршрута -->
    <form action="{{ route('api.update', $route->id) }}" method="POST" id="updateRouteForm">
        @csrf
        @method('PATCH') <!-- Указываем метод PATCH для обновления -->

        <div class="form-group">
            <label for="stops">Выберите остановки для маршрута:</label>
            @foreach($uniqueStops as $stop)
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="stop_{{ $stop->id }}" name="stops[]" value="{{ $stop->id }}"
                           @if($route->stops->contains('id', $stop->id)) checked @endif>
                    <label class="form-check-label" for="stop_{{ $stop->id }}">{{ $stop->name }}</label>
                    <input type="text" class="form-control mt-1" name="stop_names[{{ $stop->id }}]"
                           placeholder="Новое название для {{ $stop->name }}"
                           value="{{ $stop->name }}"> <!-- Поле для изменения названия остановки -->
                </div>
            @endforeach
        </div>

        <!-- Поле для добавления новой остановки -->
        <div class="form-group">
            <label for="new_stop_name">Добавить новую остановку:</label>
            <input type="text" class="form-control" id="new_stop_name" name="new_stop_name" placeholder="Название новой остановки">
            <input type="hidden" name="bus_id" value="{{ $busId }}">
            <input type="hidden" name="interval" value="{{ $interval }}">
        </div>


        <div class="button-group">
            <button type="submit" class="btn btn-primary">Обновить маршрут</button>
            <!-- Кнопка для возврата в меню -->
            <a href="{{ route('api.index') }}" class="btn btn-success" style="background: #0b423b">Перейти к таблицам</a>
        </div>
    </form>

    <!-- Место для отображения сообщений об ошибках или успехе -->
    <div id="message" class="mt-3"></div>
</div>

<script>
    document.getElementById('updateRouteForm').onsubmit = function(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        // Отправляем AJAX-запрос
        const formData = new FormData(this);
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Указываем, что это AJAX-запрос
            }
        })
            .then(response => {
                // Проверяем, является ли ответ успешным (HTTP 200)
                if (!response.ok) {
                    throw new Error('Ошибка при отправке формы');
                }
                return response.json(); // Преобразуем ответ в JSON
            })
            .then(data => {
                const messageDiv = document.getElementById('message');
                if (data.message && data.message.includes('Ошибка')) {
                    messageDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

                    // Используем window.location.reload() для принудительной перезагрузки страницы
                    setTimeout(() => {
                        window.location.reload(true); // Перезагружаем страницу, принудительно загружая её с сервера
                    }, 300);  // Задержка в 1 секунду перед перезагрузкой страницы
                }
            })
            .catch(error => {
                const messageDiv = document.getElementById('message');
                messageDiv.innerHTML = `<div class="alert alert-danger">Ошибка обновления маршрута!</div>`;
            });
    };

</script>

</body>
</html>
