@extends('layouts.app')

@section('title', 'Профиль пользователя')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Карточка с основной информацией -->
            <div class="card mb-4">
                <div class="card-header">Профиль пользователя</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Фамилия:</strong> {{ $user->surname }}</p>
                            <p><strong>Имя:</strong> {{ $user->firstname }}</p>
                            @if($user->lastname)
                                <p><strong>Отчество:</strong> {{ $user->lastname }}</p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <p><strong>Дата рождения:</strong> {{ \Carbon\Carbon::parse($user->birth_date)->format('d.m.Y') }}</p>
                            <p><strong>Регион:</strong> {{ $user->region }}</p>
                            @if($user->authData)
                                <p><strong>Роль:</strong> 
                                    <span class="badge bg-{{ $user->authData->role === 'admin' ? 'success' : 'primary' }}">
                                        {{ $user->authData->role === 'admin' ? 'Администратор' : 'Пользователь' }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="{{ route('users.edit') }}" class="btn btn-primary">Редактировать профиль</a>
                            <a href="{{ route('users.change-password-form') }}" class="btn btn-warning">Изменить пароль</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Карточка с контактной информацией -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Контактная информация</span>
                    <a href="{{ route('users.add-phone-form') }}" class="btn btn-success btn-sm">Добавить номер</a>
                </div>
                <div class="card-body">
                    @if($user->contacts && $user->contacts->count() > 0)
                        <div class="list-group">
                            @foreach($user->contacts as $contact)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-phone"></i> {{ $contact->phone_number }}
                                    </span>
                                    <form method="POST" action="{{ route('users.remove-phone', ['phoneNumber' => urlencode($contact->phone_number)]) }}" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn p-0 m-0" style="background: none; border: none; outline: none; cursor: pointer; width: 2.5rem; height: 2.5rem; display: flex; align-items: center; justify-content: center;" title="Удалить номер" onclick="return confirm('Удалить этот номер?')">
                                            <svg width="28" height="28" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M6 6L14 14M14 6L6 14" stroke="#222" stroke-width="2" stroke-linecap="round"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Номера телефонов не добавлены.</p>
                    @endif
                </div>
            </div>

            <!-- Карточка с заказами -->
            <div class="card">
                <div class="card-header">Мои заказы</div>
                <div class="card-body">
                    @if($orders && $orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Дата создания</th>
                                        <th>Статус</th>
                                        <th>Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'selected' => 'info',
                                                        'collecting' => 'warning',
                                                        'underway' => 'primary',
                                                        'delivered' => 'success',
                                                        'recieved' => 'success',
                                                        'rejected' => 'danger',
                                                        'returned' => 'secondary'
                                                    ];
                                                    $statusNames = [
                                                        'selected' => 'Выбран',
                                                        'collecting' => 'Собирается',
                                                        'underway' => 'В пути',
                                                        'delivered' => 'Доставлен',
                                                        'recieved' => 'Получен',
                                                        'rejected' => 'Отклонен',
                                                        'returned' => 'Возвращен'
                                                    ];
                                                    $color = $statusColors[$order->order_status] ?? 'secondary';
                                                    $name = $statusNames[$order->order_status] ?? $order->order_status;
                                                @endphp
                                                <span class="badge bg-{{ $color }}">
                                                    {{ $name }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', [
                                                            'userId' => $order->user_id,
                                                            'createdAt' => urlencode(
                                                                $order->created_at instanceof \Carbon\Carbon
                                                                ? $order->created_at->format('Y-m-d H:i:s')
                                                                : $order->created_at
                                                            )
                                                            ])
                                                         }}" class="btn btn-info btn-sm">
                                                    Подробнее
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($orders->count() > 5)
                            <div class="mt-3">
                                <a href="{{ route('orders.index') }}" class="btn btn-primary">Все заказы</a>
                            </div>
                        @endif
                    @else
                        <p class="text-muted">У вас пока нет заказов.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-info">Посмотреть товары</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection