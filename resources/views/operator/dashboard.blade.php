@extends('layouts.admin')

@section('title', 'Dashboard Operator')
@section('topbar_title', 'Dashboard Operator')

@section('content')
    <div class="card">
        <h3>Selamat Datang, {{ auth()->user()->name }}!</h3>
        <p>Anda login sebagai Operator.</p>
    </div>
@endsection