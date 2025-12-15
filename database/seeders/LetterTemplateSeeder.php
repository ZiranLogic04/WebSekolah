<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\LetterTemplate;

class LetterTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks to allow truncation
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        LetterTemplate::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        // Defined Signature Block (Compacted)
        $signatureHtml = '
<p class="ql-align-right">................, ..............................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p class="ql-align-right"><br></p>
<p class="ql-align-right"><strong><u>(Nama Kepala Sekolah)</u></strong></p>
<p class="ql-align-right">NIP. ..............................</p>
';

        $templates = [
            // 1. KELULUSAN
            [
                'name' => 'Surat Keterangan Lulus',
                'use_system_kop' => true,
                'content' => '
<p class="ql-align-center"><strong><u>SURAT KETERANGAN LULUS</u></strong></p>
<p class="ql-align-center">Nomor: [NOMOR_SURAT]</p>
<p class="ql-align-justify">Yang bertanda tangan di bawah ini Kepala Sekolah, menerangkan bahwa:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 200px;">Nama Peserta Didik</td><td>: <strong>{{nama_siswa}}</strong></td></tr>
    <tr><td>Tempat, Tanggal Lahir</td><td>: {{tempat_tanggal_lahir}}</td></tr>
    <tr><td>Nomor Induk Siswa</td><td>: {{nis}}</td></tr>
    <tr><td>NISN</td><td>: {{nisn}}</td></tr>
    <tr><td>Kelas</td><td>: {{kelas}}</td></tr>
</table>
<p class="ql-align-justify">Berdasarkan hasil Rapat Pleno Dewan Guru tentang Kelulusan Peserta Didik Tahun Pelajaran 2024/2025, siswa tersebut dinyatakan:</p>
<h3 class="ql-align-center"><strong>L U L U S</strong></h3>
<p class="ql-align-justify">Surat keterangan ini berlaku sampai dengan diterbitkannya Ijazah yang asli. Demikian keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
' . $signatureHtml
            ],
            // 2. PANGGILAN ORANG TUA
            [
                'name' => 'Surat Panggilan Orang Tua',
                'use_system_kop' => true,
                'content' => '
<p>Nomor : [NOMOR_SURAT]</p>
<p>Lampiran : -</p>
<p>Perihal : <strong>Panggilan Orang Tua / Wali Murid</strong></p>
<p>Kepada Yth.</p>
<p>Bapak/Ibu Orang Tua/Wali Murid</p>
<p>dari <strong>{{nama_siswa}}</strong> (Kelas {{kelas}})</p>
<p>di Tempat</p>
<p>Dengan hormat,</p>
<p class="ql-align-justify">Sehubungan dengan perlunya komunikasi dan koordinasi mengenai perkembangan akademik dan kedisiplinan putra/putri Bapak/Ibu, maka kami mengharap kehadiran Bapak/Ibu pada:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 150px;">Hari / Tanggal</td><td>: {{hari_tanggal}}</td></tr>
    <tr><td>Waktu</td><td>: {{waktu}}</td></tr>
    <tr><td>Tempat</td><td>: Ruang BK</td></tr>
    <tr><td>Keperluan</td><td>: {{keperluan}}</td></tr>
</table>
<p class="ql-align-justify">Mengingat pentingnya hal tersebut, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktunya. Terima kasih.</p>
' . $signatureHtml
            ],
            // 3. UNDANGAN RAPAT
            [
                'name' => 'Surat Undangan Rapat Wali Murid',
                'use_system_kop' => true,
                'content' => '
<p>Nomor : [NOMOR_SURAT]</p>
<p>Perihal : <strong>Undangan Rapat Wali Murid</strong></p>
<p>Kepada Yth.</p>
<p>Bapak/Ibu Wali Murid Kelas {{kelas}}</p>
<p>di Tempat</p>
<p>Assalamualaikum Wr. Wb.</p>
<p class="ql-align-justify">Puji syukur kehadirat Allah SWT. Mengundang Bapak/Ibu Wali Murid pada pertemuan rutin yang akan dilaksanakan pada:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 150px;">Hari / Tanggal</td><td>: {{hari_tanggal}}</td></tr>
    <tr><td>Waktu</td><td>: {{waktu}}</td></tr>
    <tr><td>Tempat</td><td>: Aula Sekolah</td></tr>
    <tr><td>Agenda</td><td>: {{agenda_rapat}}</td></tr>
</table>
<p class="ql-align-justify">Demikian undangan ini kami sampaikan. Atas kehadiran dan partisipasi Bapak/Ibu, kami ucapkan terima kasih.</p>
<p>Wassalamualaikum Wr. Wb.</p>
' . $signatureHtml
            ],
            // 4. TUGAS DINAS
            [
                'name' => 'Surat Tugas Perjalanan Dinas',
                'use_system_kop' => true,
                'content' => '
<p class="ql-align-center"><strong><u>SURAT TUGAS</u></strong></p>
<p class="ql-align-center">Nomor: [NOMOR_SURAT]</p>
<p>Kepala Sekolah menugaskan kepada:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 150px;">Nama</td><td>: <strong>{{nama_guru}}</strong></td></tr>
    <tr><td>NIP</td><td>: {{nip}}</td></tr>
    <tr><td>Jabatan</td><td>: {{jabatan}}</td></tr>
</table>
<p class="ql-align-justify">Untuk melaksanakan tugas sebagai {{tugas_kegiatan}} yang akan diselenggarakan pada:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 150px;">Hari / Tanggal</td><td>: {{hari_tanggal}}</td></tr>
    <tr><td>Waktu</td><td>: {{waktu}}</td></tr>
    <tr><td>Tempat</td><td>: {{tempat_kegiatan}}</td></tr>
</table>
<p class="ql-align-justify">Demikian surat tugas ini diberikan agar dilaksanakan dengan penuh tanggung jawab dan melaporkan hasilnya setelah kegiatan selesai.</p>
' . $signatureHtml
            ],
            // 5. KETERANGAN AKTIF
            [
                'name' => 'Surat Keterangan Aktif Siswa',
                'use_system_kop' => true,
                'content' => '
<p class="ql-align-center"><strong><u>SURAT KETERANGAN AKTIF BELAJAR</u></strong></p>
<p class="ql-align-center">Nomor: [NOMOR_SURAT]</p>
<p>Yang bertanda tangan di bawah ini Kepala Sekolah menerangkan bahwa:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 180px;">Nama</td><td>: <strong>{{nama_siswa}}</strong></td></tr>
    <tr><td>NIS / NISN</td><td>: {{nis}} / {{nisn}}</td></tr>
    <tr><td>Kelas</td><td>: {{kelas}}</td></tr>
    <tr><td>Alamat</td><td>: {{alamat_siswa}}</td></tr>
</table>
<p class="ql-align-justify">Benar-benar merupakan siswa yang aktif mengikuti kegiatan pembelajaran di sekolah kami pada Tahun Pelajaran 2024/2025.</p>
<p class="ql-align-justify">Surat keterangan ini dibuat untuk dipergunakan sebagai: <strong>{{keperluan_surat}}</strong>.</p>
' . $signatureHtml
            ],
            // 6. PERMOHONAN MUTASI
            [
                'name' => 'Surat Permohonan Mutasi Siswa',
                'use_system_kop' => true,
                'content' => '
<p>Nomor : [NOMOR_SURAT]</p>
<p>Perihal : <strong>Permohonan Mutasi Sekolah</strong></p>
<p>Kepada Yth.</p>
<p>Kepala Dinas Pendidikan</p>
<p>di Tempat</p>
<p>Dengan hormat,</p>
<p class="ql-align-justify">Saya yang bertanda tangan di bawah ini orang tua/wali murid dari:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 180px;">Nama Siswa</td><td>: <strong>{{nama_siswa}}</strong></td></tr>
    <tr><td>NISN</td><td>: {{nisn}}</td></tr>
    <tr><td>Asal Sekolah</td><td>: SMA NEGERI 1 CONTOH</td></tr>
    <tr><td>Kelas</td><td>: {{kelas}}</td></tr>
</table>
<p class="ql-align-justify">Bermaksud mengajukan permohonan pindah/mutasi sekolah ke:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 180px;">Sekolah Tujuan</td><td>: <strong>{{sekolah_tujuan}}</strong></td></tr>
    <tr><td>Alamat Sekolah</td><td>: {{alamat_sekolah_tujuan}}</td></tr>
    <tr><td>Alasan Pindah</td><td>: {{alasan_pindah}}</td></tr>
</table>
<p class="ql-align-justify">Demikian permohonan ini kami ajukan. Atas perhatian dan izin yang diberikan, kami ucapkan terima kasih.</p>
<p class="ql-align-right">................, ..............................</p>
<p class="ql-align-right">Hormat Kami,</p>
<p class="ql-align-right"><br></p>
<p class="ql-align-right">({{nama_orang_tua}})</p>
' 
            ],
            // 7. SURAT PERINGATAN (SP)
            [
                'name' => 'Surat Peringatan (SP)',
                'use_system_kop' => true,
                'content' => '
<p class="ql-align-center"><strong><u>SURAT PERINGATAN</u></strong></p>
<p class="ql-align-center">Nomor: [NOMOR_SURAT]</p>
<p class="ql-align-justify">Diberikan kepada siswa:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 150px;">Nama</td><td>: <strong>{{nama_siswa}}</strong></td></tr>
    <tr><td>Kelas</td><td>: {{kelas}}</td></tr>
</table>
<p class="ql-align-justify">Sehubungan dengan pelanggaran tata tertib sekolah berupa <strong>{{jenis_pelanggaran}}</strong> pada tanggal {{hari_tanggal}}, maka kami memberikan:</p>
<h3 class="ql-align-center"><strong>PERINGATAN PERTAMA (SP-1)</strong></h3>
<p class="ql-align-justify">Kami berharap siswa yang bersangkutan dapat merubah perilakunya. Apabila kembali melakukan pelanggaran, akan diberikan sanksi lebih berat.</p>
' . $signatureHtml
            ],
            // 8. KETERANGAN KELAKUAN BAIK
            [
                'name' => 'Surat Keterangan Kelakuan Baik',
                'use_system_kop' => true,
                'content' => '
<p class="ql-align-center"><strong><u>SURAT KETERANGAN KELAKUAN BAIK</u></strong></p>
<p class="ql-align-center">Nomor: [NOMOR_SURAT]</p>
<p class="ql-align-justify">Yang bertanda tangan di bawah ini Kepala Sekolah menerangkan bahwa:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 180px;">Nama</td><td>: <strong>{{nama_siswa}}</strong></td></tr>
    <tr><td>NIS / NISN</td><td>: {{nis}} / {{nisn}}</td></tr>
    <tr><td>Kelas</td><td>: {{kelas}}</td></tr>
</table>
<p class="ql-align-justify">Siswa tersebut di atas adalah benar-benar siswa kami dan berdasarkan catatan sekolah, yang bersangkutan memiliki <strong>KELAKUAN BAIK</strong> dan tidak pernah terlibat tindakan melanggar hukum.</p>
<p class="ql-align-justify">Surat keterangan ini dipergunakan untuk: <strong>{{keperluan}}</strong>.</p>
' . $signatureHtml
            ],
             // 9. UNDANGAN PENGAMBILAN RAPOR
             [
                'name' => 'Undangan Pengambilan Rapor',
                'use_system_kop' => true,
                'content' => '
<p>Nomor : [NOMOR_SURAT]</p>
<p>Perihal : <strong>Pengambilan Rapor Semester Ganjil</strong></p>
<p>Kepada Yth.</p>
<p>Bapak/Ibu Wali Murid Kelas {{kelas}}</p>
<p>di Tempat</p>
<p>Assalamualaikum Wr. Wb.</p>
<p class="ql-align-justify">Puji syukur kehadirat Allah SWT. Mengakhiri Semester Ganjil Tahun Pelajaran 2024/2025, kami mengundang Bapak/Ibu hadir pada:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 150px;">Hari / Tanggal</td><td>: {{hari_tanggal}}</td></tr>
    <tr><td>Waktu</td><td>: {{waktu}}</td></tr>
    <tr><td>Tempat</td><td>: Ruang Kelas {{kelas}}</td></tr>
    <tr><td>Agenda</td><td>: Pembagian Hasil Belajar (Rapor)</td></tr>
</table>
<p class="ql-align-justify">Demikian undangan ini kami sampaikan. Kehadiran Bapak/Ibu sangat kami harapkan tepat pada waktunya.</p>
<p>Wassalamualaikum Wr. Wb.</p>
' . $signatureHtml
            ],
            // 10. SURAT REKOMENDASI BEASISWA
            [
                'name' => 'Surat Rekomendasi Beasiswa',
                'use_system_kop' => true,
                'content' => '
<p class="ql-align-center"><strong><u>SURAT REKOMENDASI</u></strong></p>
<p class="ql-align-center">Nomor: [NOMOR_SURAT]</p>
<p>Yang bertanda tangan di bawah ini Kepala Sekolah memberikan rekomendasi kepada:</p>
<table style="width: 100%; border: none;">
    <tr><td style="width: 180px;">Nama Siswa</td><td>: <strong>{{nama_siswa}}</strong></td></tr>
    <tr><td>Nomor Induk Siswa</td><td>: {{nis}}</td></tr>
    <tr><td>Kelas</td><td>: {{kelas}}</td></tr>
    <tr><td>Prestasi</td><td>: {{prestasi_siswa}}</td></tr>
</table>
<p class="ql-align-justify">Untuk mengajukan permohonan <strong>{{nama_beasiswa}}</strong>. Kami menilai siswa tersebut memiliki potensi akademik yang baik serta berkelakuan baik, sehingga layak untuk mendapatkan beasiswa tersebut.</p>
<p class="ql-align-justify">Demikian surat rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
' . $signatureHtml
            ]
        ];

        foreach ($templates as $tmpl) {
            LetterTemplate::create($tmpl);
        }
    }
}
