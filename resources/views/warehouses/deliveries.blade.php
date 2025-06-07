@extends('layouts.app')

@section('title', 'Поставки склада - ' . $warehouse->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Поставки склада</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $warehouse->name }}</h5>
                    <p class="card-text">
                        <strong>Регион:</strong> {{ $warehouse->region }}<br>
                        <strong>Период работы:</strong> {{ $warehouse->start_date }} - {{ $warehouse->formatted_end_date }}
                    </p>
                </div>
            </div>
            
            <!-- Фильтры -->
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('warehouses.deliveries', $warehouse->id) }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="date_from" class="form-label">Дата с</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="date_to" class="form-label">Дата по</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">Применить</button>
                                <a href="{{ route('warehouses.deliveries', $warehouse->id) }}" class="btn btn-secondary">Сбросить</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>История поставок</h5>
                    <small class="text-muted">Всего записей: {{ $deliveries->total() }}</small>
                </div>
                <div class="card-body">
                    @if($deliveries->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Дата поставки</th>
                                        <th>Товар</th>
                                        <th>Количество</th>
                                        <th>Цена за единицу</th>
                                        <th>Общая стоимость</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deliveries as $delivery)
                                        <tr>
                                            <td>{{ $delivery->delivery_date }}</td>
                                            <td>
                                                <a href="{{ route('products.show', $delivery->product->id) }}" class="text-decoration-none">
                                                    {{ $delivery->product->name }}
                                                </a>
                                                <br>
                                                <small class="text-muted">{{ $delivery->product->company }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ $delivery->quantity }} шт.</span>
                                            </td>
                                            <td>{{ number_format($delivery->product->price, 2) }} ₽</td>
                                            <td>
                                                <strong>{{ number_format($delivery->product->price * $delivery->quantity, 2) }} ₽</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Пагинация -->
                        <div class="d-flex justify-content-center">
                            {{ $deliveries->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h6>Поставки не найдены</h6>
                            <p class="mb-0">
                                @if(request()->hasAny(['date_from', 'date_to']))
                                    Для выбранного периода поставки отсутствуют.
                                @else
                                    На данный склад пока не было поставок.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6>Действия</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('warehouses.show', $warehouse->id) }}" class="btn btn-primary btn-sm mb-2">
                        Вернуться к складу
                    </a>
                    <a href="{{ route('warehouses.index') }}" class="btn btn-secondary btn-sm">
                        К списку складов
                    </a>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Статистика поставок</h6>
                </div>
                <div class="card-body">
                    @if($stats)
                        <small class="text-muted">
                            <strong>Общее количество поставок:</strong> {{ $stats->total_deliveries }}<br>
                            <strong>Общая стоимость:</strong> {{ number_format($stats->total_value, 2) }} ₽<br>
                            <strong>Средняя поставка:</strong> {{ number_format($stats->avg_delivery_value, 2) }} ₽
                        </small>
                    @else
                        <small class="text-muted">Статистика недоступна</small>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection