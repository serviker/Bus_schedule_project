@extends('app')

@section('content')
    <div class="container d-flex justify-content-center align-items-center flex-column vh-100">
        <div class="text-center w-75">
            <!-- Заголовок и кнопка на главную -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a href="{{ route('home') }}" class="btn btn-secondary" style="background: #0f5132">На главную</a>
                <h1>Поиск автобусов</h1>
            </div>

            <!-- Форма для поиска -->
            <form action="{{ route('api.find-bus') }}" method="GET" class="mb-4 p-4 border rounded shadow">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="col-md-5 mb-3 pe-2"> <!-- Добавлен отступ справа -->
                        <label for="from" class="form-label">Исходная остановка</label>
                        <input type="text" name="from" id="from" class="form-control"
                               placeholder="Введите ID исходной остановки" required>
                    </div>
                    <div class="col-md-5 mb-3 pe-2"> <!-- Добавлен отступ справа -->
                        <label for="to" class="form-label">Конечная остановка</label>
                        <input type="text" name="to" id="to" class="form-control"
                               placeholder="Введите ID конечной остановки" required>
                    </div>
                    <div class="col-md-2 mb-3">
                        <button type="submit" class="btn btn-primary w-100" style="margin-top: 30px">Найти</button>
                    </div>
                </div>


            </form>

            <!-- Вывод названий остановок -->
            @if(isset($from) && isset($to))
                <h2 class="mb-4">Результаты поиска для маршрута: <br> от ост. <strong>{{ $from }}</strong> до ост. <strong>{{ $to }}</strong></h2>
            @endif

            <!-- Вывод результатов поиска -->
            @if(isset($buses) && count($buses) > 0)
                <div class="table-responsive">
                    <h2>Найденные автобусы</h2>
                    <table class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <th>Маршрут</th>
                            <th>Следующие прибытия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($buses as $bus)
                            <tr>
                                <td>{{ $bus['route'] }}</td>
                                <td>
                                    @if(!empty($bus['next_arrivals']))
                                        {{ $bus['next_arrivals'] }}
                                    @else
                                        Нет ближайших прибытий
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>Автобусы не найдены для выбранного маршрута</p>
            @endif
        </div>
    </div>
@endsection
