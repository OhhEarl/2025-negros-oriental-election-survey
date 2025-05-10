<?php

namespace Database\Seeders;

use App\Models\Candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $candidates = [
            ['name' => 'Glendol Badon', 'party' => 'Independent'],
            ['name' => 'Joh Jaos', 'party' => 'Independent'],
            ['name' => 'Alex Larita', 'party' => 'Independent'],
            ['name' => 'Chaco Sagarbarria', 'party' => 'Partido Federal ng Pilipinas'],
            ['name' => 'Pryde Henry Teves', 'party' => 'Liberal Party'],
            ['name' => 'Stephen Tuballa', 'party' => 'Independent'],
        ];

        foreach ($candidates as $candidate) {
            Candidate::create([
                'name' => $candidate['name'],
                'position' => 'governor',
                'party' => $candidate['party'],
            ]);
        }

        $vicegov = [
            ['name' => 'Fritz Diaz', 'party' => 'Partido Federal ng Pilipinas'],
            ['name' => 'Jaime Reyes', 'party' => 'Independent'],
            ['name' => 'Erwin Vergara', 'party' => 'Independent'],
        ];

        foreach ($vicegov as $vice) {
            Candidate::create([
                'name' => $vice['name'],
                'position' => 'vice-governor',
                'party' => $vice['party'],
            ]);
        }
    }
}
