<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LetterTemplate;

class LetterTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $templates = [
            // 1. Surat Keterangan Siswa Aktif
            [
                'name' => 'Surat Keterangan Siswa Aktif',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT KETERANGAN SISWA AKTIF</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, menerangkan dengan sesungguhnya bahwa:</p>
<p>Nama Lengkap : {{ nama_siswa }}</p>
<p>Tempat/Tanggal Lahir : {{ tempat_lahir }}, {{ tanggal_lahir }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Kelas : {{ kelas }}</p>
<p>Alamat : {{ alamat }}</p>
<p><br></p>
<p>Adalah benar-benar siswa aktif pada Tahun Pelajaran {{ tahun_pelajaran }}.</p>
<p><br></p>
<p>Demikian surat keterangan ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 2. Surat Keterangan Lulus
            [
                'name' => 'Surat Keterangan Lulus',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT KETERANGAN LULUS</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, menerangkan bahwa:</p>
<p>Nama Lengkap : {{ nama_siswa }}</p>
<p>Tempat/Tanggal Lahir : {{ tempat_lahir }}, {{ tanggal_lahir }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Nama Orang Tua : {{ nama_ayah }}</p>
<p><br></p>
<p>Berdasarkan hasil Rapat Pleno Dewan Guru telah dinyatakan <strong>LULUS</strong> dari sekolah kami pada Tahun Pelajaran {{ tahun_pelajaran }}.</p>
<p><br></p>
<p>Surat keterangan ini berlaku sebagai pengganti sementara Ijazah.</p>
<p><br></p>
<p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 3. Surat Keterangan Pindah
            [
                'name' => 'Surat Keterangan Pindah',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT KETERANGAN PINDAH</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, menerangkan bahwa:</p>
<p>Nama Lengkap : {{ nama_siswa }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Kelas : {{ kelas }}</p>
<p>Nama Orang Tua : {{ nama_ayah }}</p>
<p><br></p>
<p>Pindah ke sekolah : {{ sekolah_tujuan }}</p>
<p>Alamat sekolah tujuan : {{ alamat_sekolah_tujuan }}</p>
<p>Alasan pindah : {{ alasan_pindah }}</p>
<p><br></p>
<p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 4. Surat Keterangan Berkelakuan Baik
            [
                'name' => 'Surat Keterangan Berkelakuan Baik',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT KETERANGAN BERKELAKUAN BAIK</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, menerangkan bahwa:</p>
<p>Nama Lengkap : {{ nama_siswa }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Kelas : {{ kelas }}</p>
<p>Alamat : {{ alamat }}</p>
<p><br></p>
<p>Selama menjadi siswa di sekolah kami <strong>BERKELAKUAN BAIK</strong> dan tidak pernah melakukan pelanggaran tata tertib sekolah.</p>
<p><br></p>
<p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 5. Surat Keterangan Tidak Mampu
            [
                'name' => 'Surat Keterangan Tidak Mampu',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT KETERANGAN</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, menerangkan bahwa:</p>
<p>Nama Lengkap : {{ nama_siswa }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Kelas : {{ kelas }}</p>
<p>Nama Orang Tua : {{ nama_ayah }}</p>
<p>Pekerjaan Orang Tua : {{ pekerjaan_ayah }}</p>
<p><br></p>
<p>Adalah benar siswa dari keluarga tidak mampu dan layak mendapatkan bantuan pendidikan.</p>
<p><br></p>
<p>Demikian surat keterangan ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 6. Surat Rekomendasi
            [
                'name' => 'Surat Rekomendasi',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT REKOMENDASI</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, memberikan rekomendasi kepada:</p>
<p>Nama Lengkap : {{ nama_siswa }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Kelas : {{ kelas }}</p>
<p><br></p>
<p>Untuk {{ keperluan_rekomendasi }}.</p>
<p><br></p>
<p>Demikian surat rekomendasi ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 7. Surat Panggilan Orang Tua
            [
                'name' => 'Surat Panggilan Orang Tua',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p>Nomor : {{ nomor_surat }}</p>
<p>Lamp. : -</p>
<p>Hal : <strong>Panggilan Orang Tua/Wali</strong></p>
<p><br></p>
<p>Kepada Yth.</p>
<p>Bapak/Ibu Orang Tua/Wali dari:</p>
<p>Nama : {{ nama_siswa }}</p>
<p>Kelas : {{ kelas }}</p>
<p>di Tempat</p>
<p><br></p>
<p>Dengan hormat,</p>
<p>Bersama ini kami mengharapkan kehadiran Bapak/Ibu pada:</p>
<p>Hari/Tanggal : {{ hari_tanggal }}</p>
<p>Pukul : {{ waktu }} WIB</p>
<p>Tempat : Ruang Kepala Sekolah</p>
<p>Keperluan : {{ keperluan }}</p>
<p><br></p>
<p>Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 8. Surat Undangan Rapat Wali Murid
            [
                'name' => 'Surat Undangan Rapat Wali Murid',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p>Nomor : {{ nomor_surat }}</p>
<p>Lamp. : -</p>
<p>Hal : <strong>Undangan Rapat Wali Murid</strong></p>
<p><br></p>
<p>Kepada Yth.</p>
<p>Bapak/Ibu Wali Murid</p>
<p>di Tempat</p>
<p><br></p>
<p>Dengan hormat,</p>
<p>Dalam rangka {{ agenda_rapat }}, kami mengundang Bapak/Ibu untuk hadir pada:</p>
<p>Hari/Tanggal : {{ hari_tanggal }}</p>
<p>Pukul : {{ waktu }} WIB</p>
<p>Tempat : {{ tempat_rapat }}</p>
<p>Acara : {{ acara }}</p>
<p><br></p>
<p>Mengingat pentingnya acara ini, kami mohon Bapak/Ibu dapat hadir tepat waktu.</p>
<p><br></p>
<p>Atas perhatian dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 9. Surat Tugas
            [
                'name' => 'Surat Tugas',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT TUGAS</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, menugaskan kepada:</p>
<p>Nama : {{ nama_guru }}</p>
<p>NIP : {{ nip_guru }}</p>
<p>Jabatan : {{ jabatan }}</p>
<p><br></p>
<p>Untuk melaksanakan tugas:</p>
<p>{{ uraian_tugas }}</p>
<p><br></p>
<p>Pada tanggal : {{ tanggal_tugas }}</p>
<p>Tempat : {{ tempat_tugas }}</p>
<p><br></p>
<p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],

            // 10. Surat Izin Kegiatan
            [
                'name' => 'Surat Izin Kegiatan',
                'use_system_kop' => true,
                'paper_size' => 'A4',
                'content' => '<p class="ql-align-center"><strong><u>SURAT IZIN</u></strong></p>
<p class="ql-align-center">Nomor: {{ nomor_surat }}</p>
<p><br></p>
<p>Yang bertanda tangan di bawah ini, Kepala Sekolah, memberikan izin kepada:</p>
<p>Nama : {{ nama_siswa }}</p>
<p>NIS/NISN : {{ nis }} / {{ nisn }}</p>
<p>Kelas : {{ kelas }}</p>
<p><br></p>
<p>Untuk mengikuti kegiatan:</p>
<p>Nama Kegiatan : {{ nama_kegiatan }}</p>
<p>Tanggal : {{ tanggal_kegiatan }}</p>
<p>Tempat : {{ tempat_kegiatan }}</p>
<p><br></p>
<p>Demikian surat izin ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>
<p><br></p>
<p class="ql-align-right">.................., .........................</p>
<p class="ql-align-right">Kepala Sekolah,</p>
<p><br></p>
<p><br></p>
<p><br></p>
<p class="ql-align-right"><u><strong>(Nama Kepala Sekolah)</strong></u></p>
<p class="ql-align-right">NIP. .............................</p>'
            ],
        ];

        foreach ($templates as $template) {
            LetterTemplate::create($template);
        }
    }
}
