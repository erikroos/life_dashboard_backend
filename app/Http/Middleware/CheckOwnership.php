<?php
namespace App\Http\Middleware;

use App\Models\Goal;
use App\Models\Subgoal;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->route('goal_id') != null) {
            $goal = Goal::where('id', $request->route('goal_id'))->first();
            if ($goal->user_id != Auth::id()) {
                return redirect('/');
            }
        }

        if ($request->route('subgoal_id') != null) {
            $subgoal = Subgoal::where('id', $request->route('subgoal_id'))->first();
            if ($subgoal->user_id != Auth::id()) {
                return redirect('/');
            }
        }

        return $next($request);
    }
}
