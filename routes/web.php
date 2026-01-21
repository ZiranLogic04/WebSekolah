<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\SiswaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\PengaturanSuratController;


// Auth (guest)
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/berita', [\App\Http\Controllers\PostController::class, 'index'])->name('posts.public.index');
Route::get('/berita/{slug}', [\App\Http\Controllers\PostController::class, 'show'])->name('posts.public.show');

Route::get('/profil/{slug}', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
Route::get('/gallery', [\App\Http\Controllers\GalleryController::class, 'index'])->name('gallery.index');
Route::get('/direktori-guru', [\App\Http\Controllers\GuruController::class, 'index'])->name('guru.public.index');
Route::get('/direktori-guru', [\App\Http\Controllers\GuruController::class, 'index'])->name('guru.public.index');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes - all authenticated users can access dashboard
Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile update removed as requested

});

// Admin only routes (full access)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Siswa management
    Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
    Route::get('/siswa/template', [SiswaController::class, 'downloadTemplate'])->name('siswa.template');
    Route::get('/kelas/list', [\App\Http\Controllers\Admin\KelasController::class, 'list'])->name('kelas.list');
    Route::resource('kelas', \App\Http\Controllers\Admin\KelasController::class)->except(['create', 'edit', 'show']);

    Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
    Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
    Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
    Route::post('/siswa/bulk-destroy', [SiswaController::class, 'bulkDestroy'])->name('siswa.bulk-destroy');
    Route::post('/siswa/bulk-update', [SiswaController::class, 'bulkUpdate'])->name('siswa.bulk-update');
    Route::post('/siswa/import/preview', [SiswaController::class, 'previewImport'])->name('siswa.import.preview');
    Route::post('/siswa/import', [SiswaController::class, 'import'])->name('siswa.import');
    Route::get('/siswa/{siswa}/document/{type}', [SiswaController::class, 'viewDocument'])->name('siswa.view-document');
    Route::get('/siswa/{siswa}/check-tagihan', [SiswaController::class, 'checkTagihan'])->name('siswa.check-tagihan');

    // Guru management
    Route::get('/guru/download-template', [GuruController::class, 'downloadTemplate'])->name('guru.download-template');
    Route::get('/guru/export', [GuruController::class, 'export'])->name('guru.export');
    Route::resource('guru', GuruController::class);
    Route::post('/guru/bulk-update', [GuruController::class, 'bulkUpdate'])->name('guru.bulk-update');
    Route::post('/guru/bulk-destroy', [GuruController::class, 'bulkDestroy'])->name('guru.bulk-destroy');
    Route::post('/guru/import', [GuruController::class, 'import'])->name('guru.import');

    // Surat Preview (accessible by admin)
    Route::post('/surat/preview-pdf', [\App\Http\Controllers\Admin\SuratController::class, 'previewStream'])->name('surat.preview-pdf');

    // Kenaikan Kelas & Alumni
    Route::get('/kenaikan-kelas', [\App\Http\Controllers\Admin\KenaikanKelasController::class, 'index'])->name('kenaikan-kelas.index');
    Route::post('/kenaikan-kelas', [\App\Http\Controllers\Admin\KenaikanKelasController::class, 'store'])->name('kenaikan-kelas.store');
    Route::get('/api/siswa-by-kelas', [\App\Http\Controllers\Admin\KenaikanKelasController::class, 'getSiswaByKelas']);
    Route::get('/alumni', [\App\Http\Controllers\Admin\AlumniController::class, 'index'])->name('alumni.index');

    // Data Lembaga
    Route::get('/lembaga', [\App\Http\Controllers\Admin\LembagaController::class, 'index'])->name('lembaga.index');
    Route::post('/lembaga', [\App\Http\Controllers\Admin\LembagaController::class, 'update'])->name('lembaga.update');

    // Tahun Ajaran
    Route::post('/tahun-ajaran', [\App\Http\Controllers\Admin\TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');

    // User Management (Kelola Akun)
    Route::resource('akun', \App\Http\Controllers\Admin\UserController::class)->parameters(['akun' => 'user'])->names('users');

    // Post/Berita
    Route::post('/posts/upload-image', [PostController::class, 'uploadImage'])->name('posts.upload-image');
    Route::patch('/posts/{post}/toggle-status', [PostController::class, 'toggleStatus'])->name('posts.toggle-status');
    Route::resource('posts', PostController::class);
    
    // Halaman (Kelola Menu Navbar)
    Route::get('/halaman', [\App\Http\Controllers\Admin\MenuNavbarController::class, 'index'])->name('halaman.index');
    Route::put('/halaman/{menu}', [\App\Http\Controllers\Admin\MenuNavbarController::class, 'update'])->name('halaman.update');
    Route::post('/halaman/bulk-update', [\App\Http\Controllers\Admin\MenuNavbarController::class, 'bulkUpdate'])->name('halaman.bulk-update');

    // Slider Beranda
    Route::get('/halaman/beranda', [\App\Http\Controllers\Admin\MenuNavbarController::class, 'homeSections'])->name('halaman.beranda');
    Route::resource('slider', \App\Http\Controllers\Admin\SliderController::class);
    
    // Features (4 poin di bawah slider)
    Route::get('/features', [\App\Http\Controllers\Admin\FeatureController::class, 'index'])->name('features.index');
    Route::put('/features', [\App\Http\Controllers\Admin\FeatureController::class, 'update'])->name('features.update');

    // About Section (Tentang Kami)
    Route::get('/about', [\App\Http\Controllers\Admin\SectionController::class, 'editAbout'])->name('about.edit');
    Route::post('/about', [\App\Http\Controllers\Admin\SectionController::class, 'updateAbout'])->name('about.update');

    // Counter Section (Statistik)
    Route::get('/counter', [\App\Http\Controllers\Admin\SectionController::class, 'editCounter'])->name('counter.edit');
    Route::post('/counter', [\App\Http\Controllers\Admin\SectionController::class, 'updateCounter'])->name('counter.update');

    // Program Section (Header Only)
    Route::get('/program', [\App\Http\Controllers\Admin\SectionController::class, 'editProgram'])->name('program.edit');
    Route::post('/program', [\App\Http\Controllers\Admin\SectionController::class, 'updateProgram'])->name('program.update');

    // Video Section (Profil Video)
    Route::get('/video', [\App\Http\Controllers\Admin\SectionController::class, 'editVideo'])->name('video.edit');
    Route::post('/video', [\App\Http\Controllers\Admin\SectionController::class, 'updateVideo'])->name('video.update');

    // Team Section (Guru & Tendik)
    Route::get('/team', [\App\Http\Controllers\Admin\SectionController::class, 'editTeam'])->name('team.edit');
    Route::post('/team', [\App\Http\Controllers\Admin\SectionController::class, 'updateTeam'])->name('team.update');

    // Choose Us Section
    Route::get('/choose', [\App\Http\Controllers\Admin\SectionController::class, 'editChoose'])->name('choose.edit');
    Route::post('/choose', [\App\Http\Controllers\Admin\SectionController::class, 'updateChoose'])->name('choose.update');

    // Portfolio Section
    Route::get('/portfolio', [\App\Http\Controllers\Admin\SectionController::class, 'editPortfolio'])->name('portfolio.edit');
    Route::post('/portfolio', [\App\Http\Controllers\Admin\SectionController::class, 'updatePortfolio'])->name('portfolio.update');

    // Event Section (Agenda)
    Route::get('/event', [\App\Http\Controllers\Admin\SectionController::class, 'editEvent'])->name('event.edit');
    Route::post('/event', [\App\Http\Controllers\Admin\SectionController::class, 'updateEvent'])->name('event.update');

    // Testimonial Section
    Route::get('/testimonial', [\App\Http\Controllers\Admin\SectionController::class, 'editTestimonial'])->name('testimonial.edit');
    Route::post('/testimonial', [\App\Http\Controllers\Admin\SectionController::class, 'updateTestimonial'])->name('testimonial.update');

    // Partner Section (Mitra)
    Route::get('/partner', [\App\Http\Controllers\Admin\SectionController::class, 'editPartner'])->name('partner.edit');
    Route::post('/partner', [\App\Http\Controllers\Admin\SectionController::class, 'updatePartner'])->name('partner.update');

    // Footer Links Section
    Route::get('/footer-links', [\App\Http\Controllers\Admin\SectionController::class, 'editFooterLinks'])->name('footer-links.edit');
    Route::post('/footer-links', [\App\Http\Controllers\Admin\SectionController::class, 'updateFooterLinks'])->name('footer-links.update');

    // Halaman Bertingkat (Menu Items)
    Route::get('/halaman/{menuNavbarId}/items', [\App\Http\Controllers\Admin\MenuItemController::class, 'index'])->name('menu-item.index');
    Route::get('/halaman/items/{id}', [\App\Http\Controllers\Admin\MenuItemController::class, 'show'])->name('menu-item.show');
    Route::post('/halaman/items', [\App\Http\Controllers\Admin\MenuItemController::class, 'store'])->name('menu-item.store');
    Route::put('/halaman/items/{id}', [\App\Http\Controllers\Admin\MenuItemController::class, 'update'])->name('menu-item.update');
    Route::delete('/halaman/items/{id}', [\App\Http\Controllers\Admin\MenuItemController::class, 'destroy'])->name('menu-item.destroy');
    
    // Content Editor
    Route::get('/halaman/items/{id}/content', [\App\Http\Controllers\Admin\MenuItemController::class, 'editContent'])->name('menu-item.edit-content');
    Route::put('/halaman/items/{id}/content', [\App\Http\Controllers\Admin\MenuItemController::class, 'updateContent'])->name('menu-item.update-content');

    // Mega Menu Editor (untuk menu Akademik dengan 4 kolom)
    Route::get('/halaman/{menuNavbarId}/mega-menu', [\App\Http\Controllers\Admin\MenuItemController::class, 'megaMenu'])->name('menu-item.mega-menu');
    Route::post('/halaman/{menuNavbarId}/mega-menu/item', [\App\Http\Controllers\Admin\MenuItemController::class, 'storeMegaMenuItem'])->name('menu-item.mega-menu.store');
    Route::put('/halaman/{menuNavbarId}/mega-menu/about', [\App\Http\Controllers\Admin\MenuItemController::class, 'updateMegaMenuAbout'])->name('menu-item.mega-menu.about');


    // Pages/Section Content Edit (Visi Misi, Sejarah, dll)

    Route::get('/pages/{key}/edit', [\App\Http\Controllers\Admin\PageController::class, 'edit'])->name('pages.edit');
    Route::put('/pages/{key}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update');
    Route::post('/pages/{key}', [\App\Http\Controllers\Admin\PageController::class, 'update'])->name('pages.update.post'); // For FormData file upload

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
    // Pengaturan Surat (New Settings Page)
    Route::get('/pengaturan-surat', [PengaturanSuratController::class, 'index'])->name('pengaturan-surat.index');
    Route::post('/pengaturan-surat/kop', [PengaturanSuratController::class, 'updateKop'])->name('pengaturan-surat.update-kop');

    // Surat (Buat Surat & Drafts)
    Route::resource('surat', \App\Http\Controllers\Admin\SuratController::class);
    Route::patch('/surat/{surat}/status', [\App\Http\Controllers\Admin\SuratController::class, 'markAsArchived'])->name('surat.status');
    Route::get('/surat/{surat}/print', [\App\Http\Controllers\Admin\SuratController::class, 'print'])->name('surat.print');
    
    Route::resource('letter-templates', \App\Http\Controllers\Admin\LetterTemplateController::class);
    
    // Surat Keluar (Archive View)
    Route::get('/surat-keluar', [\App\Http\Controllers\Admin\SuratController::class, 'outgoing'])->name('surat-keluar.index');

    // Surat Masuk
    Route::resource('surat-masuk', \App\Http\Controllers\Admin\SuratMasukController::class);
    Route::get('/surat-masuk/{suratMasuk}/view', [\App\Http\Controllers\Admin\SuratMasukController::class, 'viewDocument'])->name('surat-masuk.view');

    // Rekap Surat (Logbook)
    Route::get('/rekap-surat', [\App\Http\Controllers\Admin\RekapSuratController::class, 'index'])->name('rekap-surat.index');
    Route::get('/rekap-surat/print', [\App\Http\Controllers\Admin\RekapSuratController::class, 'print'])->name('rekap-surat.print');
});

// Staf Keuangan routes (Keuangan)
Route::middleware(['auth', 'role:admin,staf_keuangan'])->prefix('admin')->group(function () {
    Route::resource('jenis-tagihan', \App\Http\Controllers\Admin\JenisTagihanController::class);
    Route::resource('tagihan', \App\Http\Controllers\Admin\TagihanController::class);
    Route::get('tagihan/{id}/detail', [\App\Http\Controllers\Admin\TagihanController::class, 'detail'])->name('tagihan.detail');
    Route::get('piutang', [\App\Http\Controllers\Admin\PiutangController::class, 'index'])->name('piutang.index');
    Route::get('piutang/preview', [\App\Http\Controllers\Admin\PiutangController::class, 'preview'])->name('piutang.preview');
    Route::get('piutang/print', [\App\Http\Controllers\Admin\PiutangController::class, 'print'])->name('piutang.print');
    
    // Transaksi / Pembayaran
    Route::get('transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('api/siswa/{siswa}/tagihan', [\App\Http\Controllers\Admin\TransaksiController::class, 'getSiswaBill']);
    Route::get('api/siswa/{siswa}/transaksi', [\App\Http\Controllers\Admin\TransaksiController::class, 'getSiswaTransactions']);
    
    // Transaksi Manual (Consolidated)
    Route::get('transaksi-manual', [\App\Http\Controllers\Admin\UangSekolahController::class, 'transaksiManual'])->name('transaksi-manual.index');
    Route::post('transaksi-manual', [\App\Http\Controllers\Admin\UangSekolahController::class, 'storeManual'])->name('transaksi-manual.store');
    
    // Keuangan Sekolah (Rekap)
    Route::get('uang-sekolah/print', [\App\Http\Controllers\Admin\UangSekolahController::class, 'print'])->name('uang-sekolah.print');
    Route::resource('uang-sekolah', \App\Http\Controllers\Admin\UangSekolahController::class)->except(['create', 'edit', 'show', 'update']);
    
    // Pengeluaran (Consolidated)
    Route::get('pengeluaran', [\App\Http\Controllers\Admin\UangSekolahController::class, 'pengeluaran'])->name('pengeluaran.index');
    Route::post('pengeluaran', [\App\Http\Controllers\Admin\UangSekolahController::class, 'storePengeluaran'])->name('pengeluaran.store');
});
