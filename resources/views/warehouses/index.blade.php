@extends('layouts.app')

@section('title', 'Склады')

@section('content')
<div class="mb-4">
    <h1>Склады</h1>
    
    <!-- Фильтры -->
    <div class="card mb-4">
        <div class="card-header">
            Фильтры
        </div>
        <div class="card-body">
            <form action="{{ route('warehouses.index') }}" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="region" class="form-label">Регион</label>
                        <select class="form-select" id="region" name="region">
                            <option value="">Все регионы</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="active_only" class="form-label">Статус</label>
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="active_only" name="active_only" value="1" {{ request('active_only') ? 'checked' : '' }}>
                            <label class="form-check-label" for="active_only">Только активные</label>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">Применить</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Список складов -->
    <div class="row">
        @forelse($warehouses as $warehouse)
            <div class="col-md-6 mb-4">
                <div class="card h-100 {{ $warehouse->isActive() ? '' : 'border-danger' }}">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        {{ $warehouse->name }}
                        @if($warehouse->isActive())
                            <span class="badge bg-success">Активен</span>
                        @else
                            <span class="badge bg-danger">Неактивен</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <p><strong>Регион:</strong> {{ $warehouse->region }}</p>
                        <p><strong>Период работы:</strong> {{ $warehouse->start_date->format('d.m.Y') }} - {{ $warehouse->formatted_end_date }}</p>
                        <a href="{{ route('warehouses.show', $warehouse->id) }}" class="btn btn-primary">Просмотр инвентаря</a>
                        <a href="{{ route('warehouses.deliveries', $warehouse->id) }}" class="btn btn-outline-secondary ms-2">История поставок</a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">
                    По вашему запросу склады не найдены.
                </div>
            </div>
        @endforelse
    </div>
    
    <!-- Пагинация -->
    <div class="mt-4">
        {{ $warehouses->appends(request()->except('page'))->links() }}
    </div>
</div>
@endsection