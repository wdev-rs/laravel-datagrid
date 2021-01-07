@extends('layouts.app')

@section('content')
    <data-grid
        base-url={{$baseUrl}}
        :columns="{{json_encode($columns)}}"
    ></data-grid>
@endsection
