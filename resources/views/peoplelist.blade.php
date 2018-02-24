@extends('layouts.app')

@section('content')
    <h2>Here is the mighty StarWars crew!</h2>

    <table class="table">
    <thead>
        <tr>
        <th>#</th>
        <th>Image</th>
        <th>Name</th>
        <th></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($response->results as $key => $people)
            <tr>
            <td>{{ \App\Helper\PeopleViewHelper::getId($people) }}</td>
            <td><img src="{{ $imageUrls[$key] }}" width="100"></td>
            <td>{{ $people->name }}</td>
            <td>
                <a class="btn btn-success" href="/people/{{ \App\Helper\PeopleViewHelper::getId($people) }}">Health Report</a>
            </td>
            </tr>
        @endforeach
    </tbody>
    </table>
    <div class="text-center">
        <!-- Super simple, there is a laravel paginator but dont know how to use -->
        <a class="btn btn-info" href="/people/?page={{ $page - 1 }}">&lt;&lt; Prev</a>
        <a class="btn btn-info" href="/people/?page={{ $page + 1 }}">Next &gt;&gt;</a>
    </div>
@endsection