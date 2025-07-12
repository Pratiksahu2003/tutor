<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExamPackage;

class ExamPackageSeeder extends Seeder
{
    public function run()
    {
        $packages = [
            ['name' => 'JEE Main', 'status' => 'active'],
            ['name' => 'NEET UG', 'status' => 'active'],
            ['name' => 'UPSC Civil Services', 'status' => 'active'],
            ['name' => 'CAT', 'status' => 'active'],
            ['name' => 'GATE', 'status' => 'active'],
            ['name' => 'SSC CGL', 'status' => 'active'],
            ['name' => 'Bank PO', 'status' => 'active'],
            ['name' => 'CLAT', 'status' => 'active'],
            ['name' => 'NDA', 'status' => 'active'],
            ['name' => 'State PSC', 'status' => 'active'],
        ];
        foreach ($packages as $pkg) {
            ExamPackage::firstOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
} 