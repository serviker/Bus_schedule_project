<!DOCTYPE html>
<html>
<head>
    <title>Автобусы и маршруты</title>
    <style>
        table {
            border-collapse: collapse;
            width: 60%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ddd;
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
    </style>
</head>
<body>

<h1>Расписание маршрутов</h1>

@foreach ($routes as $route)
    <div style="text-align: center;">
        <h2 style="display: inline;">{{ $route->name }}</h2>
        <!-- Кнопка для редактирования маршрута с правильным URL -->
        <a href="/api/buses/{{ $route->id }}/edit" class="edit-btn">Изменить {{ $route->name }}</a>
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

</body>
</html>
