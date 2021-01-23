@csrf
    <div >
        <label for="title">Project Tilte</label><br>
        <input
            class="mt_1 shadow_1 h2em rounded_1 w50 border_1"
            type="text"
            name="title"
            id="title"
            value="{{$project->title ?? ''}}"
        >
    </div>

    <div class="mt_2">
        <label for="description">Project Description</label><br>
        <textarea
            class="mt_1 mb_2 rounded_1 shadow_1 w50 border_1"
            name="description"
            id="description"
            cols="30"
            rows="10"
        >{{$project->description ?? ''}}</textarea>
    </div>
    <div>
        <label for="notes">Project Notes</label><br>
        <textarea name="notes" id="notes" cols="30" rows="10"
        class="w50 rounded_1 mt_1 shadow_1 p_1"
        >{{$project->notes ?? ''}}</textarea>    
    </div>
    <div>
        <button class="btn_blue border_1 py_1 px_4 rounded_1 ml_4 mt_3 "
        >{{ $btn ?? 'Add Project'}}</button>
    </div>
</form>