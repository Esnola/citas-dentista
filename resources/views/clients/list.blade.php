@extends('layouts.app')

@section('content')
    <div class="grid gap-4">
        <div class="rounded-3xl border border-white/10 bg-white/5 p-6 backdrop-blur">
            <h1 class="mt-2 text-xl font-semibold d uppercase tracking-[0.08em] text-emerald-300/80">Listado de clientes</h1>
        </div>

        @livewire('client-list', ['showAllClients' => true])
    </div>
@endsection
