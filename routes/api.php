<?php

use App\Http\Migration\Controllers\MigrationRecordsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Migration Records Controller
Route::controller(MigrationRecordsController::class)->group(function () {
    // Create Migration Record
    Route::post('migrations/create', 'create')->name('migrations.create');
});

Route::get('migrations', function (Request $request) {
    return $request->user();
});
