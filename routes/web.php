<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SubgoalController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('lifedashboard.home', ['goals' => GoalController::getAllGoalsForCurrentUser()]);
})->middleware(['auth', 'verified'])->name('home');

// Goal CRUD routes
Route::get('/goals/add', [GoalController::class, 'add'])->middleware(['auth', 'verified', 'owner'])->name('addgoal');
Route::post('/goals/add', [GoalController::class, 'store'])->middleware(['auth', 'verified', 'owner'])->name('addgoal.post');
Route::get('/goals/{goal_id}/edit', [GoalController::class, 'update'])->middleware(['auth', 'verified', 'owner']);
Route::get('/goals/{goal_id}/delete', [GoalController::class, 'destroy'])->middleware(['auth', 'verified', 'owner']);

// Subgoal CRUD routes
Route::get('/goals/{goal_id}/subgoal/{subgoal_id}', [SubgoalController::class, 'update'])->middleware(['auth', 'verified', 'owner']);
Route::post('/goals/{goal_id}/subgoal/{subgoal_id}', [SubgoalController::class, 'store'])->middleware(['auth', 'verified', 'owner']);

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
