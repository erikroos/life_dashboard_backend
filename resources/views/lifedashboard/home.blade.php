<x-lifedashboard-layout>
    <h2>My goals</h2>

    @foreach($goals as $goal)
    <ul class="my-list list-group list-group-horizontal">
        <li class="first-col list-group-item">{{ $goal->name }}</li>
        <li class="btn-col list-group-item"><a href="{{ url('/goals' . '/' . $goal->id) }}" class="btn btn-light">Details</a></li>
    </ul>
    @endforeach

    <div class="spacer"></div>

    <p><a href="{{ route('addgoal') }}" class="btn btn-primary">Add a goal</a></p>
</x-lifedashboard-layout>
