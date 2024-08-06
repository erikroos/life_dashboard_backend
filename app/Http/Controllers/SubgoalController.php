<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Goal;
use App\Models\Subgoal;
use App\Providers\RouteServiceProvider;
use \Datetime;

class SubgoalController extends Controller
{
    public function update($goal_id, $subgoal_id) : View {
        $subgoal = Subgoal::where('id', $subgoal_id)->first();
        $subgoal->next_check = substr($subgoal->next_check, 0, 10);
        return view('lifedashboard.subgoal', ['subgoal' => $subgoal]);
    }

    /**
     * Handle an incoming edit subgoal form.
     *
     * @throws ValidationException
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $subgoal = Subgoal::find($request->id);
        $subgoal->update([
            'name' => $request->name,
            'next_check' => $request->next_check,
            'user_id' => Auth::id(),
        ]);
        $message = 'Subgoal edited successfully';

        return redirect(RouteServiceProvider::HOME)->with('success', $message);
    }
}
