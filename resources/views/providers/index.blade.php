@extends('layouts.app')

@section('content')
<div class="space-y-6">
    @livewire('providers.provider-list')
    @livewire('providers.provider-form')
</div>
@endsection