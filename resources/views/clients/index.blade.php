@extends('layouts.app')

@section('content')
    <livewire:client-index />
    <section id="programar-whatsapp">
        <livewire:client-message-scheduler />
    </section>
@endsection
