<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TemaController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\ProcessController;
use App\Http\Controllers\AnggaranController;
use App\Http\Controllers\DasboardController;
use App\Http\Controllers\PenyelesaianController;
use App\Http\Controllers\Admin\User\UserController;
use App\Http\Controllers\Admin\Reject\RejectCpntroller;
use App\Http\Controllers\Admin\Otorization\OtorizartionController;



// Route::middleware(['AuthAccess'])->group(function () {

Route::middleware('AuthApi')->group(function () {

Route::controller(ProcessController::class)->group(function () {
    Route::get('/get-permasalahan', 'getPermasalahan')->name('get-permasalahan');
    Route::post('/get-permasalahan-id', 'getPermasalahanById')->name('get-permasalahan-by-id');
    Route::get('/get-permasalahan-by-tema', 'getPermasalahanByTema')->name('get-permasalahan-by-tema');
    Route::get('/get-rencana-aksi', 'getRenaksi')->name('get-renaksi');
    Route::post('/get-rencana-aksi-id', 'getRenaksiById')->name('get-renaksi-id');
    Route::get('/get-rencana-aksi-permasalahan', 'getRenaksiByPermasalahan')->name('get-renaksi-id-permasalahan');
});

Route::controller(PenyelesaianController::class)->group(function () {
    Route::get('/get-target-penyelesaian', 'getTargetPenyelesaian')->name('get-target-penyelesaian');
    Route::post('/get-target-penyelesaian-id', 'getTargetPenyelesaianById')->name('get-target-penyelesaian-id');
    Route::get('/get-realisasi-penyelesaian', 'getRealisasiPenyelesaian')->name('get-relasi-penyelesaian');
    Route::post('/get-realisasi-penyelesaian-id', 'getRealisasiPenyelesaianById')->name('get-relasi-penyelesaian-id');
    Route::get('/tes-getgd', 'GDGetFile')->name('tes-getgd');
});

Route::controller(AnggaranController::class)->group(function () {
    Route::get('/get-anggaran', 'getAnggaran')->name('get-anggaran');
    Route::post('/get-anggaran-id', 'getTargetAnggaranById')->name('get-anggaran-id');
    Route::get('/get-realisasi-anggaran', 'getRealisasiAnggaran')->name('get-relasi-anggaran');
    Route::post('/get-realisasi-anggaran-id', 'getRealisasiAnggaranById')->name('get-relasi-anggaran-id');
});

Route::controller(TemaController::class)->group(function () {
    Route::get('/get-tema', 'getType')->name('get-tema');
    Route::get('/get-tema-paginate', 'getPaginate')->name('get-tema-paginate');
    Route::get('/get-tema-id', 'getId')->name('get-tema-id');
    Route::post('/get-tema-id-first', 'geByIdFirst')->name('get-tema-id-first');
});

Route::controller(ClusterController::class)->group(function () {
    Route::get('/get-cluster', 'get')->name('get-cluster');
    Route::get('/get-cluster-paginate','getPaginate')->name('get-cluster-paginate');
    Route::post('/get-cluster-by-id','getById')->name('get-cluster-by-id');
});

Route::controller(DasboardController::class)->group(function () {
    Route::get('/get-count', 'getCount')->name('get-count');
    Route::get('/get-capaian-permasalahan', 'getRataCapaianPermasalahan')->name('get-rata-permasalahan');
    Route::get('/get-capaian-anggaran', 'getRataCapaianAnggaran')->name('get-capaian-anggaran');
    Route::get('/get-capaian-renaksi-penyelesaian', 'getRenaksiPenyelesaian')->name('get-capaian-renaksi-penyelesaian');
    Route::get('/get-capaian-renaksi-anggaran', 'getRenaksiAnggaran')->name('get-capaian-renaksi-anggaran');
    Route::get('/get-capaian-tw-penyelesaian', 'getTWpenyelesaian')->name('get-tw-penyelesaian');
    Route::get('/get-capaian-tw-anggaran', 'getTWAnggaran')->name('get-tw-anggaran');
});

Route::controller(OtorizartionController::class)->group(function () {
    Route::get('otorization-get-all','getAll');
    Route::get('otoruzation-get-by-user','getbyUserId');
   
});

Route::controller(RejectCpntroller::class)->group(function () {
    Route::get('reject-get-all','getAll');
    Route::get('reject-get-by-user','getbyUserId');
   
});

Route::controller(UserController::class)->group(function () {
    Route::get('/get-user-paginate','getPaginate')->name('get-user-paginate');
    Route::get('/get-user-by-id','getUserById');
    });
});