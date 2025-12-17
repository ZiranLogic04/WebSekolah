<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;


// Auth (guest)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/berita', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.public.index');
Route::get('/berita/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.public.show');

Route::get('/profil/{slug}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes - all authenticated users can access dashboard
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile update - accessible by all authenticated users
    Route::get('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update'); 
    Route::put('/password', [\App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('password.update');
});

// Admin only routes (full access)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Siswa management
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class)->except(['create', 'edit', 'show']);
    Route::post('/siswa/kelas', [SiswaController::class, 'storeKelas'])->name('siswa.store-kelas');
    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('/siswa/bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulk-destroy');
    Route::post('/siswa/bulk-update', [SiswaController::class, 'bulkUpdate'])->name('siswa.bulk-update');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');

    // Guru management
    Route::get('/guru/download-template', [GuruController::class, 'downloadTemplate'])->name('guru.download-template');
    Route::get('/guru/export', [GuruController::class, 'export'])->name('guru.export');
    Route::resource('guru', GuruController::class);
    Route::post('/guru/bulk-update', [GuruController::class, 'bulkUpdate'])->name('guru.bulk-update');
    Route::post('/guru/bulk-destroy', [GuruController::class, 'bulkDestroy'])->name('guru.bulk-destroy');
    Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');

    // Data Lembaga
    Route::get('/lembaga', [\App\Http\Controllers\Admin\LembagaController::class, 'index'])->name('lembaga.index');
    Route::post('/lembaga', [\App\Http\Controllers\Admin\LembagaController::class, 'update'])->name('lembaga.update');

    // User Management (Kelola Akun)
    Route::resource('akun', \App\Http\Controllers\Admin\UserController::class)->parameters(['akun' => 'user'])->names('users');

    // Post/Berita
    Route::resource('posts', PostController::class);
    
    // Halaman (Homepage Sections)
    Route::get('/pages', [\App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
    Route::get('/pages/{section}/edit', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{section}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');


    // Gallery Management
    Route::get('/gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'index'])->name('admin.gallery.index');
    Route::post('/gallery', [\App\Http\Controllers\Admin\GalleryController::class, 'store'])->name('admin.gallery.store');
    Route::post('/gallery/bulk-destroy', [\App\Http\Controllers\Admin\GalleryController::class, 'bulkDestroy'])->name('admin.gallery.bulk-destroy');
    Route::post('/gallery/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'update'])->name('admin.gallery.update');
    Route::delete('/gallery/{gallery}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy'])->name('admin.gallery.destroy');
});

// Staf Administrasi routes (Surat-surat)
Route::middleware(['auth', 'role:admin,staf_administrasi'])->prefix('admin')->group(function () {
    // Pengaturan Surat
    Route::resource('surat', \App\Http\Controllers\Admin\SuratController::class)->except(['create', 'edit']);
    Route::patch('/surat/{surat}/status', [\App\Http\Controllers\Admin\SuratController::class, 'markAsArchived'])->name('surat.status');
    Route::get('/surat/{surat}/cetak', [\App\Http\Controllers\Admin\SuratController::class, 'show'])->name('surat.cetak');
    Route::resource('letter-templates', \App\Http\Controllers\Admin\LetterTemplateController::class);
    
    // Surat Keluar (Archive View)
    Route::get('/surat-keluar', [\App\Http\Controllers\Admin\SuratController::class, 'outgoing'])->name('surat-keluar.index');

    // Surat Masuk
    Route::resource('surat-masuk', \App\Http\Controllers\Admin\SuratMasukController::class);

    // Rekap Surat (Logbook)
    Route::get('/rekap-surat', [\App\Http\Controllers\Admin\RekapSuratController::class, 'index'])->name('rekap-surat.index');
    Route::get('/rekap-surat/print', [\App\Http\Controllers\Admin\RekapSuratController::class, 'print'])->name('rekap-surat.print');
});

// Staf Keuangan routes (Keuangan)
Route::middleware(['auth', 'role:admin,staf_keuangan'])->prefix('admin')->group(function () {
    Route::resource('jenis-tagihan', \App\Http\Controllers\Admin\JenisTagihanController::class);
    Route::resource('tagihan', \App\Http\Controllers\Admin\TagihanController::class);
    Route::get('piutang', [\App\Http\Controllers\Admin\PiutangController::class, 'index'])->name('piutang.index');
    
    // Transaksi / Pembayaran
    Route::get('transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('api/siswa/{siswa}/tagihan', [\App\Http\Controllers\Admin\TransaksiController::class, 'getSiswaBill']);
    Route::get('api/siswa/{siswa}/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'getSiswaTransactions']);
    
    // Transaksi Manual (Halaman Terpisah)
    Route::get('transaksi-manual', [\App\Http\Controllers\Admin\TransaksiManualController::class, 'index'])->name('transaksi-manual.index');
    Route::post('transaksi-manual', [\App\Http\Controllers\Admin\TransaksiManualController::class, 'store'])->name('transaksi-manual.store');
    
    // Keuangan Sekolah (Rekap)
    Route::get('uang-sekolah/print', [\App\Http\Controllers\Admin\UangSekolahController::class, 'print'])->name('uang-sekolah.print');
    Route::resource('uang-sekolah', \App\Http\Controllers\Admin\UangSekolahController::class)->except(['create', 'edit', 'show', 'update']);
    
    // Pengeluaran (Halaman Terpisah)
    Route::get('pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'index'])->name('pengeluaran.index');
    Route::post('pengeluaran', [\App\Http\Controllers\Admin\PengeluaranController::class, 'store'])->name('pengeluaran.store');
    Route::delete('pengeluaran/{pengeluaran}', [\App\Http\Controllers\Admin\PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');
});