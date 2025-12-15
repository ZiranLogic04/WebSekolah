<div class="col-lg-4">
    <aside class="sidebar">
        {{-- Search --}}
        <div class="widget search">
            <h5 class="widget-title">Pencarian</h5>
            <form class="search-form" method="get" action="{{ $indexUrl }}">
                <input type="text" class="form-control" name="q" value="{{ request('q') }}"
                    placeholder="Cari berita, pengumuman…">
                <button type="submit" aria-label="Cari"><i class="fa fa-search"></i></button>
            </form>
        </div>

        {{-- Kategori --}}
        <div class="widget category">
            <h5 class="widget-title">Kategori</h5>
            <div class="category-list">
                @foreach ($categories ?? [] as $cat)
                    @php
                        $catUrl = $hasIndex
                            ? route('berita.index', ['kategori' => $cat->slug] + request()->except('page'))
                            : '#';
                    @endphp
                    <a href="{{ $catUrl }}"
                        class="{{ request('kategori') === ($cat->slug ?? '') ? 'active' : '' }}">
                        <i class="fa fa-arrow-right"></i>{{ $cat->name ?? 'Kategori' }}
                        <span>({{ $cat->posts_count ?? 0 }})</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Recent Posts --}}
        <div class="widget recent-post">
            <h5 class="widget-title">Terbaru</h5>
            @foreach ($recent ?? [] as $rp)
                @php
                    $rpUrl = $hasShow ? route('berita.show', $rp->slug) : '#';
                    $rpThumb = $rp->thumb_url ?? asset('assets/img/placeholder/news-sm.jpg');
                    $rpDate =
                        optional($rp->published_at)->translatedFormat('d M Y') ??
                        (optional($rp->created_at)->translatedFormat('d M Y') ?? '');
                @endphp
                <div class="recent-post-single">
                    <a class="recent-post-img" href="{{ $rpUrl }}">
                        <img src="{{ $rpThumb }}" alt="{{ $rp->title ?? '' }}" loading="lazy">
                    </a>
                    <div class="recent-post-bio">
                        <h6><a
                                href="{{ $rpUrl }}">{{ \Illuminate\Support\Str::limit($rp->title ?? '', 60) }}</a>
                        </h6>
                        <span><i class="far fa-clock"></i>{{ $rpDate }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Social --}}
        <div class="widget social-share">
            <h5 class="widget-title">Ikuti Kami</h5>
            <div class="social-share-link">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-whatsapp"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>

        {{-- Tags --}}
        <div class="widget sidebar-tag">
            <h5 class="widget-title">Tag Populer</h5>
            <div class="tag-list">
                @foreach ($tags ?? [] as $tag)
                    @php
                        $tagUrl = $hasIndex
                            ? route('berita.index', ['tag' => $tag->slug] + request()->except('page'))
                            : '#';
                    @endphp
                    <a href="{{ $tagUrl }}">{{ $tag->name ?? 'Tag' }}</a>
                @endforeach
            </div>
        </div>
    </aside>
</div>
