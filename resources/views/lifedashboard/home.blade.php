<x-lifedashboard-layout>
    <h2>My goals</h2>

    @foreach($goals as $goal)
        <p>
            <a href="{{ url('/goals' . '/' . $goal->id . '/edit') }}" class="{{ $goal->status }}">{{ $goal->name }}</a>
            <a href="{{ url('/goals' . '/' . $goal->id . '/delete') }}" class="btn btn-light">Delete</a>
        </p>

        <ul>
        @foreach($goal->subgoals as $subgoal)
            <li><a href="{{ url('/goals' . '/' . $goal->id . '/subgoal/' . $subgoal->id) }}" class="{{ $subgoal->status }}">{{ $subgoal->name }}</a> ({{ substr($subgoal->next_check, 0, 10) }})</li>
        @endforeach
        </ul>
    @endforeach

    <div class="spacer"></div>

    <p><a href="{{ route('addgoal') }}" class="btn btn-primary">Add a goal</a></p>
</x-lifedashboard-layout>
