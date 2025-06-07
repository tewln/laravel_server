@extends('layouts.app')

@section('title', 'Наличие товара - ' . $product->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Наличие товара по регионам</h1>
            
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="text-muted">Компания: {{ $product->company }}</p>
                    <h4 class="text-primary">{{ number_format($product->price, 2) }} ₽</h4>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>Наличие по регионам</h5>
                </div>
                <div class="card-body">
                    @if($availability->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Регион</th>
                                        <th>Общее количество</th>
                                        <th>Статус</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availability as $item)
                                        <tr>
                                            <td>{{ $item->region }}</td>
                                            <td>
                                                <span class="badge {{ $item->total_quantity > 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $item->total_quantity }} шт.
                                                </span>
                                            </td>
                                            <td>
                                                @if($item->total_quantity > 10)
                                                    <span class="text-success">В наличии</span>
                                                @elseif($item->total_quantity > 0)
                                                    <span class="text-warning">Мало на складе</span>
                                                @else
                                                    <span class="text-danger">Нет в наличии</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <h6>Товар отсутствует на всех складах</h6>
                            <p class="mb-0">Данный товар в настоящее время недоступен ни в одном регионе.</p>
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
                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm mb-2">
                        Вернуться к товару
                    </a>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                        К списку товаров
                    </a>
                </div>
            </div>
            
            <div class="card mt-3">
                <div class="card-header">
                    <h6>Информация о товаре</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <strong>Тип:</strong> 
                        @switch($product->product_type)
                            @case('component')
                                Комплектующие
                                @break
                            @case('peripheral')
                                Периферия
                                @break
                            @case('software')
                                Программное обеспечение
                                @break
                            @default
                                {{ $product->product_type }}
                        @endswitch
                    </small><br>
                    <small class="text-muted"><strong>ID товара:</strong> {{ $product->id }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection