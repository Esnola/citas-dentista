@extends('layouts.app')

@section('content')
    <livewire:client-message-scheduler />
    <div class="h-px bg-white/10"></div>
    <livewire:message-manager />
@endsection
