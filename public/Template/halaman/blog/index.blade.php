@extends('index')

@section('main-content')
    @php
        use Illuminate\Support\Facades\Route;

        // ====== FALLBACK (hapus nanti saat sudah ada controller) ======
        if (!isset($posts)) {
            $posts = collect([
                (object) [
                    'slug' => 'contoh-berita-1',
                    'thumb_url' => asset('assets/img/placeholder/news.jpg'),
                    'published_at' => \Carbon\Carbon::parse('2025-03-01'),
                    'created_at' => \Carbon\Carbon::parse('2025-03-01'),
                    'author' => (object) ['name' => 'Admin'],
                    'comments_count' => 0,
                    'title' => 'PPDB MI Al-Hikmah Resmi Dibuka',
                    'excerpt' => 'Pendaftaran peserta didik baru tahun ajaran 2026/2027 telah dibuka.',
                    'content' => '…',
                ],
                (object) [
                    'slug' => 'contoh-berita-2',
                    'thumb_url' => asset('assets/img/placeholder/news.jpg'),
                    'published_at' => \Carbon\Carbon::parse('2025-02-10'),
                    'created_at' => \Carbon\Carbon::parse('2025-02-10'),
                    'author' => (object) ['name' => 'Admin'],
                    'comments_count' => 2,
                    'title' => 'Kegiatan Literasi 15 Menit',
                    'excerpt' => 'Program literasi harian untuk membiasakan budaya membaca.',
                    'content' => '…',
                ],
                (object) [
                    'slug' => 'contoh-berita-3',
                    'thumb_url' => asset('assets/img/placeholder/news.jpg'),
                    'published_at' => \Carbon\Carbon::parse('2025-01-20'),
                    'created_at' => \Carbon\Carbon::parse('2025-01-20'),
                    'author' => (object) ['name' => 'Admin'],
                    'comments_count' => 1,
                    'title' => 'Juara 1 MTQ Tingkat Kecamatan',
                    'excerpt' => 'Selamat untuk ananda atas prestasi MTQ cabang tilawah.',
                    'content' => '…',
                ],
            ]);
        }

        if (!isset($categories)) {
            $categories = collect([
                (object) ['slug' => 'pengumuman', 'name' => 'Pengumuman', 'posts_count' => 1],
                (object) ['slug' => 'kegiatan', 'name' => 'Kegiatan', 'posts_count' => 2],
                (object) ['slug' => 'prestasi', 'name' => 'Prestasi', 'posts_count' => 1],
            ]);
        }

        if (!isset($tags)) {
            $tags = collect([
                (object) ['slug' => 'ppdb', 'name' => 'PPDB'],
                (object) ['slug' => 'literasi', 'name' => 'Literasi'],
                (object) ['slug' => 'prestasi', 'name' => 'Prestasi'],
            ]);
        }

        if (!isset($recent)) {
            $recent = $posts->take(3);
        }

        // Cek route; kalau belum ada, pakai fallback URL
        $hasIndex = Route::has('berita.index');
        $hasShow = Route::has('berita.show');
        $indexUrl = $hasIndex ? route('berita.index') : url()->current();
    @endphp

    <div class="blog-area py-120">
        <div class="container">
            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-7 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-bullhorn"></i> Berita & Informasi</span>
                        <h2 class="site-title">Kabar Terbaru <span>MI Al-Hikmah</span></h2>
                        <p>Dapatkan pengumuman sekolah, kegiatan siswa, dan informasi akademik terbaru.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- MAIN LIST --}}
                <div class="col-lg-8">
                    <div class="row" id="newsGrid">
                        @forelse($posts as $i => $post)
                            @php
                                $url = $hasShow ? route('berita.show', $post->slug) : '#';
                                $thumb = $post->thumb_url ?? asset('assets/img/placeholder/news.jpg');
                                $date =
                                    optional($post->published_at)->translatedFormat('d M Y') ??
                                    (optional($post->created_at)->translatedFormat('d M Y') ?? '');
                                $author = $post->author->name ?? 'Admin';
                                $cmt = $post->comments_count ?? 0;
                                $title = $post->title ?? '(Tanpa judul)';
                                $excerpt = \Illuminate\Support\Str::limit(
                                    strip_tags($post->excerpt ?? ($post->content ?? '')),
                                    110,
                                );
                            @endphp

                            <div class="col-md-6 col-lg-4">
                                <article class="blog-item wow fadeInUp" data-wow-delay=".{{ 25 + ($i % 4) * 25 }}s">
                                    <div class="blog-date"><i class="fal fa-calendar-alt"></i> {{ $date }}</div>

                                    <a class="blog-item-img" href="{{ $url }}"
                                        aria-label="Baca: {{ $title }}">
                                        <div class="ratio ratio-16x9">
                                            <img src="{{ $thumb }}" alt="{{ $title }}" loading="lazy"
                                                style="object-fit:cover;">
                                        </div>
                                    </a>

                                    <div class="blog-item-info">
                                        <div class="blog-item-meta">
                                            <ul>
                                                <li><span><i class="far fa-user-circle"></i> {{ $author }}</span></li>
                                                <li><span><i class="far fa-comments"></i> {{ $cmt }}
                                                        Komentar</span></li>
                                            </ul>
                                        </div>
                                        <h4 class="blog-title">
                                            <a href="{{ $url }}">{{ $title }}</a>
                                        </h4>
                                        <p class="mb-3">{{ $excerpt }}</p>
                                        <a class="theme-btn" href="{{ $url }}">Baca Selengkapnya<i
                                                class="fas fa-arrow-right-long"></i></a>
                                    </div>
                                </article>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">Belum ada berita untuk saat ini.</div>
                            </div>
                        @endforelse
                    </div>

                    {{-- Pagination: hanya tampil jika paginator --}}
                    @if ($posts instanceof \Illuminate\Pagination\AbstractPaginator)
                        <div class="mt-4">
                            {{ $posts->withQueryString()->links() }}
                        </div>
                    @endif
                </div>

                {{-- SIDEBAR --}}
                @include('halaman.blog.sideblog')
            </div>
        </div>
    </div>

    {{-- Style kecil supaya gambar grid konsisten --}}
    <style>
        .blog-item .ratio {
            border-radius: 8px;
            overflow: hidden;
        }

        .blog-item .blog-item-info .blog-title a {
            word-break: break-word;
        }
    </style>
@endsection
