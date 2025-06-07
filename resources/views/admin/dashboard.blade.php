@extends('layouts.app')

@section('title', 'Панель администратора')

@section('content')
<div class="mb-4">
    <h1 class="mb-4">Панель администратора</h1>
    
    <div class="row mb-4">
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Пользователи</h5>
                    <p class="card-text display-4">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Заказы</h5>
                    <p class="card-text display-4">{{ $totalOrders }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Товары</h5>
                    <p class="card-text display-4">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Товаров на складах</h5>
                    <p class="card-text display-4">{{ $totalInventory }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Последние заказы</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Пользователь</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($latestOrders as $order)
                                    <tr>
                                        <td>{{ substr(md5($order->user_id . $order->created_at->format('Y-m-d H:i:s')), 0, 8) }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            @switch($order->order_status)
                                                @case('selected')
                                                    <span class="badge bg-secondary">Выбран</span>
                                                    @break
                                                @case('collecting')
                                                    <span class="badge bg-info">Комплектуется</span>
                                                    @break
                                                @case('underway')
                                                    <span class="badge bg-primary">В пути</span>
                                                    @break
                                                @case('delivered')
                                                    <span class="badge bg-warning">Доставлен</span>
                                                    @break
                                                @case('received')
                                                    <span class="badge bg-success">Получен</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge bg-danger">Отклонен</span>
                                                    @break
                                                @case('returned')
                                                    <span class="badge bg-dark">Возвращен</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $order->order_status }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Обработка поставок</h5>
                </div>
                <div class="card-body">
                    <p>Запустите процесс обработки ожидающих поставок, чтобы обновить инвентарь на складах.</p>
                    
                    <form action="{{ route('admin.process-deliveries') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Обработать ожидающие поставки
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Общая стоимость заказов</h5>
                </div>
                <div class="card-body">
                    <p class="display-4">{{ number_format($totalOrdersValue, 2) }} ₽</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection