<template>
  <footer class="footer-area">
    <div class="footer-shape">
        <img src="/assets/img/hope/header_logo1.png" alt="Logo MI Al-Hikmah">
    </div>

    <div class="footer-widget">
        <div class="container">
            <div class="row footer-widget-wrapper pt-100 pb-70">

                <!-- Kolom 1: Tentang & Kontak -->
                <div class="col-md-6 col-lg-4">
                    <div class="footer-widget-box about-us">
                        <Link href="/" class="footer-logo">
                            <img src="/assets/img/hope/header_logo1.png" alt="Logo MI Al-Hikmah" style="width: 80px">
                        </Link>
                        <p class="mb-3">
                            MI Al-Hikmah adalah madrasah ibtidaiyah yang berkomitmen menghadirkan pendidikan dasar
                            berkualitas,
                            menumbuhkan akhlak, serta menguatkan literasi dan numerasi dalam lingkungan yang ramah
                            anak.
                        </p>
                        <ul class="footer-contact">
                            <li>
                                <a :href="'https://maps.google.com/?q=' + ($page.props.lembaga?.alamat ?? '')" target="_blank" rel="noopener">
                                    <i class="far fa-location-dot"></i> {{ $page.props.lembaga?.alamat || 'Alamat belum diatur' }}
                                </a>
                            </li>
                            <li>
                                <a :href="'mailto:' + ($page.props.lembaga?.email ?? '#')"><i class="far fa-envelope"></i>
                                    {{ $page.props.lembaga?.email || '-' }}</a>
                            </li>
                            <!-- Optional: Humas Email if needed elsewhere or use same
                            <li>
                                <a href="mailto:humas@mi-alhikmah.sch.id"><i class="far fa-envelope"></i>
                                    humas@mi-alhikmah.sch.id</a>
                            </li>
                            -->
                            <li>
                                <a :href="'tel:' + ($page.props.lembaga?.telepon ?? '#')"><i class="far fa-phone-volume"></i> {{ $page.props.lembaga?.telepon || '-' }}</a>
                            </li>
                            <li>
                                <a :href="'https://wa.me/' + ($page.props.lembaga?.whatsapp || '').replace(/[^0-9]/g, '')" target="_blank" rel="noopener">
                                    <i class="fab fa-whatsapp"></i> WhatsApp Layanan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Kolom 2: Tautan Cepat -->
                <div class="col-md-6 col-lg-2">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Tautan Cepat</h4>
                        <ul class="footer-list">
                            <li><Link href="/"><i class="fas fa-caret-right"></i> Beranda</Link></li>
                            <li><Link href="/profil"><i class="fas fa-caret-right"></i> Tentang</Link></li>
                            <li><Link href="/akademik/kurikulum"><i class="fas fa-caret-right"></i> Akademik</Link></li>
                            <li><Link href="/program"><i class="fas fa-caret-right"></i> Program</Link></li>
                            <li><Link href="/ppdb" class="fw-bold"><i class="fas fa-caret-right"></i> PPDB</Link>
                            </li>
                            <li><Link href="/berita"><i class="fas fa-caret-right"></i> Berita</Link></li>
                            <li><Link href="/galeri"><i class="fas fa-caret-right"></i> Galeri</Link></li>
                            <li><Link href="/kontak"><i class="fas fa-caret-right"></i> Kontak</Link></li>
                        </ul>
                    </div>
                </div>

                <!-- Kolom 3: Akademik & Layanan -->
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Akademik & Layanan</h4>
                        <ul class="footer-list">
                            <li><Link href="/akademik/kalender"><i class="fas fa-caret-right"></i> Kalender
                                    Akademik</Link></li>
                            <li><Link href="/program/tahfiz"><i class="fas fa-caret-right"></i> Tahfiz & BTQ</Link></li>
                            <li><Link href="/program/literasi"><i class="fas fa-caret-right"></i> Literasi 15
                                    Menit</Link></li>
                            <li><Link href="/program/numerasi"><i class="fas fa-caret-right"></i> Klinik Numerasi</Link>
                            </li>
                            <li><Link href="/kesiswaan/ekskul"><i class="fas fa-caret-right"></i> Ekstrakurikuler</Link>
                            </li>
                            <li><Link href="/unduhan"><i class="fas fa-caret-right"></i> Unduhan</Link></li>
                            <li><Link href="/anti-bullying"><i class="fas fa-caret-right"></i> Satgas
                                    Anti-Bullying</Link></li>
                            <li><Link href="/skm"><i class="fas fa-caret-right"></i> Survei Kepuasan (SKM)</Link>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Kolom 4: Lokasi / Map -->
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Lokasi Kami</h4>
                        <div class="ratio ratio-1x1" style="border-radius:12px; overflow:hidden;">
                            <iframe src="https://www.google.com/maps?q=MI%20Al-Hikmah%20(Kabupaten%20…)&output=embed"
                                style="border:0;" allowfullscreen="" loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>

            </div><!-- /.row -->
        </div>
    </div>

    <div class="copyright">
        <div class="container">
            <div class="copyright-wrapper">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <p class="copyright-text mb-0">
                            &copy; <span id="year">{{ new Date().getFullYear() }}</span> MI Al-Hikmah. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 align-self-center">
                        <ul class="footer-social">
                            <li v-if="$page.props.lembaga?.facebook"><a :href="$page.props.lembaga?.facebook" target="_blank" rel="noopener"><i
                                        class="fab fa-facebook-f"></i></a></li>
                            <li v-if="$page.props.lembaga?.instagram"><a :href="$page.props.lembaga?.instagram" target="_blank" rel="noopener"><i
                                        class="fab fa-instagram"></i></a></li>
                            <li v-if="$page.props.lembaga?.whatsapp"><a :href="'https://wa.me/' + ($page.props.lembaga?.whatsapp || '').replace(/[^0-9]/g, '')" target="_blank" rel="noopener"><i
                                        class="fab fa-whatsapp"></i></a></li>
                            <li v-if="$page.props.lembaga?.youtube"><a :href="$page.props.lembaga?.youtube" target="_blank" rel="noopener"><i
                                        class="fab fa-youtube"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </footer>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>