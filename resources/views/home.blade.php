@extends('layouts.app')

@section('content')
<div class="conteiner p_4">

    <div class="mt_4 bc_milky w100 p_4 shadow_2 rounded_3">
        @if (session('status'))
            <div role="alert">
                {{ session('status') }}
            </div>
        @endif

        <span class="text_xl">Welcome to Whiteboard website :)</span>
        <div class="w50">
            <h3 class="my_2 pt_2 border_t1">Add New Project</h3>
            <form class="flex_column w100" action="{{route('ProjectsStore')}}" method="POST">
            @include('projects.form')
        </div>
    </div>
            
</div>
@endsection
