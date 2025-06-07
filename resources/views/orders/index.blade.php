@extends('layouts.app')

@section('title', 'Мои заказы')

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Мои заказы</h1>
    </div>
    
    @if(count($orders) > 0)
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Номер заказа</th>
                        <th>Дата заказа</th>
                        <th>Статус</th>
                        <th>Действие до</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{ substr(md5($order->user_id . $order->created_at), 0, 8) }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</td>
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
                            <td>{{ $order->valid_until ? \Carbon\Carbon::parse($order->valid_until)->format('d.m.Y') : 'Не указано' }}</td>
                            <td>
                                <a href="{{ route('orders.show', [
                                    $order->user_id,
                                    urlencode(
                                        $order->created_at instanceof \Carbon\Carbon
                                            ? $order->created_at->format('Y-m-d H:i:s')
                                            : $order->created_at
                                    )
                                ]) }}" class="btn btn-sm btn-info">
                                    Подробнее
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    @else
        <div class="alert alert-info">
            У вас пока нет заказов. <a href="{{ route('products.index') }}">Перейдите в каталог</a>, чтобы выбрать товары.
        </div>
    @endif
</div>
@endsection