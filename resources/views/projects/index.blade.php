@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text_xl my_3">Projects</h1>

    <div class="container p_3 bc_milky rounded_3 shadow_1">
        <ul class="w100 no_list_style pb_3 flex_row flex_wrap">
            @forelse ($projects as $project)
            <div class="w25 mt_3 ">
                <li class="p_1 bc_white rounded_1 shadow_1 mx_1 h100 ">
                    <a class="no_decoration text_lg" href="{{$project->path()}}">{{$project->title}}</a>
                    <p class="pt_1 mt_1 border_t1">{{Str::limit($project->description, 100)}}</p>
                </li>
            </div>
            @empty
                <li>Not any project yet</li>
            @endforelse 
            
        </ul>
    </div>
</div>
@endsection
