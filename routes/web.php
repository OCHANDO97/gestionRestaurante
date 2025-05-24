<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\InvoceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\RegisterUserController;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\EnsureUserHasRole;


Route::get('/', function () {
    return redirect('/login');

});


Route::get('/dashboard', function () {

    return Inertia::render('Dashboard');

})->middleware(['auth', 'verified'])->name('dashboard');



// EnsureUserHasRole se creo el middleware
Route::group(['middleware' => ['auth',EnsureUserHasRole::class.':crear_cuentas']], function () {

    Route::post('/custom-register', [RegisterUserController::class, 'store'])->name('register.store');
    Route::get('/custom-register', [RegisterUserController::class, 'create'])->name('register.create');

});

// EnsureUserHasRole se creo el middleware
Route::group(['middleware' => ['auth',EnsureUserHasRole::class.':importar_csv']], function () {

    Route::get('/gestion-datos',function() {
        return Inertia::render('ImportarExportar');
     })->name('data');

     // CATEGORIAS 
    Route::post('/categories/upload', [CategoryController::class, 'uploadCSV'])->name('categories.upload');
    Route::get('/export-categorias', [CategoryController::class, 'exportCSV'])->name('categorias.export');
    // PRODUCTOS
    Route::get('/export-productos', [ProductController::class, 'exportCSV'])->name('producto.export');
    Route::post('/products/upload', [ProductController::class, 'uploadCSV'])->name('producto.upload');
    // MESAS
    Route::get('/export-mesas', [TableController::class, 'exportCSV'])->name('table.export');
    Route::post('/mesas/upload', [TableController::class, 'uploadCSV'])->name('table.upload');

});

// EnsureUserHasRole se creo el middleware
Route::group(['middleware' => ['auth',EnsureUserHasRole::class.':crear_comanda']], function () {

    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('mesas', TableController::class);
    
    Route::post('redirect-to-mesa', [TableController::class,'redirectToTable'])->name('redirect.to.mesa');
    Route::post('factura/mesa/{mesa}/addProducto/{producto}', [InvoceController::class,'addProductoToFacturaOnMesa'])->name('addProductoToFacturaOnMesa');
    Route::post('factura/mesa/{mesa}/removeProducto/{producto}', [InvoceController::class, 'removeProductoFromFacturaOnMesa'])->name('removeProductoFromFacturaOnMesa');

});

Route::group(['middleware' => ['auth',EnsureUserHasRole::class.':ver_usuarios']], function () {

    Route::get('/users/{user}/perfil', [UserController::class, 'perfil'])->name('users.perfil');
    Route::post('/users/{user}/permissions', [UserController::class, 'updatePermissions'])->name('users.permissions.update');
    Route::resource('user', UserController::class);

});

Route::group(['middleware' => ['auth',EnsureUserHasRole::class.':ver_facturas']], function () {
    
    Route::get('/invoces/search', [InvoceController::class, 'search'])->name('invoces.search');

    Route::resource('invoces', InvoceController::class);


});

Route::group(['middleware' => ['auth',EnsureUserHasRole::class.':ver_perfil']], function () {
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});




Route::middleware(['auth', 'verified'])->group(function(){

});

require __DIR__.'/auth.php';
