<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $teacher = [
            'name' => 'Teacher',
            'email' => 'teacher@exams.com',
            'password' => bcrypt('password'),
        ];

        Teacher::create($teacher);
    }
}
