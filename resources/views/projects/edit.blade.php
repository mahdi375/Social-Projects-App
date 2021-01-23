@extends('layouts.app')

@section('content')
<div class="conteiner p_4">

    <div class="mt_4 bc_milky w100 p_4 shadow_2 rounded_3">
        <div class="w50">
            <h3 class="my_2 pt_2 border_t1">Edit Project</h3>
            <form class="flex_column w100" action="{{$project->path()}}" method="POST">
                @method('PATCH')
                <?php $btn = 'Edit Project'; ?>
                @include('projects.form', compact('project', 'btn'))
        </div>
    </div>
            
</div>
@endsection
