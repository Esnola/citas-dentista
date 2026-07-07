@extends('layouts.app')

@section('content')
    <livewire:client-appointments :client-id="(int) request()->route('client')" />
@endsection
