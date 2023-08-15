@extends('layouts.app')

@section('content')
    <h1>Lista de Financiadoras</h1>
    <ul>
        @foreach ($financiadoras as $financiadora)
            <li>
                {{ $financiadora->nombre }} - {{ $financiadora->descripcion }}
            </li>
        @endforeach
    </ul>
@endsection
