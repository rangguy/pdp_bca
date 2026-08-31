<?php

namespace Database\Seeders;

use App\Models\MasterDealer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Default users per role (password: password)
        User::factory()->marketing()->create([
            'username' => 'marketing1',
            'email' => 'marketing1@jkl.co.id',
            'nama_lengkap' => 'Budi Santoso',
        ]);

        User::factory()->marketing()->create([
            'username' => 'marketing2',
            'email' => 'marketing2@jkl.co.id',
            'nama_lengkap' => 'Siti Nurhaliza',
        ]);

        User::factory()->atasanMarketing()->create([
            'username' => 'atasan1',
            'email' => 'atasan1@jkl.co.id',
            'nama_lengkap' => 'Ahmad Wijaya',
        ]);

        User::factory()->adminBackoffice()->create([
            'username' => 'backoffice1',
            'email' => 'backoffice1@jkl.co.id',
            'nama_lengkap' => 'Dewi Lestari',
        ]);

        User::factory()->admin()->create([
            'username' => 'admin',
            'email' => 'admin@jkl.co.id',
            'nama_lengkap' => 'Super Admin',
        ]);

        // Sample dealers
        MasterDealer::create(['nama_dealer' => 'Honda Permata Motor', 'alamat' => 'Jl. Sudirman No. 45, Jakarta']);
        MasterDealer::create(['nama_dealer' => 'Yamaha Jaya Abadi', 'alamat' => 'Jl. Gatot Subroto No. 12, Jakarta']);
        MasterDealer::create(['nama_dealer' => 'Suzuki Mandiri Motor', 'alamat' => 'Jl. Thamrin No. 88, Jakarta']);
        MasterDealer::create(['nama_dealer' => 'Toyota Astra Mobil', 'alamat' => 'Jl. MT Haryono No. 33, Jakarta']);
        MasterDealer::create(['nama_dealer' => 'Daihatsu Sentral Motor', 'alamat' => 'Jl. HR Rasuna Said No. 21, Jakarta']);
    }
}
