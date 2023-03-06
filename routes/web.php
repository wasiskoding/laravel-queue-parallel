<?php

use App\Jobs\UpdateUserChunkJob;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Carbon\CarbonInterface;

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
    $started = now();

    // User::whereNull('email_verified_at')->get()->each(function($user){
    //     // send email
    //     sleep(1);
    //     $user->update([
    //         'email_verified_at' => now()
    //     ]);
    // }); // 1 menit

    User::whereNull('email_verified_at')->chunkById(10, function (Collection $users) {
        UpdateUserChunkJob::dispatch($users->pluck('id')->toArray());
    }); // 1 detik UI, 10 detik background

    $ended = now();
    
    return 'Done in : '.$ended->diffForHumans($started, CarbonInterface::DIFF_ABSOLUTE);
});
