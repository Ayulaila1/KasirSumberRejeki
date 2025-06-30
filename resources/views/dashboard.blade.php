@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
@livewire('dashboard.main')
@livewire('admin-dashboard')
@endsection