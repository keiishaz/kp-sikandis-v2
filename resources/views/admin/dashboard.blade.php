@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('topbar_title', 'Dashboard Admin')

@section('content')
    <div class="card">
        <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
        <p>Anda login sebagai Admin.</p>
    </div>
@endsection