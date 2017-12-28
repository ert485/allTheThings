@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('bin.search.form')
        @include('bin.search.results')
        @include('bin.view')
    </div>
</div>

@endsection