<x-lifedashboard-layout>
    <form method="POST" action="/goals/{{$subgoal->goal_id}}/subgoal/{{$subgoal->id}}">
        @csrf

        <input type="hidden" name="id" value="{{ $subgoal->id }}">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" value="{{ $subgoal->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="next_check" class="form-label">Next check date</label>
            <input type="date" name="next_check" value="{{ $subgoal->next_check }}" class="form-control">
        </div>

        <input type="submit" value="Save" class="btn btn-primary">
        <a href="{{url()->previous()}}" class="btn btn-secondary">Cancel</a>
    </form>
</x-lifedashboard-layout>