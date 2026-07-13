@extends('layouts.app')

@section('content')
    <livewire:agenda-day :date="$date" />
@endsection
