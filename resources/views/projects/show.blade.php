@extends('layouts.app')

@section('content')
<div class="container">
    <div class="container p_3 bc_milky rounded_3 shadow_1 flex_row justify_between">
        <div class="w75">
            <h1 id="title" class="text_xl mb_2">{{$project->title}}</h1>

            <p id="description" class="text_lg ml_3">{{$project->description}}</p> 
        </div>

        <div class="mt_3  mr_4">
            <a href="{{ $project->path().'/edit'}}" class="no_decoration btn_orange py_1 px_3 rounded_1">Edit</a>
        </div>
    </div>

    <div id="tasks" class="container p_3 bc_milky rounded_3 mt_3 shadow_1">
        <h2 class="text_xl text_skiny mb_1">Project Tasks: </h2>
        <ul class="no_list_style">
            @foreach ($project->tasks as $task)
                <li class="w50">
                    <form class="flex_row justify_between bc_white w100 h100 px_2 border_1 rounded_1 text_lg my_1" action="{{$task->path()}}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div>
                            <input class="no_border bc_inherit text_lg py_1 {{$task->wasChecked() ? 'text_mutted' : ''}}" type="text" name="body" value="{{$task->body}}">
                        </div>
                        <div class="py_1 pr_2">
                            <input type="checkbox" name="checked" id="" value="checked" {{$task->wasChecked() ? 'checked' : ''}} onChange="this.form.submit()">
                        </div>
                    </form>
                </li>
            @endforeach
                <li>
                    <form method="POST" action="{{$project->path().'/tasks'}}">
                        @csrf
                        <input class="w50 shadow_1 py_2 border_1 p_1 text_lg rounded_1" type="text" name="body" placeholder="Add New Task" id="">
                    </form>
                </li>
        </ul>
    </div>

    <div id="additions" class="flex_row container justify_between p_3 bc_milky rounded_3 mt_3 shadow_1">
        
        <div id="notes" class="w75">
            <h2 class="text_xl text_skiny mb_1">Project Notes: </h2>
            <form action="{{$project->path().'/notes'}}" method="POST" >
                @csrf
                @method('PATCH')
                <textarea name="notes" placeholder="Add Notes ..." id="" cols="30" rows="10"
                    class="w100 ml_3 rounded_2 shadow_1 p_1"
                    >{{$project->notes}}</textarea><br>

                <button class="btn_blue border_1 py_1 px_4 rounded_1 ml_4 mt_3 "
                    >Edit Notes</button>
            </form>
        </div>

        <div id="activities" class="w25 ">
            @include('projects.activity.card', compact('project'))
        </div>

    </div>

</div>
@endsection