@extends('layouts.app')

@section('title', 'Главная - Интернет-магазин электроники')

@section('content')
    <div class="jumbotron">
        <h1 class="display-4">Добро пожаловать в интернет-магазин электроники!</h1>
        <p class="lead">У нас вы найдете широкий ассортимент компьютерных комплектующих, периферийных устройств и программного обеспечения.</p>
        <hr class="my-4">
        <p>Начните с просмотра наших популярных товаров или воспользуйтесь поиском.</p>
        <a class="btn btn-primary btn-lg" href="{{ route('products.index') }}" role="button">Перейти к товарам</a>
    </div>

    <div class="row mt-5">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Компьютерные комплектующие</h5>
                    <p class="card-text">Процессоры, материнские платы, видеокарты, оперативная память и многое другое.</p>
                    <a href="{{ route('products.index') }}?type=component" class="btn btn-outline-primary">Смотреть</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Периферийные устройства</h5>
                    <p class="card-text">Клавиатуры, мыши, мониторы, наушники и другие устройства для вашего компьютера.</p>
                    <a href="{{ route('products.index') }}?type=peripheral" class="btn btn-outline-primary">Смотреть</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Программное обеспечение</h5>
                    <p class="card-text">Операционные системы, офисные приложения, антивирусы и игры.</p>
                    <a href="{{ route('products.index') }}?type=software" class="btn btn-outline-primary">Смотреть</a>
                </div>
            </div>
        </div>
    </div>
@endsection