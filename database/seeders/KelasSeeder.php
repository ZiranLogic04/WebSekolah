<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kelas = [
            "10 IPA 1", "10 IPA 2", "10 IPA 3",
            "10 IPS 1", "10 IPS 2", "10 IPS 3",
            "11 IPA 1", "11 IPA 2", "11 IPA 3",
            "11 IPS 1", "11 IPS 2", "11 IPS 3",
            "12 IPA 1", "12 IPA 2", "12 IPA 3",
            "12 IPS 1", "12 IPS 2", "12 IPS 3",
        ];

        foreach ($kelas as $k) {
            DB::table('kelas')->insertOrIgnore([
                'nama' => $k,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
