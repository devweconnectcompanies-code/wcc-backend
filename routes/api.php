<?php
//
//use App\Http\Controllers\Auth\AuthController;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Route;
//
//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');
//

foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/api')) as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        require $file->getPathname();
    }
}
