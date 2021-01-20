@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text_xl my_3">{{$project->title}}</h1>

    <div class="container p_3 bc_milky rounded_3 shadow_1">
        <p class="text_lg">{{$project->description}}</p>
    </div>

    <div class="container p_3 bc_milky rounded_3 mt_3 shadow_1">
        <ul>
            @foreach ($project->tasks as $task)
                <li>{{$task->body}}</li>
            @endforeach
        </ul>
    </div>

</div>
@endsection