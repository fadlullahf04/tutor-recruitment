<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\StudyProgram;
use App\Models\Faculty;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_index_with_search_and_sort()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@test.com',
            'password' => 'secret123',
            'role' => 'super_admin'
        ]);

        $faculty = Faculty::create(['name' => 'Fakultas Tarbiyah']);
        $program = StudyProgram::create(['faculty_id' => $faculty->id, 'name' => 'PJJ PAI']);

        $course1 = Course::create([
            'study_program_id' => $program->id,
            'code' => 'PAI-101',
            'name' => 'Sejarah Islam',
            'credits' => 3,
            'semester' => 1
        ]);

        $course2 = Course::create([
            'study_program_id' => $program->id,
            'code' => 'PAI-201',
            'name' => 'Filsafat Pendidikan',
            'credits' => 2,
            'semester' => 3
        ]);

        // Test normal list
        $response = $this->actingAs($admin)->get(route('admin.master.courses.index'));
        $response->assertStatus(200);
        $response->assertSee('Daftar Mata Kuliah');
        $response->assertSee('Pencarian Mata Kuliah');

        // Test search
        $responseSearch = $this->actingAs($admin)->get(route('admin.master.courses.index', ['search' => 'PAI-101']));
        $responseSearch->assertStatus(200);
        $responseSearch->assertSee('PAI-101');

        // Test sorting by study_program
        $responseSortProgram = $this->actingAs($admin)->get(route('admin.master.courses.index', [
            'sort_by' => 'study_program',
            'sort_direction' => 'desc'
        ]));
        $responseSortProgram->assertStatus(200);

        // Test sorting by semester
        $responseSortSemester = $this->actingAs($admin)->get(route('admin.master.courses.index', [
            'sort_by' => 'semester',
            'sort_direction' => 'desc'
        ]));
        $responseSortSemester->assertStatus(200);
    }

    public function test_store_course_allows_same_code()
    {
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin2@test.com',
            'password' => 'secret123',
            'role' => 'super_admin'
        ]);

        $faculty = Faculty::create(['name' => 'Fakultas Tarbiyah']);
        $program1 = StudyProgram::create(['faculty_id' => $faculty->id, 'name' => 'PJJ PAI']);
        $program2 = StudyProgram::create(['faculty_id' => $faculty->id, 'name' => 'PJJ SPI']);

        Course::create([
            'study_program_id' => $program1->id,
            'code' => 'MK-101',
            'name' => 'Mata Kuliah Dasar 1',
            'credits' => 3,
            'semester' => 1
        ]);

        $response = $this->actingAs($admin)->post(route('admin.master.courses.store'), [
            'study_program_id' => $program2->id,
            'code' => 'MK-101', // duplicate code
            'name' => 'Mata Kuliah Dasar 2',
            'credits' => 3,
            'semester' => 1
        ]);

        $response->assertRedirect(route('admin.master.courses.index'));
        $this->assertDatabaseCount('courses', 2);
    }
}
