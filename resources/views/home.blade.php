@extends('layouts.app')

@section('content')
<div class="conteiner p_4">
    <h1 class="text_xl">Dashboard</h1>

    <div class="mt_4 bc_milky w100 p_4 shadow_2 rounded_3">
        @if (session('status'))
            <div role="alert">
                {{ session('status') }}
            </div>
        @endif

        <span>Welcome to Whiteboard website :)</span>
    </div>
            
</div>
@endsection
