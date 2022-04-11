    <?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Api\V1\FarmGoogleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::get("/login", [HomeController::class, 'index'])->name('login');

Route::get('/farm_google/auth_redirect', [FarmGoogleController::class, 'google_redirect']);

Route::get('/{any}', [HomeController::class, 'index'])->where('any', '.*');

