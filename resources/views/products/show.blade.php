@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $product->name }}</h1>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Назад к списку</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Описание</h5>
                    <p>{{ $product->description }}</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Основная информация</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><strong>Категория:</strong> 
                                    {{ $product->product_type == 'component' ? 'Комплектующие' : 
                                      ($product->product_type == 'peripheral' ? 'Периферия' : 'Программное обеспечение') }}
                                </li>
                                <li class="list-group-item"><strong>Цена:</strong> {{ number_format($product->price, 2) }} ₽</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    Наличие на складах
                </div>
                <div class="card-body">
                    @if(count($inventory) > 0)
                        <ul class="list-group">
                            @php $totalQuantity = 0; @endphp
                            @foreach($inventory as $item)
                                @php $totalQuantity += $item->quantity; @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->warehouse->name }} ({{ $item->warehouse->region }})
                                    <span class="badge bg-primary rounded-pill">{{ $item->quantity }} шт.</span>
                                </li>
                            @endforeach
                        </ul>
                        <div class="mt-3">
                            <strong>Всего в наличии:</strong> {{ $totalQuantity }} шт.
                        </div>
                    @else
                        <p>Товар отсутствует на складах.</p>
                    @endif
                </div>
                <div class="card-footer">
                    <a href="{{ route('products.availability', $product->id) }}" class="btn btn-outline-primary">Подробнее о наличии</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection