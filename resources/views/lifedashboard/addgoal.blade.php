<x-lifedashboard-layout>
    @if ($goal->id > 0)
    <h1>Edit goal {{ $goal->name }}</h1>
    @else
    <h1>Add a goal</h1>
    @endif

    <form method="POST" action="{{ route('addgoal.post') }}">
        @csrf

        <input type="hidden" name="id" value="{{ $goal->id }}">

        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" name="name" value="{{ $goal->name }}" class="form-control">
        </div>

        <div class="mb-3">
            <label for="ok_percentage" class="form-label">Percentage overdue needed for alarm</label>
            <input type="number" name="ok_percentage" value="{{ $goal->ok_percentage }}" class="form-control" min="0" max="100">
        </div>

        <div class="mb-3">
            <label>Subgoals</label>
        </div>
        <div class="mb-3" id="subgoalContainer">
            @if ($goal->id > 0)
                @php($i = 1)
                @foreach ($goal->subgoals as $subgoal)
                    <div class="inputWrapper">
                        <input type="text" class="form-control" name="subgoalName{{$i}}" value="{{$subgoal->name}}"><input class="form-control" type="date" name="subgoalDate{{$i}}" value="{{$subgoal->next_check}}"><button class="btn btn-light removeButton">Remove</button>
                    </div>
                    @php($i++)
                @endforeach
            @else
                <div class="inputWrapper">
                    <input type="text" class="form-control" name="subgoalName1"><input class="form-control" type="date" name="subgoalDate1"><button class="btn btn-light removeButton">Remove</button>
                </div>
            @endif
        </div>
        <div class="mb-3">
            <button class="btn btn-light" id="addSubgoalButton">Add subgoal</button>
        </div>

        <input type="submit" value="Save" class="btn btn-primary">
        <a href="{{url()->previous()}}" class="btn btn-secondary">Cancel</a>
    </form>

    <script>
    let removeButtons = document.getElementsByClassName("removeButton");
    for (var i = 0; i < removeButtons.length; i++) {
        removeButtons[i].addEventListener('click', function(event) {
            event.preventDefault();
            event.target.parentNode.remove();
        });
    }

    document.getElementById('addSubgoalButton').addEventListener('click', function(event) {
        event.preventDefault();

        var inputContainer = document.getElementById('subgoalContainer');
        var newInputWrapper = document.createElement('div');
        newInputWrapper.classList.add('inputWrapper');

        var newInputName = document.createElement('input');
        newInputName.type = 'text';
        newInputName.classList.add('form-control');
        newInputName.name = 'subgoalName' + (subgoalContainer.children.length + 1);
        newInputWrapper.appendChild(newInputName);

        var newInputDate = document.createElement('input');
        newInputDate.type = 'date';
        newInputDate.classList.add('form-control');
        newInputDate.name = 'subgoalDate' + (subgoalContainer.children.length + 1);
        newInputWrapper.appendChild(newInputDate);

        var newButton = document.createElement('button');
        newButton.textContent = 'Remove';
        newButton.classList.add('btn');
        newButton.classList.add('btn-light');
        newButton.classList.add('removeButton');
        newButton.addEventListener('click', function(event) {
            event.preventDefault();
            event.target.parentNode.remove();
        });
        newInputWrapper.appendChild(newButton);

        inputContainer.appendChild(newInputWrapper);
    });
    </script>
</x-lifedashboard-layout>
