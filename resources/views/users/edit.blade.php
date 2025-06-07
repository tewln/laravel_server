@extends('layouts.app')

@section('title', 'Редактирование профиля')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h1 class="h5 mb-0">Редактирование профиля</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('users.update') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="surname" class="form-label">Фамилия</label>
                        <input id="surname" type="text" class="form-control @error('surname') is-invalid @enderror" name="surname" value="{{ old('surname', $user->surname) }}" required>
                        @error('surname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="firstname" class="form-label">Имя</label>
                        <input id="firstname" type="text" class="form-control @error('firstname') is-invalid @enderror" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
                        @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Отчество</label>
                        <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname', $user->lastname) }}">
                        @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="region" class="form-label">Регион</label>
                        <select id="region" class="form-select @error('region') is-invalid @enderror" name="region" required>
                            <option value="">Выберите регион</option>
                            @foreach($regions as $region)
                                <option value="{{ $region }}" {{ old('region', $user->region) == $region ? 'selected' : '' }}>{{ $region }}</option>
                            @endforeach
                        </select>
                        @error('region')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <button type="submit" class="btn btn-primary">
                            Сохранить изменения
                        </button>
                        <a href="{{ route('users.profile') }}" class="btn btn-secondary">Отмена</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection