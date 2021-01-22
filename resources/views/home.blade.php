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
                @csrf
                <div >
                    <label for="title">Project Tilte</label><br>
                    <input class="mt_1 shadow_1 h2em rounded_1 w50 border_1" type="text" name="title" id="title">
                </div>

                <div class="mt_2">
                    <label for="description">Project Description</label><br>
                    <textarea class="mt_1 mb_2 rounded_1 shadow_1 w50 border_1" name="description" id="description" cols="30" rows="10"></textarea>
                </div>
                <div>
                    <label for="notes">Project Notes</label><br>
                    <textarea name="notes" id="notes" cols="30" rows="10"
                    class="w50 rounded_1 mt_1 shadow_1 p_1"
                    ></textarea>    
                </div>
                <div>
                    <button class="btn_blue border_1 py_1 px_4 rounded_1 ml_4 mt_3 ">Add Project</button>
                </div>
            </form>
        </div>
    </div>
            
</div>
@endsection
