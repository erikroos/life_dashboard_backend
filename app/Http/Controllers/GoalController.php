<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use App\Models\Goal;
use App\Models\Subgoal;

class GoalController extends Controller
{
    public static function getAllGoalsForCurrentUser() : Collection {
        $goals = Goal::where('user_id', Auth::id())->get();
        foreach ($goals as $goal) {
            $goal->subgoals = Subgoal::where('goal_id', $goal->id)->get();
        }
        return $goals;
    }
}
