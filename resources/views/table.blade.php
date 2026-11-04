@extends('layouts.app')

@section('title', 'Список сохраненных данных')

@section('content')
    <h2 class="mb-4">Сохраненные заявки</h2>

    @if(empty($allData))
        <div class="alert alert-info">Пока нет сохраненных данных.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Телефон</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allData as $data)
                        <tr>
                            <td>{{ $data['full_name'] ?? 'Не указано' }}</td>
                            <td>{{ $data['email'] ?? 'Не указано' }}</td>
                            <td>{{ $data['phone'] ?? 'Не указано' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
