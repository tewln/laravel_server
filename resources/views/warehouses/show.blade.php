@extends('layouts.app')

@section('title', 'Склад: ' . $warehouse->name)

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Склад: {{ $warehouse->name }}</h1>
        <a href="{{ route('warehouses.index') }}" class="btn btn-secondary">Назад к складам</a>
    </div>
    
    <div class="card mb-4">
        <div class="card-header">
            Информация о складе
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Название:</strong> {{ $warehouse->name }}</p>
                    <p><strong>Регион:</strong> {{ $warehouse->region }}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Дата открытия:</strong> {{ $warehouse->start_date->format('d.m.Y') }}</p>
                    <p><strong>Дата закрытия:</strong> {{ $warehouse->formatted_end_date }}</p>
                    <p><strong>Статус:</strong> 
                        @if($warehouse->isActive())
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-danger">Неактивен</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Инвентарь склада</h5>
            <a href="{{ route('warehouses.deliveries', $warehouse->id) }}" class="btn btn-outline-primary">История поставок</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-bordered m-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название товара</th>
                            <th>Тип</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inventory as $item)
                            <tr>
                                <td>{{ $item->product_id }}</td>
                                <td>{{ $item->product->name }}</td>
                                <td>
                                    @switch($item->product->product_type)
                                        @case('component')
                                            <span class="badge bg-primary">Комплектующие</span>
                                            @break
                                        @case('peripheral')
                                            <span class="badge bg-info">Периферия</span>
                                            @break
                                        @case('software')
                                            <span class="badge bg-success">ПО</span>
                                            @break
                                        @default
                                            <span class="badge bg-secondary">{{ $item->product->product_type }}</span>
                                    @endswitch
                                </td>
                                <td>{{ number_format($item->product->price, 2) }} ₽</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <a href="{{ route('products.show', $item->product_id) }}" class="btn btn-sm btn-info">Подробнее</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Товары на складе отсутствуют.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ $inventory->links() }}
        </div>
    </div>
</div>
@endsection