<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $data = 'Laravel JWT Api';
    return $data;
});
