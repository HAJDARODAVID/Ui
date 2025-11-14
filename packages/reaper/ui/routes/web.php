<?php

use Illuminate\Support\Facades\Route;
use Reaper\Ui\Http\Controllers\TestController;

Route::get('test', [TestController::class, 'index']);
