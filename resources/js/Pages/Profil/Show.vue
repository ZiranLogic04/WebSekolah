<template>
    <BerandaLayout>
        <!-- Breadcrumb Area -->
        <div class="breadcrumb-area" style="background-image: url(/assets/img/hope/breadcrumb-01.jpg);">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb-content">
                            <h2 class="title">{{ title }}</h2>
                            <ul class="breadcrumb-list">
                                <li><Link href="/">Beranda</Link></li>
                                <li class="active">{{ title }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Breadcrumb Area End -->

        <!-- Content Area -->
        <div class="py-120">
            <div class="container">
                
                <!-- Page: Visi & Misi -->
                <div v-if="slug === 'visi-misi'" class="visi-misi-wrapper">
                    <div class="row">
                        <div class="col-lg-6 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open"></i> Visi & Misi</span>
                                <h2 class="site-title">VISI <span>MISI</span></h2>
                                <p>Landasan arah pengembangan MI Al-Hikmah untuk menumbuhkan iman, ilmu, dan akhlak mulia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4">
                        <!-- Visi -->
                        <div class="col-lg-5">
                            <div class="p-4 rounded-3 shadow-sm h-100 bg-white">
                                <h4 class="mb-2">Visi</h4>
                                <div v-if="displayVisiTagline" class="d-inline-block mb-3 px-3 py-1 rounded-pill" style="background:#f3f4f6; font-weight:600;">
                                    {{ displayVisiTagline }}
                                </div>
                                <blockquote class="mb-0" style="font-size:1.05rem; line-height:1.7;">
                                    <em>"{{ displayVisi }}"</em>
                                </blockquote>
                            </div>
                        </div>
                        <!-- Misi -->
                        <div class="col-lg-7">
                            <div class="p-4 rounded-3 shadow-sm h-100 bg-white">
                                <h4 class="mb-3">Misi</h4>
                                <ul class="list-unstyled m-0" style="line-height:1.8;">
                                    <li v-for="(item, i) in displayMisiList" :key="i" class="mb-2">
                                        <i class="far fa-check-circle me-2 text-success"></i>{{ item }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Sejarah -->
                <div v-else-if="slug === 'sejarah'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Sejarah Singkat</span>
                                <h2 class="site-title">Sejarah <span>MI Al-Hikmah</span></h2>
                                <p>Perjalanan MI Al-Hikmah dalam membangun pendidikan dasar yang berlandaskan iman, ilmu, dan akhlak mulia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-start">
                        <!-- Narasi + Timeline -->
                        <div class="col-lg-7">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <h5 class="mb-3">Perjalanan Singkat</h5>
                                <p>{{ displayNarasi }}</p>
                                <ul class="timeline list-unstyled m-0 mt-4">
                                    <li v-for="(item, i) in displayTimeline" :key="i" class="mb-3">
                                        <div class="tl-dot"></div>
                                        <div class="tl-content">
                                            <span class="tl-year">{{ item.tahun }}</span>
                                            <h6 class="mb-1">{{ item.judul }}</h6>
                                            <p class="mb-0">{{ item.desc }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- Fakta Singkat -->
                        <div class="col-lg-5">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Fakta Singkat</h5>
                                <ul class="list-unstyled mb-0" style="line-height:1.9;">
                                    <li><i class="far fa-hashtag me-2 text-success"></i>NSM: <strong>{{ $page.props.lembaga?.nsm || '—' }}</strong></li>
                                    <li><i class="fa fa-award me-2 text-success"></i>Akreditasi: <strong>{{ $page.props.lembaga?.akreditasi || '—' }}</strong></li>
                                    <li><i class="fa fa-user-tie me-2 text-success"></i>Kepala: <strong>{{ $page.props.lembaga?.kepala_sekolah || '—' }}</strong></li>
                                    <li><i class="fa fa-map-marker-alt me-2 text-success"></i>Alamat: <strong>{{ $page.props.lembaga?.alamat || '—' }}</strong></li>
                                    <li><i class="fa fa-users me-2 text-success"></i>Siswa Aktif: <strong>{{ $page.props.lembaga?.jumlah_siswa || '—' }}</strong></li>
                                    <li><i class="fa fa-chalkboard-teacher me-2 text-success"></i>Guru & Tendik: <strong>{{ $page.props.lembaga?.jumlah_guru || '—' }}</strong></li>
                                </ul>
                                <a href="/profil/visi-misi" class="theme-btn mt-3">Profil Lengkap<i class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Sambutan / Kepala Madrasah -->
                <div v-else-if="slug === 'sambutan' || slug === 'kepala-madrasah'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-user-tie"></i> Kepala Madrasah</span>
                                <h2 class="site-title">Profil <span>Kepala Madrasah</span></h2>
                                <p>Kepemimpinan yang menumbuhkan iman, ilmu, dan akhlak mulia di MI Al-Hikmah.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Foto + Sambutan -->
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-4">
                            <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                                <div class="ratio ratio-3x4 rounded-3 overflow-hidden mb-3">
                                    <img :src="content?.foto ? `/storage/${content.foto}` : '/assets/img/team/placeholder.jpg'" class="w-100 h-100" style="object-fit:cover;" alt="Foto Kepala Madrasah">
                                </div>
                                <h4 class="mb-1">{{ $page.props.lembaga?.kepala_sekolah || 'Kepala Madrasah' }}</h4>
                                <div class="text-muted mb-2">Kepala Madrasah</div>
                                <ul class="list-unstyled small text-start m-0">
                                    <li class="mb-1"><i class="far fa-id-badge me-2 text-success"></i>NIP: {{ content?.nip || '—' }}</li>
                                    <li class="mb-1"><i class="far fa-calendar me-2 text-success"></i>{{ content?.periode || 'Periode 2023–sekarang' }}</li>
                                    <li v-if="content?.email" class="mb-1"><i class="far fa-envelope me-2 text-success"></i><a :href="'mailto:' + content.email">{{ content.email }}</a></li>
                                    <li v-if="content?.telepon"><i class="far fa-phone me-2 text-success"></i><a :href="'tel:' + content.telepon.replace(/\D/g, '')">{{ content.telepon }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Sambutan Kepala Madrasah</h5>
                                <blockquote v-if="content?.quote" class="mb-3" style="border-left:4px solid #16a34a; padding-left:12px; color:#0f5132;">
                                    {{ content.quote }}
                                </blockquote>
                                <div style="white-space: pre-line;">{{ content?.sambutan || 'Belum ada sambutan.' }}</div>
                            </div>
                        </div>
                    </div>
                    <!-- Profil Singkat & Riwayat -->
                    <div class="row g-4 align-items-start mt-2">
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <h5 class="mb-3">Profil Singkat</h5>
                                <table class="table table-sm align-middle mb-0">
                                    <tbody>
                                        <tr><th style="width:170px;">Nama Lengkap</th><td>{{ $page.props.lembaga?.kepala_sekolah || '—' }}</td></tr>
                                        <tr><th>Jabatan</th><td>Kepala Madrasah</td></tr>
                                        <tr><th>Masa Jabatan</th><td>{{ content?.periode || '—' }}</td></tr>
                                        <tr><th>NIP</th><td>{{ content?.nip || '—' }}</td></tr>
                                        <tr v-if="content?.email"><th>Email</th><td><a :href="'mailto:' + content.email">{{ content.email }}</a></td></tr>
                                        <tr v-if="content?.telepon"><th>Telepon</th><td><a :href="'tel:' + content.telepon.replace(/\D/g, '')">{{ content.telepon }}</a></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Riwayat Singkat</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li v-for="(item, i) in displayRiwayat" :key="i">
                                        <i class="far fa-check-circle me-2 text-success"></i>{{ item }}
                                    </li>
                                </ul>
                                <a href="/profil/visi-misi" class="theme-btn mt-3">Profil Madrasah<i class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Struktur Organisasi -->
                <div v-else-if="slug === 'struktur-organisasi'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-sitemap"></i> Struktur Organisasi</span>
                                <h2 class="site-title">Struktur <span>Organisasi MI Al-Hikmah</span></h2>
                                <p>Bagan organisasi madrasah: pengawas, pimpinan, koordinator, wali kelas, serta dukungan administrasi & komite.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Org Chart -->
                    <div class="row">
                        <div class="col-12">
                            <div class="org-wrap">
                                <!-- Mode: Image -->
                                <div v-if="displayOrg.mode_tampilan === 'image' && displayOrg.org_chart_image" class="text-center p-3 border rounded bg-light">
                                    <img :src="displayOrg.org_chart_image" class="img-fluid rounded shadow-sm" style="max-height: 800px; width: auto;" alt="Struktur Organisasi">
                                </div>

                                <!-- Mode: Tree (Text) -->
                                <ul v-else class="org">
                                    <li>
                                        <div class="node">
                                            <div class="node-title">{{ displayOrg.pengawas_label || 'Pengawas Madrasah' }}</div>
                                            <div class="node-name">{{ displayOrg.pengawas }}</div>
                                        </div>
                                        <ul>
                                            <li>
                                                <div class="node node-main">
                                                    <div class="node-title">{{ displayOrg.kepala_label || 'Kepala Madrasah' }}</div>
                                                    <div class="node-name">{{ $page.props.lembaga?.kepala_sekolah || '—' }}</div>
                                                </div>
                                                <ul>
                                                    <!-- Pimpinan -->
                                                    <li>
                                                        <div class="node">
                                                            <div class="node-title">{{ displayOrg.pimpinan_title || 'Pimpinan' }}</div>
                                                            <div class="node-name">
                                                                <div v-for="(p, i) in displayOrg.pimpinan" :key="i" class="mb-2 border-bottom pb-1 last-border-0">
                                                                    <div v-if="typeof p === 'object'">
                                                                        <div class="fw-bold text-primary small">{{ p.jabatan }}</div>
                                                                        <div>{{ p.nama }}</div>
                                                                    </div>
                                                                    <div v-else>{{ p }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <!-- Koordinator -->
                                                    <li>
                                                        <div class="node">
                                                            <div class="node-title">{{ displayOrg.koordinator_title || 'Koordinator' }}</div>
                                                            <div class="node-name">
                                                                <div v-for="(k, i) in displayOrg.koordinator" :key="i" class="mb-2 border-bottom pb-1 last-border-0">
                                                                    <div v-if="typeof k === 'object'">
                                                                        <div class="fw-bold text-primary small">{{ k.jabatan }}</div>
                                                                        <div>{{ k.nama }}</div>
                                                                    </div>
                                                                    <div v-else>{{ k }}</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <!-- Administrasi -->
                                                    <li>
                                                        <div class="node">
                                                            <div class="node-title">{{ displayOrg.administrasi_title || 'Administrasi' }}</div>
                                                            <div class="node-name">
                                                                <template v-if="Array.isArray(displayOrg.administrasi)">
                                                                    <div v-for="(a, i) in displayOrg.administrasi" :key="i" class="mb-2 border-bottom pb-1 last-border-0">
                                                                        <div v-if="typeof a === 'object'">
                                                                            <div class="fw-bold text-primary small">{{ a.jabatan }}</div>
                                                                            <div>{{ a.nama }}</div>
                                                                        </div>
                                                                        <div v-else>{{ a }}</div>
                                                                    </div>
                                                                </template>
                                                                <template v-else>{{ displayOrg.administrasi }}</template>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <!-- Komite -->
                                                    <li>
                                                        <div class="node">
                                                            <div class="node-title">{{ displayOrg.komite_title || 'Komite' }}</div>
                                                            <div class="node-name">
                                                                <template v-if="Array.isArray(displayOrg.komite)">
                                                                    <div v-for="(c, i) in displayOrg.komite" :key="i" class="mb-2 border-bottom pb-1 last-border-0">
                                                                        <div v-if="typeof c === 'object'">
                                                                            <div class="fw-bold text-primary small">{{ c.jabatan }}</div>
                                                                            <div>{{ c.nama }}</div>
                                                                        </div>
                                                                        <div v-else>{{ c }}</div>
                                                                    </div>
                                                                </template>
                                                                <template v-else>{{ displayOrg.komite }}</template>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- Wali Kelas -->
                    <div class="row mt-5">
                        <div class="col-lg-8">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <h5 class="mb-3">Wali Kelas</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead><tr><th style="min-width:120px;">Kelas</th><th>Nama Wali</th></tr></thead>
                                        <tbody>
                                            <tr v-for="(w, i) in displayOrg.wali_kelas" :key="i"><td>{{ w.kelas }}</td><td>{{ w.nama }}</td></tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Catatan</h5>
                                <p class="mb-0">{{ displayOrg.catatan || 'Bagan dapat diperbarui sewaktu-waktu sesuai SK terbaru.' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Sarana & Prasarana -->
                <div v-else-if="slug === 'sarana-prasarana'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Sarana & Prasarana</span>
                                <h2 class="site-title">Fasilitas <span>MI Al-Hikmah</span></h2>
                                <p>Ringkasan fasilitas pembelajaran dan penunjang di MI Al-Hikmah untuk mendukung proses belajar yang aman, nyaman, dan bermakna.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan Angka Kunci (dari section counter) -->
                    <div class="row g-4 mb-4">
                        <div class="col-md-3">
                            <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                                <div class="mb-1 text-muted">Jumlah Rombel</div>
                                <h3 class="m-0">{{ counter?.rombel || '6' }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                                <div class="mb-1 text-muted">Siswa Aktif</div>
                                <h3 class="m-0">{{ counter?.siswa_aktif || '—' }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                                <div class="mb-1 text-muted">Guru & Tendik</div>
                                <h3 class="m-0">{{ counter?.guru || '—' }}</h3>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="p-4 rounded-3 shadow-sm bg-white text-center">
                                <div class="mb-1 text-muted">Jumlah Alumni</div>
                                <h3 class="m-0">{{ counter?.alumni || '—' }}</h3>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Kartu Fasilitas (dari database atau fallback) -->
                    <div class="row g-4">
                        <div class="col-sm-6 col-lg-4" v-for="(facility, index) in displayFacilities" :key="index">
                            <div class="p-4 h-100 rounded-3 shadow-sm bg-white">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="icon-circle me-3" style="width:50px; height:50px; display:flex; align-items:center; justify-content:center; background:#e0f2f1; border-radius:50%; flex-shrink:0;">
                                        <i :class="facility.icon" class="text-success fs-4"></i>
                                    </div>
                                    <h5 class="m-0">{{ facility.title }}</h5>
                                </div>
                                <p class="mb-0">{{ facility.desc }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Inventaris Ringkas -->
                    <div class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <h5 class="mb-0">Inventaris Ringkas</h5>
                                    <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        Unduhan Inventaris <i class="fas fa-arrow-right-long ms-2"></i>
                                    </a>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead>
                                            <tr>
                                                <th>Fasilitas</th>
                                                <th>Jumlah</th>
                                                <th>Kondisi</th>
                                                <th>Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, idx) in displayInventaris" :key="idx">
                                                <td>{{ row.name }}</td>
                                                <td>{{ row.qty }}</td>
                                                <td>{{ row.cond }}</td>
                                                <td>{{ row.note }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-muted d-block mt-2">* Data di atas bersifat ringkas. Versi lengkap dan terbaru mengikuti dokumen inventaris resmi madrasah.</small>
                            </div>
                        </div>
                    </div>

                    <!-- Deskripsi Tambahan dari Admin -->
                    <div v-if="content?.content" class="row g-4 mt-2">
                        <div class="col-12">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <div class="ck-content" v-html="content.content"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Tujuan -->
                <div v-else-if="slug === 'tujuan'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Tujuan</span>
                                <h2 class="site-title">Tujuan <span>Madrasah</span></h2>
                                <p>Landasan arah pengembangan MI Al-Hikmah untuk menumbuhkan iman, ilmu, dan akhlak mulia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-7">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <ol class="list-unstyled m-0" style="line-height:1.9;">
                                    <li v-for="(tujuan, i) in displayTujuanList" :key="i">
                                        <i class="far fa-check-circle me-2 text-success"></i>{{ tujuan }}
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Program yang Mendukung</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li v-for="(prog, i) in displayProgramList" :key="i">{{ prog }}</li>
                                </ul>
                                <a href="/program" class="theme-btn mt-3">Lihat Program<i class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Kurikulum & Mapel -->
                <div v-else-if="slug === 'kurikulum-mapel'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-book-open-reader"></i> Kurikulum</span>
                                <h2 class="site-title">Kurikulum & <span>Mata Pelajaran</span></h2>
                                <p>Struktur pembelajaran MI Al-Hikmah yang religius, ramah anak, dan menumbuhkan literasi–numerasi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Ringkasan & Download -->
                    <div class="row g-4 align-items-start mb-4">
                        <div class="col-lg-8">
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <h5 class="mb-2">Ringkasan Kurikulum</h5>
                                <p class="mb-3">
                                    MI Al-Hikmah menerapkan kurikulum madrasah yang terintegrasi dengan penguatan karakter,
                                    tahfiz/BTQ, serta pembiasaan ibadah harian. Pembelajaran dirancang aktif, kolaboratif, dan
                                    kontekstual untuk menguatkan fondasi <em>literasi</em> dan <em>numerasi</em> sejak dini.
                                </p>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li><i class="far fa-check-circle me-2 text-success"></i> Integrasi
                                        <strong>intrakurikuler</strong>, <strong>kokurikuler</strong> (projek/pendalaman), dan
                                        <strong>ekstrakurikuler</strong>.</li>
                                    <li><i class="far fa-check-circle me-2 text-success"></i> Penguatan kekhasan madrasah:
                                        <strong>Tahfiz & BTQ</strong>, pembiasaan ibadah, akhlak mulia.</li>
                                    <li><i class="far fa-check-circle me-2 text-success"></i> Budaya sekolah: <strong>Gerakan
                                            Literasi</strong> & <strong>Clinic Numerasi</strong>, sekolah ramah anak.</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Dokumen Pembelajaran</h5>
                                <div class="d-grid gap-2">
                                    <a class="btn btn-outline-primary text-start" href="#"><i class="fas fa-file-pdf me-2"></i>Silabus</a>
                                    <a class="btn btn-outline-primary text-start" href="#"><i class="fas fa-file-pdf me-2"></i>Program Tahunan (Prota)</a>
                                    <a class="btn btn-outline-primary text-start" href="#"><i class="fas fa-file-pdf me-2"></i>Program Semester (Prosem)</a>
                                    <a class="btn btn-outline-primary text-start" href="#"><i class="fas fa-file-pdf me-2"></i>KKTP/KKM</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Mapel -->
                    <div class="row g-4">
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3"><i class="far fa-mosque me-2"></i> Kelompok Keagamaan</h5>
                                <ul class="m-0" style="columns:2; column-gap:24px; line-height:1.9;">
                                    <li v-for="m in displayMapelKeagamaan" :key="m">{{ m }}</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3"><i class="far fa-books me-2"></i> Kelompok Umum & Keterampilan</h5>
                                <ul class="m-0" style="columns:2; column-gap:24px; line-height:1.9;">
                                    <li v-for="m in displayMapelUmum" :key="m">{{ m }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Alokasi Waktu -->
                    <div class="p-4 mt-4 rounded-3 shadow-sm bg-white">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="mb-0">Alokasi Waktu per Minggu <small class="text-muted text-sm">(contoh, JP = 35 menit)</small></h5>
                            <a href="#" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                Unduhan Alokasi <i class="fas fa-arrow-right-long ms-2"></i>
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm align-middle table-bordered text-center table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th class="align-middle bg-light sticky-col">Kelas</th>
                                        <th v-for="(mapel, idx) in alokasiHeaders" :key="idx" class="text-nowrap">{{ mapel }}</th>
                                        <th class="align-middle bg-light fw-bold">Total JP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(row, kelas) in transposedAlokasi" :key="kelas">
                                        <td class="bg-light fw-bold sticky-col">{{ kelasLabel(kelas) }}</td>
                                        <td v-for="(mapel, idx) in alokasiHeaders" :key="idx">
                                            {{ row[mapel] || '-' }}
                                        </td>
                                        <td class="bg-light fw-bold">{{ row.total }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <small class="text-muted d-block mt-2">
                            * Angka di atas adalah contoh yang <em>dapat disesuaikan</em> dengan ketentuan madrasah & regulasi terbaru.
                        </small>
                    </div>

                    <!-- Penilaian & Program Penguat -->
                    <div class="row g-4 mt-2">
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Penilaian</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Asesmen diagnostik awal untuk pemetaan kemampuan.</li>
                                    <li>Penilaian formatif (harian/tugas/projek) & sumatif (PTS/PAS).</li>
                                    <li>Penilaian sikap/karakter terintegrasi dalam pembiasaan ibadah & adab.</li>
                                    <li>Portofolio karya & jurnal belajar untuk refleksi kemajuan siswa.</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <h5 class="mb-3">Program Penguat</h5>
                                <ul class="m-0" style="line-height:1.9;">
                                    <li>Tahfiz & BTQ (tahsin bacaan, setoran berkala, target juz bertahap).</li>
                                    <li>Gerakan Literasi (15 menit membaca) & Klinik Numerasi.</li>
                                    <li>Projek karakter & kepemimpinan melalui kegiatan kepramukaan/ekskul.</li>
                                    <li>Green & Clean School (GCC) dan Sekolah Ramah Anak.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Ekskul -->
                     <div class="p-4 mt-4 rounded-3 shadow-sm bg-white">
                        <h5 class="mb-3">Ekstrakurikuler</h5>
                        <div class="d-flex flex-wrap gap-2">
                            <span v-for="e in displayEkskul" :key="e" class="badge rounded-pill bg-light text-dark border p-2">
                                {{ e }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Page: Direktori Guru -->
                <div v-else-if="slug === 'direktori-guru'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-chalkboard-teacher"></i> GTK</span>
                                <h2 class="site-title">Direktori <span>Guru & Tendik</span></h2>
                                <p>Temukan guru dan tenaga kependidikan MI Al-Hikmah beserta bidang ajarnya.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-lg-3" v-for="guru in gurus" :key="guru.id">
                            <div class="team-item mb-4">
                                <div class="team-img">
                                    <img :src="guru.foto ? `/storage/${guru.foto}` : '/assets/img/team/placeholder.jpg'" :alt="guru.nama">
                                </div>
                                <div class="team-content">
                                    <div class="team-bio">
                                        <h5>{{ guru.nama }}</h5>
                                        <span>{{ guru.jabatan || 'Guru' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!gurus || gurus.length === 0" class="col-12">
                            <div class="alert alert-info">Data guru belum tersedia.</div>
                        </div>
                    </div>
                </div>

                <!-- Page: Syarat & Alur PPDB -->
                <div v-else-if="slug === 'syarat-dan-alur'">
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-user-plus"></i> PPDB</span>
                                <h2 class="site-title">Syarat & <span>Alur Pendaftaran</span></h2>
                                <p>Informasi resmi penerimaan peserta didik baru MI Al-Hikmah: persyaratan, tahapan pendaftaran, jadwal, dan kontak panitia.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 align-items-start">
                        <div class="col-lg-8">
                            <!-- Syarat -->
                            <div class="p-4 rounded-3 shadow-sm bg-white mb-3">
                                <h5 class="mb-3"><i class="fa fa-clipboard-check me-2 text-success"></i> Syarat Pendaftaran</h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Administratif</h6>
                                        <div v-if="syaratAdmin" v-html="syaratAdmin" style="line-height:1.9;"></div>
                                        <ul v-else class="m-0" style="line-height:1.9;">
                                            <li>Fotokopi <strong>Akte Kelahiran</strong> (1 lembar)</li>
                                            <li>Fotokopi <strong>Kartu Keluarga</strong> (1 lembar)</li>
                                            <li>Fotokopi <strong>KTP Orang Tua/Wali</strong> (1 lembar)</li>
                                            <li>Pas foto terbaru <strong>3×4</strong> (2 lembar)</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Ketentuan Usia</h6>
                                        <div v-if="syaratUsia" v-html="syaratUsia" style="line-height:1.9;"></div>
                                        <ul v-else class="m-0" style="line-height:1.9;">
                                            <li>Usia minimal <strong>6 tahun</strong> per <strong>1 Juli</strong> tahun berjalan</li>
                                            <li>Mengikuti observasi ringan kesiapan belajar</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Alur -->
                            <div class="p-4 rounded-3 shadow-sm bg-white">
                                <h5 class="mb-3"><i class="fa fa-route me-2 text-success"></i> Alur Pendaftaran</h5>
                                <ol class="ppdb-steps list-unstyled m-0">
                                    <li class="step" v-for="(step, i) in ppdbSteps" :key="i">
                                        <div class="step-num">{{ i + 1 }}</div>
                                        <div class="step-body">
                                            <h6 class="mb-1">{{ step.title }}</h6>
                                            <p class="mb-0">{{ step.desc }}</p>
                                        </div>
                                    </li>
                                </ol>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="p-4 rounded-3 shadow-sm bg-white position-sticky" style="top: 90px;">
                                <h5 class="mb-3">Info & Kontak Panitia</h5>
                                <ul class="list-unstyled mb-3" style="line-height:1.9;">
                                    <li><i class="fa fa-location-dot me-2 text-success"></i>{{ kontakInfo }}</li>
                                    <li><i class="far fa-envelope me-2 text-success"></i><a :href="'mailto:'+kontakEmail">{{ kontakEmail }}</a></li>
                                    <li><i class="fab fa-whatsapp me-2 text-success"></i><a :href="'https://wa.me/'+kontakWa.replace(/[^0-9]/g, '')" target="_blank">{{ kontakWa }}</a></li>
                                </ul>
                                <a :href="linkDaftar" class="theme-btn d-block text-center">Daftar Online<i class="fas fa-arrow-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Page: Jadwal & Kuota PPDB -->
                <div v-else-if="slug === 'jadwal-dan-kuota'">
                    <div class="row">
                        <div class="col-lg-9 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="fa fa-calendar-check"></i> PPDB</span>
                                <h2 class="site-title">Jadwal & <span>Kuota</span> PPDB</h2>
                                <p>Informasi resmi penerimaan peserta didik baru MI Al-Hikmah.</p>
                            </div>
                        </div>
                    </div>
                    <div class="row g-4 mb-4">
                        <div class="col-sm-6 col-lg-4" v-for="(j, i) in jadwalPpdb" :key="i">
                            <div class="p-4 rounded-3 shadow-sm bg-white h-100">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="fa fa-flag-checkered text-success"></i>
                                    <h6 class="m-0">{{ j.stage }}</h6>
                                </div>
                                <div class="small text-muted mb-2"><i class="fa fa-calendar me-1"></i>{{ j.range }}</div>
                                <p class="mb-0">{{ j.desc }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <a href="/ppdb/daftar" class="theme-btn">Daftar Sekarang<i class="fas fa-arrow-right-long ms-2"></i></a>
                        <a href="/profil/syarat-dan-alur" class="theme-btn theme-btn2 ms-2">Lihat Syarat<i class="fas fa-arrow-right-long ms-2"></i></a>
                    </div>
                </div>

                <!-- Default Fallback -->
                <!-- Default Fallback: Halaman Generik (Isi dari Editor) -->
                <div v-else>
                    <!-- Site Heading (Judul Halaman) -->
                    <div class="row">
                        <div class="col-lg-8 mx-auto">
                            <div class="site-heading text-center">
                                <span class="site-title-tagline"><i class="far fa-file-alt"></i> Informasi</span>
                                <h2 class="site-title">{{ title }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="row g-4 align-items-start">
                        <!-- Konten Utama -->
                        <div class="col-lg-8">
                            <div class="p-5 rounded-3 shadow-sm bg-white h-100">
                                <!-- Header di dalam Box (Opsional, untuk penegas) -->
                                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                                    <h4 class="m-0 text-uppercase">{{ title }}</h4>
                                </div>

                                <!-- Konten dari CKEditor -->
                                <div class="ck-content" v-if="content && content.content" v-html="content.content"></div>
                                <div class="ck-content" v-else>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Konten belum tersedia. Silakan hubungi administrator.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="col-lg-4">
                            <div class="sticky-top" style="top: 90px; z-index: 1;">
                                <Sidebar 
                                    :recentPosts="sidebarData?.recent_posts || []" 
                                    :categories="sidebarData?.categories || []"
                                />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </BerandaLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import BerandaLayout from '@/Layouts/BerandaLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';

const props = defineProps({
    slug: String,
    title: String,
    content: Object,
    gurus: Array,
    sidebarData: Object, // Data untuk Sidebar (Recent Post, dll)
    counter: Object, // Data counter dari section counter
});

// Computed: Gunakan data dari database jika ada, atau fallback ke statis
const displayFacilities = computed(() => {
    return props.content?.facilities?.length > 0 ? props.content.facilities : facilities;
});

const displayInventaris = computed(() => {
    return props.content?.inventaris?.length > 0 ? props.content.inventaris : inventaris;
});

// Static data for Sarana Prasarana
const facilities = [
    { icon: 'fas fa-chalkboard-teacher', title: 'Ruang Kelas', desc: 'Tersedia 6 ruang kelas yang nyaman dan terang.' },
    { icon: 'fas fa-book-reader', title: 'Perpustakaan & Pojok Baca', desc: 'Koleksi bacaan siswa & pojok baca di tiap kelas.' },
    { icon: 'fas fa-user-tie', title: 'Ruang Guru & Kepala', desc: 'Ruang kerja guru, kepala, serta TU/Operator.' },
    { icon: 'fas fa-first-aid', title: 'UKS & Keamanan Anak', desc: 'Layanan kesehatan dasar & Satgas Anti-Bullying.' },
    { icon: 'fas fa-desktop', title: 'Perangkat TIK', desc: 'Perangkat presentasi & akses TIK terjadwal.' },
    { icon: 'fas fa-volleyball-ball', title: 'Lapangan & Olahraga', desc: 'Kegiatan senam, scout, dan permainan edukatif.' },
    { icon: 'fas fa-mosque', title: 'Mushola / Pembiasaan Ibadah', desc: 'Kegiatan salat berjamaah & tadarus harian.' },
    { icon: 'fas fa-palette', title: 'Seni & Ekstrakurikuler', desc: 'Pramuka, hadroh, kaligrafi, dan kegiatan kreatif.' },
    { icon: 'fas fa-pump-soap', title: 'Sanitasi & Kebersihan', desc: 'Toilet terpisah, tempat cuci tangan, program GCC.' },
];

// Static data for Inventaris Ringkas
const inventaris = [
    { name: 'Ruang Kelas', qty: '6', cond: 'Baik', note: 'Meja-kursi proporsional' },
    { name: 'Perpustakaan', qty: '—', cond: '—', note: 'Koleksi bacaan siswa' },
    { name: 'Ruang Guru', qty: '1', cond: '—', note: '—' },
    { name: 'Ruang Kepala', qty: '1', cond: '—', note: '—' },
    { name: 'TU/Operator', qty: '1', cond: '—', note: 'Administrasi & Emis' },
    { name: 'UKS', qty: '1', cond: '—', note: 'P3K dasar' },
    { name: 'Mushola', qty: '1', cond: '—', note: 'Ibadah & tadarus' },
    { name: 'Perangkat TIK', qty: '—', cond: '—', note: 'Laptop/Proyektor (jadwal pakai)' },
    { name: 'Lapangan', qty: '1', cond: '—', note: 'Olahraga & pramuka' },
    { name: 'Toilet (L/P)', qty: '—', cond: '—', note: 'Terpisah & bersih' },
    { name: 'Tempat Cuci Tangan', qty: '—', cond: '—', note: 'Setiap area strategis' },
    { name: 'Gudang', qty: '1', cond: '—', note: 'Perlengkapan sekolah' },
];

// Static data for Tujuan
const tujuanList = [
    'Menyiapkan peserta didik agar memiliki dasar keimanan yang kokoh, akhlak mulia, dan semangat ibadah dalam kehidupan sehari-hari.',
    'Menumbuhkembangkan karakter siswa yang mandiri, disiplin, tanggung jawab, dan cinta terhadap ilmu pengetahuan.',
    'Menanamkan pemahaman Islam yang benar dan moderat, serta mencintai Al-Qur\'an sebagai pedoman hidup.',
    'Membekali peserta didik dengan keterampilan dasar membaca, menulis, berhitung, dan berpikir kritis sesuai jenjang usianya.',
    'Mengembangkan potensi peserta didik secara optimal baik di bidang akademik maupun non-akademik.',
    'Membangun budaya sekolah yang sehat, harmonis, dan peduli lingkungan demi iklim belajar yang nyaman dan menyenangkan.',
    'Menanamkan semangat nasionalisme dan cinta tanah air dalam bingkai nilai-nilai Islami.',
];

// Static data for Program Pendukung
const programList = [
    'Tahfidz & Tadarus Harian',
    'Gerakan Literasi Madrasah (GLM)',
    'Green & Clean School (GCC)',
    'Ekstrakurikuler: Pramuka, Kaligrafi, Hadroh, KIR, English Club, olahraga',
];

// Computed: Gunakan data dari database jika ada, atau fallback ke statis
const displayTujuanList = computed(() => {
    return props.content?.tujuan?.length > 0 ? props.content.tujuan : tujuanList;
});

const displayProgramList = computed(() => {
    return props.content?.program?.length > 0 ? props.content.program : programList;
});

// Static data for Kurikulum
const defaultMapelKeagamaan = ["Al-Qur'an Hadits", "Akidah Akhlak", "Fikih", "SKI", "Bahasa Arab", "Tahfiz & BTQ"];
const defaultMapelUmum = ["PPKn", "Bahasa Indonesia", "Matematika", "IPAS", "PJOK", "SBdP", "Muatan Lokal", "Informatika/TIK"];

const defaultAlokasi = [
    { mapel: 'PPKn', '1': 4, '2': 4, '3': 4, '4': 4, '5': 4, '6': 4 },
    { mapel: 'B. Indonesia', '1': 7, '2': 7, '3': 7, '4': 6, '5': 6, '6': 6 },
    { mapel: 'Matematika', '1': 6, '2': 6, '3': 6, '4': 6, '5': 6, '6': 6 },
    { mapel: 'IPAS', '1': 3, '2': 3, '3': 4, '4': 5, '5': 5, '6': 5 },
    { mapel: 'SBdP', '1': 3, '2': 3, '3': 3, '4': 3, '5': 3, '6': 3 },
    { mapel: 'PJOK', '1': 4, '2': 4, '3': 4, '4': 4, '5': 4, '6': 4 },
    { mapel: 'PAI (QH, AA, Fikih, SKI)', '1': 6, '2': 6, '3': 6, '4': 6, '5': 6, '6': 6 },
    { mapel: 'Bahasa Arab', '1': 2, '2': 2, '3': 2, '4': 2, '5': 2, '6': 2 },
    { mapel: 'BTQ/Tahfiz', '1': 3, '2': 3, '3': 3, '4': 3, '5': 3, '6': 3 },
];

const defaultEkskul = ['Pramuka', 'Hadroh', 'Kaligrafi', 'KIR / Sains', 'Olahraga', 'English Club'];

const displayMapelKeagamaan = computed(() => props.content?.mapel_keagamaan?.length > 0 ? props.content.mapel_keagamaan : defaultMapelKeagamaan);
const displayMapelUmum = computed(() => props.content?.mapel_umum?.length > 0 ? props.content.mapel_umum : defaultMapelUmum);

const displayAlokasi = computed(() => {
    if (typeof props.content?.alokasi === 'string') {
         try { return JSON.parse(props.content.alokasi); } catch { return defaultAlokasi; }
    }
    return props.content?.alokasi?.length > 0 ? props.content.alokasi : defaultAlokasi;
});

// Transpose Alokasi (Subject-based -> Class-based) for UI
const alokasiHeaders = computed(() => {
    return displayAlokasi.value.map(row => row.mapel);
});

const transposedAlokasi = computed(() => {
    const classes = ['1', '2', '3', '4', '5', '6'];
    const result = {};
    
    classes.forEach(cls => {
        const rowData = { total: 0 };
        displayAlokasi.value.forEach(item => {
            const jp = parseInt(item[cls]) || 0;
            rowData[item.mapel] = jp > 0 ? jp : '—'; // Use dash for 0/empty
            rowData.total += jp;
        });
        result[cls] = rowData;
    });
    return result;
});

const kelasLabel = (cls) => {
    const roman = { '1': 'I', '2': 'II', '3': 'III', '4': 'IV', '5': 'V', '6': 'VI' };
    return roman[cls] || cls;
};

const displayEkskul = computed(() => props.content?.ekskul?.length > 0 ? props.content.ekskul : defaultEkskul);

// === PPDB: Syarat & Alur ===
// Static data for PPDB Steps (Fallback)
const defaultPpdbSteps = [
    { title: 'Isi Formulir', desc: 'Calon peserta didik/ortu mengisi formulir online atau offline di sekolah.' },
    { title: 'Verifikasi Dokumen', desc: 'Panitia memeriksa berkas & menghubungi via WhatsApp/Email untuk konfirmasi.' },
    { title: 'Observasi/Wawancara', desc: 'Observasi kesiapan belajar & wawancara singkat orang tua.' },
    { title: 'Pengumuman & Daftar Ulang', desc: 'Peserta yang diterima melakukan daftar ulang (administrasi awal, seragam, dsb.).' },
];

// Computed
const ppdbSteps = computed(() => {
    if (typeof props.content?.alur_steps === 'string') {
         try { return JSON.parse(props.content.alur_steps); } catch { return defaultPpdbSteps; }
    }
    return props.content?.alur_steps?.length > 0 ? props.content.alur_steps : defaultPpdbSteps;
});

const syaratAdmin = computed(() => props.content?.syarat_admin || null);
const syaratUsia = computed(() => props.content?.syarat_usia || null);
const kontakInfo = computed(() => props.content?.kontak_info || 'Kp. Cihaneut RT 01/RW 11, Desa Drawati, Paseh, Bandung');
const kontakWa = computed(() => props.content?.kontak_wa || '0812-1444-7944');
const kontakEmail = computed(() => props.content?.kontak_email || 'mialhikmahpaseh@gmail.com');
const linkDaftar = computed(() => props.content?.link_daftar || '/ppdb/daftar');


// === PPDB: Jadwal & Kuota ===
// Static data for Jadwal PPDB (Fallback)
const defaultJadwalPpdb = [
    { stage: 'Pendaftaran', range: '1 Feb – 31 Mar 2026', desc: 'Daftar online/offline & unggah berkas' },
    { stage: 'Verifikasi', range: 'H+1 s.d. H+3 (rolling)', desc: 'Validasi berkas & konfirmasi WA/Email' },
    { stage: 'Observasi', range: 'Setiap Sabtu, 09.00–11.00', desc: 'Observasi siswa & wawancara orang tua' },
    { stage: 'Pengumuman', range: 'Setiap Senin, 12.00', desc: 'Hasil seleksi & penempatan sementara' },
    { stage: 'Daftar Ulang', range: 'Maks. 7 hari setelah pengumuman', desc: 'Administrasi awal & pengukuran seragam' },
];

const jadwalPpdb = computed(() => {
    if (typeof props.content?.jadwal === 'string') {
         try { return JSON.parse(props.content.jadwal); } catch { return defaultJadwalPpdb; }
    }
    return props.content?.jadwal?.length > 0 ? props.content.jadwal : defaultJadwalPpdb;
});

// === VISI MISI ===
const defaultVisi = 'Terwujudnya Insan yang Tafaqquh fid-din, Mandiri, dan Berakhlakul Karimah (TAMANKU).';
const defaultMisiList = [
    'Mewujudkan generasi yang rajin dan khusyuk beribadah melalui kegiatan keagamaan.',
    'Menumbuhkan perilaku santun melalui pembiasaan 5S dan agen karakter.',
    'Meningkatkan kualitas dan efektivitas proses belajar mengajar.',
    'Mendorong prestasi akademik dan non-akademik melalui kegiatan ekstrakurikuler.',
    'Membiasakan budaya disiplin bagi seluruh warga madrasah melalui program bahasa.',
    'Menciptakan lingkungan Aman, Bersih, Indah, Rindang, Asri melalui kegiatan "5 menit bersih" sebelum pulang.',
    'Menjaga dan mengelola lingkungan hidup melalui komunitas Green and Clean School (GCC).',
    'Meningkatkan kemampuan literasi melalui Gerakan Literasi Madrasah (GLM).',
];

const displayVisi = computed(() => props.content?.visi || defaultVisi);
const displayVisiTagline = computed(() => props.content?.tagline || 'TAMANKU');
const displayMisiList = computed(() => props.content?.misi?.length > 0 ? props.content.misi : defaultMisiList);

// === SEJARAH ===
const defaultNarasi = 'MI Al-Hikmah berdiri atas semangat masyarakat dan para pendidik untuk menghadirkan layanan pendidikan dasar yang religius, ramah anak, dan berkualitas. Seiring waktu, madrasah mengembangkan program unggulan seperti Tahfiz & BTQ, Gerakan Literasi Madrasah (GLM), Green & Clean School (GCC), serta beragam ekstrakurikuler yang menumbuhkan bakat dan karakter siswa.';
const defaultTimeline = [
    { tahun: '2008', judul: 'Pendirian MI Al-Hikmah', desc: 'Awal mula layanan pendidikan dasar berbasis keislaman.' },
    { tahun: '2014', judul: 'Penguatan Tahfiz & BTQ', desc: 'Program tahsin, setoran berkala, dan pembiasaan ibadah harian.' },
    { tahun: '2017', judul: 'Gerakan Literasi Madrasah (GLM)', desc: 'Membaca 15 menit, pojok baca kelas, dan kegiatan literasi kreatif.' },
    { tahun: '2019', judul: 'Green & Clean School (GCC)', desc: 'Pembiasaan kebersihan, penghijauan, dan sekolah yang asri.' },
    { tahun: '2022', judul: 'Akreditasi Madrasah', desc: 'Pencapaian akreditasi B sebagai wujud komitmen mutu.' },
    { tahun: '2024', judul: 'Penyesuaian Kurikulum Terbaru', desc: 'Implementasi regulasi kurikulum terkini dengan penekanan karakter.' },
];

const displayNarasi = computed(() => props.content?.narasi || defaultNarasi);
const displayTimeline = computed(() => props.content?.timeline?.length > 0 ? props.content.timeline : defaultTimeline);

// === KEPALA MADRASAH ===
const defaultRiwayat = [
    'Pendidikan terakhir: (isi dari data resmi)',
    'Pengalaman mengajar & kepemimpinan madrasah',
    'Fokus pengembangan: Tahfiz/BTQ, GLM, GCC, layanan ramah anak',
    'Kolaborasi aktif dengan komite & orang tua/wali',
];

const displayRiwayat = computed(() => {
    if (typeof props.content?.riwayat === 'string') {
        try { return JSON.parse(props.content.riwayat); } catch { return defaultRiwayat; }
    }
    return props.content?.riwayat?.length > 0 ? props.content.riwayat : defaultRiwayat;
});

// === ORGANISASI ===
const defaultOrg = {
    pengawas: 'Ayi Ridwan',
    pimpinan: ['Wakil Kepala (Kurikulum)', 'Wakil Kepala (Kesiswaan)', 'Wakil Kepala (Sarpras/Humas)'],
    koordinator: ['Koordinator Tahfiz & BTQ', 'Koordinator Literasi & Numerasi', 'UKS & Satgas Anti-Bullying'],
    administrasi: 'Operator/Emis & TU',
    komite: 'Iis Kurniasih (Ketua Komite)',
    wali_kelas: [
        { kelas: 'Kelas I', nama: '—' }, { kelas: 'Kelas II', nama: '—' },
        { kelas: 'Kelas III', nama: '—' }, { kelas: 'Kelas IV', nama: '—' },
        { kelas: 'Kelas V', nama: '—' }, { kelas: 'Kelas VI', nama: '—' },
    ],
    catatan: 'Bagan dapat diperbarui sewaktu-waktu sesuai SK terbaru.',
};

const displayOrg = computed(() => ({
    pengawas: props.content?.pengawas || defaultOrg.pengawas,
    pimpinan: props.content?.pimpinan?.length > 0 ? props.content.pimpinan : defaultOrg.pimpinan,
    koordinator: props.content?.koordinator?.length > 0 ? props.content.koordinator : defaultOrg.koordinator,
    administrasi: props.content?.administrasi || defaultOrg.administrasi,
    komite: props.content?.komite || defaultOrg.komite,
    wali_kelas: props.content?.wali_kelas?.length > 0 ? props.content.wali_kelas : defaultOrg.wali_kelas,
    catatan: props.content?.catatan || defaultOrg.catatan,
}));
</script>

<style>
/* CKEditor Content Styles */
.ck-content h2 { margin-top: 1.5em; margin-bottom: 0.5em; font-weight: bold; }
.ck-content h3 { margin-top: 1.2em; margin-bottom: 0.5em; font-weight: bold; }
.ck-content ul, .ck-content ol { padding-left: 1.5em; margin-bottom: 1em; }
.ck-content ul li { list-style-type: disc; margin-bottom: 0.25em; }
.ck-content ol li { list-style-type: decimal; margin-bottom: 0.25em; }
.ck-content blockquote {
    border-left: 4px solid #ccc;
    margin-left: 0;
    padding-left: 1em;
    font-style: italic;
    color: #555;
}

/* UI Adjustments matching reference */
.badge {
    padding: .6rem .9rem;
    font-size: 0.9em;
}
.table thead th {
    white-space: nowrap;
}
.sticky-col {
    position: sticky;
    left: 0;
    z-index: 1;
    border-right: 2px solid #dee2e6;
}

/* Team Image */
.team-img img {
    width: 100%;
    height: 260px;
    object-fit: cover;
}

/* PPDB Steps */
.ppdb-steps .step {
    position: relative;
    display: flex;
    gap: 14px;
    padding: 14px 0;
}
.ppdb-steps .step + .step {
    border-top: 1px dashed rgba(0, 0, 0, .12);
}
.ppdb-steps .step-num {
    flex: 0 0 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #16a34a;
    color: #fff;
    font-weight: 700;
    box-shadow: 0 6px 16px rgba(22, 163, 74, .25);
}
.ppdb-steps .step-body h6 {
    font-weight: 700;
}

.py-120 {
    padding-top: 120px;
    padding-bottom: 120px;
}

/* === Timeline Styles === */
.timeline li {
    position: relative;
    padding-left: 24px;
}
.timeline li .tl-dot {
    position: absolute;
    left: 0;
    top: 6px;
    width: 12px;
    height: 12px;
    background: #16a34a;
    border-radius: 50%;
}
.timeline li .tl-dot::after {
    content: '';
    position: absolute;
    left: 5px;
    top: 12px;
    width: 2px;
    height: calc(100% + 12px);
    background: rgba(22, 163, 74, .25);
}
.timeline li:last-child .tl-dot::after {
    display: none;
}
.tl-year {
    display: inline-block;
    background: #16a34a;
    color: #fff;
    font-size: .75rem;
    font-weight: 600;
    padding: 2px 8px;
    border-radius: 4px;
    margin-bottom: 4px;
}

/* === Org Chart Styles === */
.org-wrap { overflow-x: auto; padding: 8px 0; }
.org, .org ul { margin: 0; padding: 0; list-style: none; }
.org { display: flex; justify-content: center; }
.org > li > ul, .org ul ul { display: flex; justify-content: center; align-items: flex-start; gap: 24px; padding-top: 20px; position: relative; }
.org > li > ul::before, .org ul ul::before { content: ""; position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 1px; height: 20px; background: rgba(0,0,0,.15); }
.org li { position: relative; padding-top: 20px; flex: 0 0 auto; text-align: center; }
.org li::before, .org li::after { content: ""; position: absolute; top: 0; width: 50%; height: 1px; border-top: 1px solid rgba(0,0,0,.15); }
.org li::before { left: -50%; }
.org li::after { right: -50%; }
.org li:only-child::before, .org li:only-child::after { display: none; }
.org li:first-child::before { display: none; }
.org li:last-child::after { display: none; }
.node { display: inline-block; min-width: 200px; max-width: 280px; background: #fff; border: 1px solid rgba(0,0,0,.08); border-radius: 12px; box-shadow: 0 6px 16px rgba(0,0,0,.06); padding: 12px 14px; }
.node-main { border: 1px solid rgba(22,163,74,.25); box-shadow: 0 10px 24px rgba(22,163,74,.12); }
.node-title { font-weight: 700; font-size: .95rem; margin-bottom: 4px; }
.node-name { font-size: .95rem; line-height: 1.5; }
.node-name > div + div { margin-top: 2px; }
@media (max-width: 576px) { .node { min-width: 180px; } }

/* === Ratio Helper === */
.ratio.ratio-3x4 { position: relative; width: 100%; padding-top: 133.333%; }
.ratio.ratio-3x4 > img { position: absolute; inset: 0; }
</style>
