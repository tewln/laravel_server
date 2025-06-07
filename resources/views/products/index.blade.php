@extends('layouts.app')

@section('title', 'Каталог товаров')

@section('content')
<div class="mb-4">
    <h1>Каталог товаров</h1>
    
    <!-- Фильтры -->
    <div class="card mb-4">
        <div class="card-header">
            Фильтры
        </div>
        <div class="card-body">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Поиск по названию</label>
                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="type" class="form-label">Тип товара</label>
                        <select class="form-select" id="type" name="type">
                            <option value="">Все типы</option>
                            <option value="component" {{ request('type') == 'component' ? 'selected' : '' }}>Комплектующие</option>
                            <option value="peripheral" {{ request('type') == 'peripheral' ? 'selected' : '' }}>Периферия</option>
                            <option value="software" {{ request('type') == 'software' ? 'selected' : '' }}>ПО</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="min_price" class="form-label">Цена от</label>
                        <input type="number" class="form-control" id="min_price" name="min_price" value="{{ request('min_price') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="max_price" class="form-label">Цена до</label>
                        <input type="number" class="form-control" id="max_price" name="max_price" value="{{ request('max_price') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Применить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Сортировка -->
    <div class="mb-3">
        <div class="btn-group">
            <a href="{{ route('products.index', array_merge(
                        request()->except(['sort', 'direction']),
                        ['sort' => 'name', 'direction' => 'asc']
                        ))
                     }}"
               class="btn btn-outline-secondary {{
                      request('sort') == 'name' &&
                      request('direction') == 'asc'
                      ? 'active' : ''
                      }}">
                По названию (А-Я)
            </a>
            <a href="{{ route('products.index', array_merge(
                        request()->except(['sort', 'direction']),
                        ['sort' => 'name', 'direction' => 'desc']
                        ))
                     }}"
               class="btn btn-outline-secondary {{
                      request('sort') == 'name' &&
                      request('direction') == 'desc'
                      ? 'active' : ''
                      }}">
                По названию (Я-А)
            </a>
            <a href="{{ route('products.index', array_merge(
                        request()->except(['sort', 'direction']),
                        ['sort' => 'price', 'direction' => 'asc']
                        ))
                    }}"
               class="btn btn-outline-secondary {{
                      request('sort') == 'price' &&
                      request('direction') == 'asc'
                      ? 'active' : ''
                      }}">
                Цена (по возрастанию)
            </a>
            <a href="{{ route('products.index', array_merge(
                        request()->except(['sort', 'direction']),
                        ['sort' => 'price', 'direction' => 'desc']
                        ))
                     }}"
               class="btn btn-outline-secondary {{
                      request('sort') == 'price' &&
                      request('direction') == 'desc'
                      ? 'active' : ''
                      }}">
                Цена (по убыванию)
            </a>
        </div>
    </div>

    <!-- Список товаров -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">
                            <span class="badge bg-secondary">{{ $product->product_type == 'component' ? 'Комплектующие' : ($product->product_type == 'peripheral' ? 'Периферия' : 'ПО') }}</span>
                        </p>
                        <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                        <p class="card-text"><strong>Цена:</strong> {{ number_format($product->price, 2) }} ₽</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Подробнее</a>
                        <a href="{{ route('products.availability', $product->id) }}" class="btn btn-outline-secondary">Наличие</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    По вашему запросу товары не найдены.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Пагинация -->
    <div class="mt-4">
        {{ $products->appends(request()->except('page'))->links() }}
    </div>
</div>
@endsection