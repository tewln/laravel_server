@extends('layouts.app')

@section('title', 'Детали заказа')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>
            Заказ #{{ substr(md5($order->user_id . $order->created_at->format('Y-m-d H:i:s')), 0, 8) }}
        </h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
            Назад к списку
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Товары в заказе</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered m-0">
                            <thead>
                                <tr>
                                    <th>Товар</th>
                                    <th>Цена</th>
                                    <th>Количество</th>
                                    <th>Сумма</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($orderItems as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('products.show', $item->product_id) }}">{{ $item->product->name }}</a>
                                            <span class="badge bg-secondary">{{ $item->product->product_type }}</span>
                                        </td>
                                        <td>{{ number_format($item->product->price, 2) }} ₽</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->quantity * $item->product->price, 2) }} ₽</td>
                                    </tr>
                                @endforeach
                                <tr class="table-active">
                                    <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                    <td><strong>{{ number_format($totalCost, 2) }} ₽</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">История заказа</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        @foreach($history as $historyItem)
                            <li class="list-group-item">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $historyItem->update_date->format('d.m.Y H:i') }}</h5>
                                </div>
                                <p class="mb-1">Статус заказа изменен на: 
                                    <strong>
                                        @switch($historyItem->order_status)
                                            @case('selected')
                                                Выбран
                                                @break
                                            @case('collecting')
                                                Комплектуется
                                                @break
                                            @case('underway')
                                                В пути
                                                @break
                                            @case('delivered')
                                                Доставлен
                                                @break
                                            @case('received')
                                                Получен
                                                @break
                                            @case('rejected')
                                                Отклонен
                                                @break
                                            @case('returned')
                                                Возвращен
                                                @break
                                            @default
                                                {{ $historyItem->order_status }}
                                        @endswitch
                                    </strong>
                                </p>
                                @if($historyItem->comment)
                                    <p class="mb-1">Комментарий: {{ $historyItem->comment }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Информация о заказе</h5>
                </div>
                <div class="card-body">
                    <p><strong>Дата заказа:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                    <p><strong>Текущий статус:</strong> 
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
                    </p>
                    <p><strong>Действителен до:</strong> {{ $order->valid_until ? $order->valid_until->format('d.m.Y') : 'Не указано' }}</p>
                    <p><strong>Общая сумма:</strong> {{ number_format($totalCost, 2) }} ₽</p>
                </div>
            </div>
            
            @if($order->order_status == 'delivered')
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Действия</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('orders.update-status', [
                            $order->user_id,
                            urlencode(
                                $order->created_at instanceof \Carbon\Carbon
                                    ? $order->created_at->format('Y-m-d H:i:s')
                                    : $order->created_at
                            )
                        ]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <input type="hidden" name="order_status" value="received">
                                <button type="submit" class="btn btn-success w-100">Подтвердить получение</button>
                            </div>
                        </form>
                        
                        <form action="{{ route('orders.update-status', [$order->user_id, $order->created_at]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div>
                                <input type="hidden" name="order_status" value="rejected">
                                <button type="submit" class="btn btn-danger w-100">Отказаться от получения</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection