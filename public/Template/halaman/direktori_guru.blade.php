@extends('index')

@section('main-content')
    <div class="py-120">
        <div class="container">

            {{-- Heading --}}
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="site-heading text-center">
                        <span class="site-title-tagline"><i class="fa fa-chalkboard-teacher"></i> GTK</span>
                        <h2 class="site-title">Direktori <span>Guru & Tendik</span></h2>
                        <p>Temukan guru dan tenaga kependidikan MI Al-Hikmah beserta bidang ajarnya.</p>
                    </div>
                </div>
            </div>

            {{-- Controls: Search & Filters --}}
            @php
                // opsi filter (bisa dioverride dari controller)
                $subjects = $subjects ?? [
                    'Al-Qur’an Hadits',
                    'Akidah Akhlak',
                    'Fikih',
                    'SKI',
                    'Bahasa Arab',
                    'BTQ/Tahfiz',
                    'Bahasa Indonesia',
                    'Matematika',
                    'IPAS',
                    'PJOK',
                    'SBdP',
                    'Muatan Lokal',
                    'Informatika/TIK',
                ];
                $roles = $roles ?? ['Kepala Madrasah', 'Guru Kelas', 'Guru Mapel', 'Tendik', 'Waka', 'Koordinator'];
            @endphp
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <input id="teacherSearch" type="search" class="form-control" placeholder="Cari nama atau mapel…">
                </div>
                <div class="col-md-3">
                    <select id="filterSubject" class="form-select">
                        <option value="">Semua Mapel</option>
                        @foreach ($subjects as $s)
                            <option value="{{ $s }}">{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select id="filterRole" class="form-select">
                        <option value="">Semua Jabatan</option>
                        @foreach ($roles as $r)
                            <option value="{{ $r }}">{{ $r }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Grid Guru --}}
            @php
                /* Struktur data:
        $teachers = [
          [
            'name'=>'Ahmad Soleh, S.Pd.I', 'role'=>'Kepala Madrasah',
            'subjects'=>['Manajerial','Keagamaan'], 'class'=>'-',
            'photo'=>'assets/img/team/kepala.jpg',
            'wa'=>'62812xxxxxxx','fb'=>null,'ig'=>null,'yt'=>null,
          ],
          [
            'name'=>'Ustadzah A', 'role'=>'Guru Kelas', 'subjects'=>['Kelas I','BTQ/Tahfiz'],
            'class'=>'I', 'photo'=>'assets/img/team/01.jpg', 'wa'=>null,'fb'=>null,'ig'=>null,'yt'=>null
          ],
          ...
        ];
      */
                $teachers = $teachers ?? [];
                $placeholder = 'assets/img/team/placeholder.jpg';
            @endphp

            <div id="teacherGrid" class="row">
                @forelse($teachers as $t)
                    @php
                        $photo = !empty($t['photo']) ? asset($t['photo']) : asset($placeholder);
                        $subs = $t['subjects'] ?? [];
                        $subs_text = implode(', ', $subs);
                        $data_subjects = strtolower(implode(' ', $subs));
                        $role = $t['role'] ?? '-';
                        $name = $t['name'] ?? '-';
                        $kelas = $t['class'] ?? '-';
                        $wa = $t['wa'] ?? null;
                        $fb = $t['fb'] ?? null;
                        $ig = $t['ig'] ?? null;
                        $yt = $t['yt'] ?? null;
                        $slug = \Illuminate\Support\Str::slug($name);
                    @endphp
                    <div class="col-md-6 col-lg-3 teacher-card" data-name="{{ strtolower($name) }}"
                        data-role="{{ strtolower($role) }}" data-subjects="{{ $data_subjects }}">
                        <div class="team-item wow fadeInUp" data-wow-delay=".25s">
                            <div class="team-img">
                                <img src="{{ $photo }}" alt="{{ $name }}">
                            </div>

                            <div class="team-social">
                                @if ($fb)
                                    <a href="{{ $fb }}" target="_blank" rel="noopener"><i
                                            class="fab fa-facebook-f"></i></a>
                                @endif
                                @if ($wa)
                                    <a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener"><i
                                            class="fab fa-whatsapp"></i></a>
                                @endif
                                @if ($ig)
                                    <a href="{{ $ig }}" target="_blank" rel="noopener"><i
                                            class="fab fa-instagram"></i></a>
                                @endif
                                @if ($yt)
                                    <a href="{{ $yt }}" target="_blank" rel="noopener"><i
                                            class="fab fa-youtube"></i></a>
                                @endif
                            </div>

                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="{{ url('/guru/' . $slug) }}">{{ $name }}</a></h5>
                                    <span>{{ $role }}</span>
                                    <div class="mt-2 small text-muted">Kelas: <strong>{{ $kelas }}</strong></div>
                                    @if ($subs)
                                        <div class="mt-2 d-flex flex-wrap gap-1">
                                            @foreach ($subs as $s)
                                                <span class="badge mapel-badge">{{ $s }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <span class="team-social-btn"><i class="far fa-share-nodes"></i></span>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info">Data guru belum tersedia.</div>
                    </div>
                @endforelse
            </div>

            {{-- Paginasi server-side (jika pakai LengthAwarePaginator) --}}
            @if (isset($teachers) && $teachers instanceof \Illuminate\Pagination\AbstractPaginator)
                <div class="mt-4">
                    {{ $teachers->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Style kecil agar gambar & badge rapi --}}
    <style>
        .team-img img {
            width: 100%;
            height: 260px;
            object-fit: cover;
        }

        .mapel-badge {
            background: #f3f4f6;
            color: #111827;
            font-weight: 600;
            border-radius: 999px;
            padding: .35rem .6rem;
        }
    </style>

    {{-- Filter client-side --}}
    <script>
        (function() {
            const q = document.getElementById('teacherSearch');
            const fs = document.getElementById('filterSubject');
            const fr = document.getElementById('filterRole');
            const cards = document.querySelectorAll('#teacherGrid .teacher-card');

            function norm(s) {
                return (s || '').toLowerCase().trim();
            }

            function applyFilters() {
                const qv = norm(q.value);
                const sv = norm(fs.value);
                const rv = norm(fr.value);

                cards.forEach(card => {
                    const name = card.dataset.name;
                    const role = card.dataset.role;
                    const subs = card.dataset.subjects;

                    let show = true;
                    if (qv && !(name.includes(qv) || subs.includes(qv))) show = false;
                    if (sv && !subs.includes(sv)) show = false;
                    if (rv && !role.includes(rv)) show = false;

                    card.style.display = show ? '' : 'none';
                });
            }

            q && q.addEventListener('input', applyFilters);
            fs && fs.addEventListener('change', applyFilters);
            fr && fr.addEventListener('change', applyFilters);
        })();
    </script>
@endsection
