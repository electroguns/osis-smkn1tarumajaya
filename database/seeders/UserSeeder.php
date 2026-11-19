<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
     DB::table('users')->insert([
            'name' => 'Ashley Iraola',
            'email' => 'ashleyiraola10@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('osistmj1'),
            'qr_token' => 'OSIS-TRMJ-5M7PL2Y0AK',
            'role' => 'ketua',
            'kelas' => 'XI KIMIA A',
            'foto' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'name' => 'Zahratussita',
            'email' => 'zahratussita118@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('osistmj1'),
            'qr_token' => 'OSIS-TRMJ-5MSMAKUQZ2',
            'role' => 'wakil',
            'kelas' => 'XI KIMIA B',
            'foto' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'name' => 'Cantik Nur Anggraini Fitriyah',
            'email' => 'cacantik1599@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('osistmj1'),
            'qr_token' => 'OSIS-TRMJ-KKU4NPYG8J',
            'role' => 'sekretaris',
            'kelas' => 'XI TKJ B',
            'foto' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'name' => 'Sheila Dwi Raharti',
            'email' => 'sheiladwiraharti170309@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('osistmj1'),
            'qr_token' => 'OSIS-TRMJ-3RZQHDVV79',
            'role' => 'bendahara',
            'kelas' => 'XI TKJ A',
            'foto' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'name' => 'Muhammad Baabul Multazam',
            'email' => 'bymax176@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('osistmj1'),
            'qr_token' => 'OSIS-TRMJ-46MUIH3CAE',
            'role' => 'ketua_divisi_humas',
            'kelas' => 'XI TKJ B',
            'foto' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ],
        [
            'name' => 'Najwa Anisah',
            'email' => 'juwaaajuweeyy@gmail.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('osistmj1'),
            'qr_token' => 'OSIS-TRMJ-VJP3ZU8BIJ',
            'role' => 'anggota_divisi_humas',
            'kelas' => 'XI TKJ B',
            'foto' => null,
            'remember_token' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);  
    }
}