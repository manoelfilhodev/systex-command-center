@extends('layouts.systex')

@section('content')
    <x-topbar
        title="Command Center"
        subtitle="Visão executiva da SYSTEX Sistemas Inteligentes"
    />

    <section class="grid">
        @foreach ($cards as $card)
            <x-stat-card
                :label="$card['label']"
                :value="$card['value']"
                :description="$card['description']"
                :trend="$card['trend']"
            />
        @endforeach
    </section>
@endsection