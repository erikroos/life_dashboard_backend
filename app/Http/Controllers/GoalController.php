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

class GoalController extends Controller
{
    public static function getAllGoalsForCurrentUser() : Collection {
        $goals = Goal::where('user_id', Auth::id())->get();
        foreach ($goals as $goal) {
            $goal->subgoals = Subgoal::where('goal_id', $goal->id)->get();
            $goal->nr_subgoals = 0;
            $goal->nr_subgoals_overdue = 0;
            $goal->status = "ok";
            if (sizeof($goal->subgoals) > 0) {
                foreach ($goal->subgoals as $subgoal) {
                    $goal->nr_subgoals++;
                    $subgoal->status = "ok";
                    $next_check_date = new DateTime($subgoal->next_check);
                    $current_date = new DateTime();
                    if ($next_check_date <= $current_date) {
                        $goal->nr_subgoals_overdue++;
                        $subgoal->status = "overdue";
                    }
                }
                if ($goal->nr_subgoals_overdue / (double)$goal->nr_subgoals > $goal->ok_percentage / 100.0) {
                    $goal->status = "overdue";
                }
            }
        }
        return $goals;
    }

    /**
     * Handle an incoming new or edit goal form.
     *
     * @throws ValidationException
     */
    public function store(Request $request) : RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'ok_percentage' => ['required', 'integer', 'numeric', 'min:0', 'max:100'],
        ]);

        if ($request->id == 0) {
            // New goal
            $goal = Goal::create([
                'name' => $request->name,
                'ok_percentage' => $request->ok_percentage,
                'user_id' => Auth::id(),
            ]);
            $message = 'Goal added successfully';
        } else {
            // Edit goal
            $goal = Goal::find($request->id);
            $goal->update([
                'name' => $request->name,
                'ok_percentage' => $request->ok_percentage,
                'user_id' => Auth::id(),
            ]);
            $message = 'Goal edited successfully';
        }
        $this->addSubgoalsToGoal($goal->id, $request);

        return redirect(RouteServiceProvider::HOME)->with('success', $message);
    }

    private function addSubgoalsToGoal($goal_id, $request) {
        // Delete existing
        Subgoal::where('goal_id', $goal_id)->delete();
        // Make and add new subgoals
        $i = 1;
        while ($i < 21) { // TODO
            if (!isset($request->{"subgoalName" . $i})) {
                $i++;
                continue;
            }
            Subgoal::create([
                'name' => $request->{"subgoalName" . $i},
                'next_check' => $request->{"subgoalDate" . $i},
                'goal_id' => $goal_id,
                'user_id' => Auth::id(),
            ]);
            $i++;
        }
    }

    public function add() : View {
        $goal = new Goal();
        $goal->id = 0;
        $goal->ok_percentage = 75;
        $goal->subgoals = [];
        return view('lifedashboard.addgoal', ['goal' => $goal]);
    }

    public function update($goal_id) : View {
        $goal = Goal::where('id', $goal_id)->first();
        $goal->subgoals = Subgoal::where('goal_id', $goal->id)->get();
        foreach ($goal->subgoals as $subgoal) {
            $subgoal->next_check = substr($subgoal->next_check, 0, 10);
        }
        return view('lifedashboard.addgoal', ['goal' => $goal]);
    }

    public function destroy($goal_id) : RedirectResponse {
        $goal = Goal::where('id', $goal_id)->first();
        $goal->delete();
        return redirect(RouteServiceProvider::HOME)->with('success', 'Goal removed successfully');
    }
}
