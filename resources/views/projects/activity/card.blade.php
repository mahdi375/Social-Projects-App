
<h2 class="text_xl text_skiny mb_1">Latest Activities: </h2>
<ul class="no_list_style" style="padding-inline-start: 10px">
    @foreach ($activities as $activity)
        <li class="mt_05">
            @include("projects.activity.{$activity->description}", compact('activity'))
            <span class="text_mutted ml_1">{{$activity->created_at->diffForHumans()}}</span>
        </li>
    @endforeach
</ul>