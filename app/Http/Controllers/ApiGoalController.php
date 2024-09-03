<?php
namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use App\Models\Goal;
use App\Models\Subgoal;
use App\Providers\RouteServiceProvider;
use \Datetime;

class ApiGoalController extends Controller implements IGoalController
{
    public static function getAllGoalsForCurrentUser() : JsonResponse {
        // TODO:
        //$goals = Goal::where('user_id', Auth::id())->get();
        $goals = Goal::all();

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
        return response()->json($goals);
    }
}
