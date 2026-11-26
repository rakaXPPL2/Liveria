<?php

use App\Http\Controllers\Api\DesignApiController;
use Illuminate\Support\Facades\Route;

Route::get('/designs', [DesignApiController::class, 'index']);
