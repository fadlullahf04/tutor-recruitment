<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clean database tables first to avoid duplicates if re-seeded
        $isSqlite = DB::connection()->getDriverName() === 'sqlite';
        if ($isSqlite) {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }

        DB::table('users')->truncate();
        DB::table('recruitment_periods')->truncate();
        DB::table('faculties')->truncate();
        DB::table('study_programs')->truncate();
        DB::table('courses')->truncate();

        if ($isSqlite) {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // 1. Seed users
        DB::table('users')->insert([
            [
                'name' => 'Super Admin UPT PJJ',
                'email' => 'superadminuptpjj@uinssc.ac.id',
                'password' => Hash::make('lantai3pjj'),
                'role' => 'super_admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin Jurusan UPT PJJ',
                'email' => 'adminjurusanpjj@uinssc.ac.id',
                'password' => Hash::make('lantai3pjj'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 2. Seed active recruitment period
        DB::table('recruitment_periods')->insert([
            'name' => 'Rekrutmen Tutor PJJ - Semester Ganjil 2026/2027',
            'start_date' => '2026-06-01 00:00:00',
            'end_date' => '2026-08-31 23:59:59',
            'is_active' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Seed faculties
        $fitkId = DB::table('faculties')->insertGetId([
            'name' => 'Fakultas Ilmu Tarbiyah dan Keguruan (FITK)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $fuaId = DB::table('faculties')->insertGetId([
            'name' => 'Fakultas Ushuluddin dan Adab (FUA)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);


        // 4. Seed study programs
        $paiId = DB::table('study_programs')->insertGetId([
            'faculty_id' => $fitkId,
            'name' => 'S1 - PJJ Pendidikan Agama Islam (S1_PJJ_PAI)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $tbiId = DB::table('study_programs')->insertGetId([
            'faculty_id' => $fuaId,
            'name' => 'S1 - PJJ Sejarah Pendidikan Islam (S1_PJJ_SPI)',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 5. Seed courses
        DB::table('courses')->insert([
            // PAI Courses
            [
                'study_program_id' => $paiId,
                'code' => 'PAI-101',
                'name' => 'Sejarah Peradaban Islam',
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'study_program_id' => $paiId,
                'code' => 'PAI-102',
                'name' => 'Filsafat Pendidikan Islam',
                'credits' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'study_program_id' => $paiId,
                'code' => 'PAI-103',
                'name' => 'Metodologi Penelitian PAI',
                'credits' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
           
        ]);
    }
}
