{{--@extends('layouts.app')--}}

{{--@section('content')--}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <style>
        .container {
            width: 80%;
            margin: auto;
            font-family: Arial, sans-serif;
        }

        h2 {
            color: #333;
            margin-top: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button.btn-primary {
            background-color: #007bff;
            color: #fff;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button.btn-primary:hover {
            background-color: #0056b3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>
</head>
    <div class="container">
        <h2>Получение кадастровых данных</h2>

        <form action="{{ route('plots') }}" method="GET">
            <div class="form-group">
                <label for="cadastreNumbers">Кадастровые номера</label>
                <input type="text" class="form-control" id="cadastreNumbers" name="cadastreNumbers" placeholder="Например, 69:27.0000022:1306, 69:27.0000022:1307">
            </div>
            <button type="submit" class="btn btn-primary">Получить данные</button>
        </form>

        @if (isset($plots))
            <table class="table">
                <thead>
                <tr>
                    <th>Кадастровый номер</th>
                    <th>Адрес</th>
                    <th>Стоимость</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($plots as $plot)
                    <tr>
                        <td>{{ $plot->cadastreNumber }}</td>
                        <td>{{ $plot->address }}</td>
                        <td>{{ $plot->price }} р. | {{ $plot->area }} м²</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <h3>Данные не найдены</h3>
        @endif
    </div>
@push('styles')
@endpush

{{--@endsection--}}
