@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text_xl my_3">Projects</h1>

    <div class="container p_3 bc_milky rounded_3 shadow_1">
        <ul class="no_list_style pb_3">
            @forelse ($projects as $project)
                <li class="mt_3"><a class="no_decoration text_lg" href="{{$project->path()}}">{{$project->title}}</a></li>
            @empty
                <li>Not any project yet</li>
            @endforelse 
            
        </ul>
    </div>
</div>
@endsection
