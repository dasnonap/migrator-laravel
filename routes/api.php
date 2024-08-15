<?php

use App\Http\Migration\Controllers\MigrationRecordsController;
use App\Http\Migration\Controllers\MigrateRecordController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Migration Records Controller
Route::controller(MigrationRecordsController::class)->group(function () {
    // Create Migration Record
    Route::post('migrations/create', 'create')->name('migrations.create');
});

// MigrateRecordController
Route::controller(MigrateRecordController::class)->group(function () {
    // Manipulate Migration
    Route::post('migrations/migrate', 'migrate')->name('migrations.migrate');
});

Route::get('migrations', function (Request $request) {
    return $request->user();
});
