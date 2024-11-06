<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\NavigationController;
use App\Http\Controllers\PenyelesaianController;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Reject\RejectCpntroller;
use App\Http\Controllers\Admin\Otorization\OtorizartionController;

Route::get('/', function () {
    return view('welcome');
});
Route::controller(NavigationController::class)->group(function () {
    Route::get('/login', 'LoginView')->name('login');
})->middleware(['AuthLoginBlocked']);

Route::controller(ProcessController::class)->group(function () {
    Route::post('/login-process', 'LoginProccess')->name('login-process');
})->middleware(['AuthLoginBlocked']);


Route::middleware(['AuthAccess'])->group(function () {

    Route::controller(NavigationController::class)->group(function () {
        Route::get('/dasboard', 'dashboardView')->name('dasboard');
        Route::get('/e-rb-view', 'e_rbSutingView')->name('e-rb');
        Route::get('/rencana-aksi', 'RencanaAksiSutingView')->name('rencana-aksi');
        Route::get('/target-penyelesaian', 'TargetPenyelesaianSutingView')->name('target-penyelesaian');
        Route::get('/target-anggaran', 'TargetAnggaranSutingView')->name('target-anggaran');
        Route::get('/realisasi-penyelesaian', 'RealisasiPenyelesaianSuntingView')->name('realisasi-penyelesaian');
        Route::get('/realisasi-anggaran', 'RelisasiAnggaranSuntingView')->name('realisasi-anggaran');
        Route::get('/evaluasi','EvaluatedUserView')->name('evaluasi');

        //admin navigation
        Route::get('/admin-dasboard','DasboardAdminView')->name('admin-dasboard');
        Route::get('/tema-dasboard','TemaAdminView')->name('tema-dasboard');
        Route::get('/cluster-dasboard','ClusterAdminView')->name('cluster-dasboard');
        Route::get('/user-dasboard','UserdAdminView')->name('user-dasboard');
        Route::get('/evaluasi-dasboard','EvaluatedAdminView')->name('evaluasi-dasboard');
    });

    Route::controller(ProcessController::class)->group(function () {

        Route::post('/permasalahan-process', 'createPermasalahan')->name('permasalahan-process');
        Route::post('/permasalahan-edit-prosess', 'updatePermsalahan')->name('permasalahan-edit-prosess');
        Route::post('/permasalahan-delete-prosess', 'deletePermasalahan')->name('delete-permasalahan');
        Route::post('/renaksi-process', 'createRenaksi')->name('reknaksi-prosess');
        Route::post('/renaksi-edit-process', 'updateRenaksi')->name('renaksi-edit-prosess');
        Route::post('/renaksi-delete-process', 'deleteRenaksi')->name('renaksi-delete-process');
        Route::post('/export-excel', 'exportExcel')->name('export-excel');
        Route::post('/logout',  'logout')->name('logout');
    });


    Route::controller(PenyelesaianController::class)->group(function () {
        Route::post('/target-penyelesaian-create', 'createTargetPenyelesaian')->name('target-penyelesaian-create');
        Route::post('/target-penyelesaian-edit', 'updateTargetPenyelesaian')->name('target-penyelesaian-edit');
        Route::post('/target-penyelesaian-delete', 'deleteTargetPenyelesaian')->name('target-penyelesaian-delete');
        Route::post('/realisasi-penyelesaian-create', 'createRealisasiPenyelesaian')->name('realisasi-penyelesaian-create');
        Route::post('/realisasi-penyelesaian-edit', 'updateRealisasiPenyelesaian')->name('realisasi-penyelesaian-edit');
        Route::post('/realisasi-penyelesaian-delete', 'deleteRealisasiPenyelesaian')->name('realisasi-penyelesaian-delete');
    });

    Route::controller(AnggaranController::class)->group(function () {
        Route::post('/target-anggaran-create', 'createTargetAnggaran')->name('target-anggaran-create');
        Route::post('/target-anggaran-edit', 'updateTargetAnggaran')->name('target-anggaran-edit');
        Route::post('/target-anggaran-delete', 'deleteTargetAnggaran')->name('target-anggaran-delete');
        Route::post('/realisasi-anggaran-create', 'createRealisasiAnggaran')->name('realisasi-anggaran-create');
        Route::post('/realisasi-anggaran-edit', 'updateRealisasiAnggaran')->name('realisasi-anggaran-edit');
        Route::post('/realisasi-anggaran-delete', 'deleteRealisasiAnggaran')->name('realisasi-anggaran-delete');
    });

    Route::controller(OtorizartionController::class)->group(function () {
        Route::post('otorization-create','setOtorization')->name('otorization-create');
        Route::post('otorization-update','update')->name('otorization-update');
        Route::post('otorization-delete','delete')->name('otorization-delete');
    });

    Route::controller(RejectCpntroller::class)->group(function () {
        Route::post('reject-create','setOtorization')->name('reject-create');
        Route::post('reject-update','update')->name('reject-update');
        Route::post('reject-delete','delete')->name('reject-delete');
        Route::get('export-approve','exportExcelApprove')->name('export-approve');
    });

    Route::controller(UserController::class)->group(function () {
        Route::post('user-create','create')->name('user-create');
        Route::post('user-update','update')->name('user-update');
        Route::post('user-delete','delete')->name('user-delete');
        Route::post('user-reset','reset')->name('user-reset');
    });

    Route::controller(TemaController::class)->group(function () {
        Route::post('tema-create','create')->name('tema-create');
        Route::post('tema-update','update')->name('tema-update');
        Route::post('tema-delete','delete')->name('tema-delete');
    });

    Route::controller(ClusterController::class)->group(function () {
        Route::post('cluster-create','create')->name('cluster-create');
        Route::post('cluster-update','update')->name('cluster-update');
        Route::post('cluster-delete','delete')->name('cluster-delete');
    });
});