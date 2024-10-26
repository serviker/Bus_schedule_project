<!DOCTYPE html>
<html>
<head>
    <title>Автобусы и маршруты</title>
    <style>
        table {
            border-collapse: collapse;
            width: 70%;
            margin: 20px auto;
        }
        th, td {
            border: 2px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
        }
        h1, h2 {
            text-align: center;
        }
        .edit-btn {
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 0.9em;
            color: white;
            background-color: #0b423b;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-home {
            display: block;
            width: 200px;
            margin: 20px auto; /* Центрирование кнопки */
            padding: 10px;
            text-align: center;
            background-color: #0b423b; /* Цвет кнопки */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-home:hover {
            background-color: #5a6268; /* Цвет при наведении */
        }
        .edit-btn:hover {
            background-color: #0056b3;
        }
        .delete-btn {
            margin-left: 10px;
            padding: 5px 10px;
            font-size: 0.9em;
            color: white;
            background-color: #850000;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #ff4d4d;
        }
        /* Стили для модального окна */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }
        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border: 2px solid #888;
            width: 30%;
            text-align: center;
            border-radius: 8px;
        }
        .modal-buttons {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }
        .modal-buttons button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-confirm {
            background-color: #850000;
            color: white;
        }
        .btn-cancel {
            background-color: #0b423b;
            color: white;
        }
    </style>
</head>
<body>

<h1>Расписание маршрутов</h1>

@foreach ($routes as $route)
    <div style="text-align: center;">
        <!-- Кнопка для редактирования маршрута с правильным URL -->
        <a href="/api/buses/{{ $route->id }}/edit" class="edit-btn">Изменить {{ $route->name }}</a>
        <h2 style="display: inline;">{{ $route->name }}</h2>
        <!-- Кнопка для удаления маршрута -->
        <button onclick="openModal({{ $route->id }})" class="delete-btn">Удалить {{ $route->name }}</button>
    </div>

    @if ($route->stops->isNotEmpty())
        <table>
            <tr>
                <th>Остановка</th>
                @php
                    $printedStops = [];
                @endphp

                @foreach ($route->stops as $stop)
                    @if (!in_array($stop->id, $printedStops))
                        <th>{{ $stop->name }}</th>
                        @php
                            $printedStops[] = $stop->id;
                        @endphp
                    @endif
                @endforeach
            </tr>

            @foreach ($route->buses as $bus)
                <tr>
                    <th>{{ $bus->name }}</th>
                    @php
                        $printedTimes = [];
                    @endphp

                    @foreach ($route->stops as $stop)
                        @php
                            $arrivalTime = $bus->stops->where('id', $stop->id)->first()?->pivot->arrival_time;
                        @endphp

                        @if ($arrivalTime && !in_array($arrivalTime, $printedTimes))
                            <td>{{ $arrivalTime }}
                                @php
                                    $printedTimes[] = $arrivalTime;
                                @endphp
                            </td>
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </table>
    @else
        <p style="text-align: center;">Остановки не найдены для этого маршрута</p>
    @endif
@endforeach

<!-- Кнопка для возвращения на главную страницу -->
<a href="{{ route('home') }}" class="btn-home">Вернуться на главную</a>

<!-- Модальное окно для подтверждения удаления -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <p>Вы уверены, что хотите удалить этот маршрут?</p>
        <div class="modal-buttons">
            <button onclick="closeModal()" class="btn-cancel">Отменить</button>
            <button id="confirmDelete" class="btn-confirm">Подтвердить</button>

        </div>
    </div>
</div>


<script>
    let routeIdToDelete = null;

    // Открытие модального окна
    function openModal(routeId) {
        routeIdToDelete = routeId;
        document.getElementById('deleteModal').style.display = 'block';
    }

    // Закрытие модального окна
    function closeModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    // Удаление маршрута после подтверждения
    document.getElementById('confirmDelete').onclick = function() {
        if (routeIdToDelete) {
            fetch(`/api/buses/${routeIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => {
                    if (!response.ok) throw new Error('Ошибка при удалении');
                    return response.json();
                })
                .then(data => {
                   // alert('Маршрут успешно удалён.');
                    closeModal();
                    location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Произошла ошибка при удалении маршрута.');
                });
        }
    }
</script>
</body>
</html>
