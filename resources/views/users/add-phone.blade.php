@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Добавить номер телефона</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.add-phone') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">Номер телефона</label>

                            <div class="col-md-6">
                                <input id="phone_number" type="tel" class="form-control @error('phone_number') is-invalid @enderror" 
                                       name="phone_number" value="{{ old('phone_number') }}" 
                                       placeholder="+71234567890" pattern="^\+[0-9]+$" required autocomplete="tel">

                                @error('phone_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                
                                <small class="form-text text-muted">
                                    Формат: +71234567890 (начинается с + и содержит только цифры)
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Добавить номер
                                </button>
                                <a href="{{ route('users.profile') }}" class="btn btn-secondary">
                                    Отмена
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection