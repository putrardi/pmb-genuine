<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\ProgramStudiController;
use App\Http\Controllers\Admin\GelombangController;
use App\Http\Controllers\Calon\DashboardController;
use App\Http\Controllers\Calon\BiodataController;
use App\Http\Controllers\Calon\DokumenController;
use App\Http\Controllers\Calon\SubmitController;
use App\Http\Controllers\Verifikasi\VerifikasiController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminPendaftarController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::get('/post-login', function () {
    $u = auth()->user();
    if (!$u) return redirect()->route('login');

    return match ($u->role) {
        'admin', 'staff' => redirect()->route('admin.dashboard'),
        'calon_mahasiswa' => redirect()->route('pendaftaran.dashboard'),
        default => redirect('/'),
    };
})->middleware('auth')->name('post-login');


// registrasi calon mahasiswa (di halaman depan)
Route::post('/register-calon', [LandingController::class,'register'])->name('landing.register');

// login admin/staff (di halaman depan)
//Route::post('/admin-login', [LandingController::class,'adminLogin'])->name('landing.admin.login');

// >>> Tambahkan ini untuk form buat akun calon <<<
Route::post('/register-calon', [LandingController::class, 'register'])->name('register.calon');

// Login pengguna (admin/staff/calon yang sudah punya akun) – hanya halaman form
Route::get('/login-user', [AuthenticatedSessionController::class, 'create'])->name('login.user');

/** logout (umum) */
Route::post('/logout', function(){
    \Auth::logout(); request()->session()->invalidate(); request()->session()->regenerateToken();
    return redirect()->route('landing');
})->name('logout');

// placeholder dashboard (nanti kita isi di modul berikut)
Route::get('/dashboard-calon', fn()=>view('calon.dashboard'))
    ->middleware('auth')->name('pendaftaran.dashboard');

Route::get('/admin', fn()=>view('admin.dashboard'))
    ->middleware('auth')->name('admin.dashboard');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// routes/web.php (sementara, agar sidebar tidak error)
Route::middleware('auth')->group(function () {
  Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('prodi', ProgramStudiController::class)
        ->parameters(['prodi' => 'program_studi']);
});

Route::middleware(['auth','role:admin'])->get('/admin', function () {
    return view('admin.dashboard'); // ini yang extends layouts.admin
})->name('admin.dashboard');

Route::middleware(['auth','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('gelombang', GelombangController::class);
    });

Route::middleware(['auth','role:calon_mahasiswa'])->group(function () {
    Route::get('/dashboard-calon', [DashboardController::class, 'index'])->name('pendaftaran.dashboard');

    // Wizard Step 1: Biodata
    Route::get('/calon/biodata', [BiodataController::class, 'edit'])->name('calon.biodata.edit');
    Route::post('/calon/biodata', [BiodataController::class, 'update'])->name('calon.biodata.update');

    // Route::get('/calon/dokumen', ...)->name('calon.dokumen');
    Route::get('/calon/dokumen', [DokumenController::class, 'edit'])->name('calon.dokumen.edit');
    Route::post('/calon/dokumen', [DokumenController::class, 'update'])->name('calon.dokumen.update');

    // preview signed
    Route::get('/calon/dokumen/preview/{key}', [DokumenController::class, 'preview'])
        ->name('calon.dokumen.preview')
        ->middleware('signed');

    // hapus file
    Route::delete('/calon/dokumen/{key}', [DokumenController::class, 'destroy'])->name('calon.dokumen.destroy');

    // pilih gelombang
    Route::get('/calon/pilih-gelombang', [SubmitController::class, 'pilihGelombang'])->name('calon.pilih-gelombang');
    Route::post('/calon/pilih-gelombang', [SubmitController::class, 'simpanGelombang'])->name('calon.simpan-gelombang');

    // submit final
    Route::post('/calon/submit', [SubmitController::class, 'submitFinal'])->name('calon.submit');

    // ringkasan
    Route::get('/calon/ringkasan', [SubmitController::class, 'ringkasan'])->name('calon.ringkasan');

});

Route::middleware(['auth','role:staff,admin'])
    ->prefix('staff')
    ->name('staff.')
    ->group(function () {
        Route::get('/verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
        Route::get('/verifikasi/{pendaftaran}', [VerifikasiController::class, 'show'])->name('verifikasi.show');

        // Preview dokumen privat (tanpa signed), khusus staff/admin
        Route::get('/verifikasi/{pendaftaran}/preview/{key}', [VerifikasiController::class, 'previewDoc'])
            ->name('verifikasi.preview');

        // Aksi
        Route::post('/verifikasi/{pendaftaran}/verify', [VerifikasiController::class, 'verify'])->name('verifikasi.verify');
        Route::post('/verifikasi/{pendaftaran}/reject', [VerifikasiController::class, 'reject'])->name('verifikasi.reject');
    });

    Route::middleware(['auth','role:admin,staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // redirect root admin → dashboard
        Route::get('/', fn () => redirect()->route('admin.dashboard'))->name('home');

        // dashboard (punya variabel $gActive dkk dari controller)
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware(['auth','role:admin,staff'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/pendaftar', [AdminPendaftarController::class, 'index'])->name('pendaftar.index');
    });
    
require __DIR__.'/auth.php';
