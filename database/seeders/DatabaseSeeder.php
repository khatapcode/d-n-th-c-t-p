<?php

namespace Database\Seeders;

use App\Models\ArtClass;
use App\Models\Artwork;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Invoice;
use App\Models\Review;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        File::ensureDirectoryExists(public_path('images/artworks'));

        $admin = User::updateOrCreate(
            ['email' => 'admin@madmun.art'],
            [
                'name' => 'Quản trị Madmun',
                'password' => Hash::make('password'),
                'role' => User::ROLE_ADMIN,
                'phone' => '0901000001',
                'is_active' => true,
            ]
        );

        $teachers = collect([
            ['name' => 'Minh Họa', 'email' => 'teacher@madmun.art', 'bio' => 'Giảng viên hội họa sơn dầu, 10 năm giảng dạy.', 'hourly_rate' => 200000],
            ['name' => 'Lan Màu', 'email' => 'lan@madmun.art', 'bio' => 'Chuyên màu nước và illustration.', 'hourly_rate' => 180000],
            ['name' => 'Khoa Hình', 'email' => 'khoa@madmun.art', 'bio' => 'Điêu khắc và hình họa khối.', 'hourly_rate' => 190000],
        ])->map(function ($row) {
            return User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_TEACHER,
                    'phone' => '0902' . rand(100000, 999999),
                    'bio' => $row['bio'],
                    'hourly_rate' => $row['hourly_rate'],
                    'is_active' => true,
                ]
            );
        });

        $students = collect([
            ['name' => 'An Nhiên', 'email' => 'student@madmun.art'],
            ['name' => 'Bảo Châu', 'email' => 'chau@madmun.art'],
            ['name' => 'Đức Huy', 'email' => 'huy@madmun.art'],
            ['name' => 'Hà My', 'email' => 'my@madmun.art'],
            ['name' => 'Ngọc Ánh', 'email' => 'anh@madmun.art'],
            ['name' => 'Trí Tâm', 'email' => 'tam@madmun.art'],
        ])->map(function ($row) {
            return User::updateOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $row['name'],
                    'password' => Hash::make('password'),
                    'role' => User::ROLE_STUDENT,
                    'phone' => '0903' . rand(100000, 999999),
                    'is_active' => true,
                ]
            );
        });

        $courseData = [
            ['name' => 'Hình họa cơ bản', 'level' => 'beginner', 'duration_hours' => 24, 'tuition_fee' => 3500000, 'is_featured' => true, 'description' => 'Luyện khối, tỷ lệ, ánh sáng cho người mới bắt đầu.'],
            ['name' => 'Màu nước sáng tạo', 'level' => 'intermediate', 'duration_hours' => 32, 'tuition_fee' => 4200000, 'is_featured' => true, 'description' => 'Kỹ thuật wet-on-wet, layer và biểu cảm màu.'],
            ['name' => 'Sơn dầu nâng cao', 'level' => 'advanced', 'duration_hours' => 40, 'tuition_fee' => 5800000, 'is_featured' => true, 'description' => 'Tác phẩm lớn, bố cục và chất liệu sơn dầu.'],
            ['name' => 'Digital Illustration', 'level' => 'intermediate', 'duration_hours' => 28, 'tuition_fee' => 4500000, 'is_featured' => false, 'description' => 'Minh họa kỹ thuật số cho portfolio.'],
        ];

        $existingImages = collect(range(1, 4))->map(fn ($n) => "course-$n.svg");

        $courses = collect($courseData)->map(function ($row, $i) use ($existingImages) {
            $row['status'] = 'active';
            $row['image'] = $existingImages[$i] ?? null;
            return Course::updateOrCreate(['name' => $row['name']], $row);
        });

        $classSpecs = [
            ['code' => 'HH-01', 'name' => 'Hình họa tối T2-T4', 'course' => 0, 'teacher' => 0, 'status' => 'ongoing', 'schedule' => 'T2-T4 18:00-20:00', 'room' => 'Studio A'],
            ['code' => 'MN-02', 'name' => 'Màu nước cuối tuần', 'course' => 1, 'teacher' => 1, 'status' => 'ongoing', 'schedule' => 'T7 09:00-12:00', 'room' => 'Studio B'],
            ['code' => 'SD-03', 'name' => 'Sơn dầu chiều T5', 'course' => 2, 'teacher' => 0, 'status' => 'upcoming', 'schedule' => 'T5 14:00-17:00', 'room' => 'Studio C'],
            ['code' => 'DI-04', 'name' => 'Digital tối T3', 'course' => 3, 'teacher' => 2, 'status' => 'ongoing', 'schedule' => 'T3 19:00-21:00', 'room' => 'Lab 1'],
        ];

        $classes = collect($classSpecs)->map(function ($row) use ($courses, $teachers) {
            return ArtClass::updateOrCreate(
                ['code' => $row['code']],
                [
                    'name' => $row['name'],
                    'course_id' => $courses[$row['course']]->id,
                    'teacher_id' => $teachers[$row['teacher']]->id,
                    'status' => $row['status'],
                    'schedule' => $row['schedule'],
                    'room' => $row['room'],
                    'start_date' => now()->subWeeks(3),
                    'end_date' => now()->addMonths(2),
                    'max_students' => 16,
                ]
            );
        });

        $classes[0]->members()->sync($students->take(4)->pluck('id')->mapWithKeys(fn ($id) => [$id => ['status' => 'approved', 'enrolled_at' => now()->subWeeks(3), 'decided_at' => now()->subWeeks(3)]]));
        $classes[1]->members()->sync($students->slice(2, 4)->pluck('id')->mapWithKeys(fn ($id) => [$id => ['status' => 'approved', 'enrolled_at' => now()->subWeeks(2), 'decided_at' => now()->subWeeks(2)]]));
        $classes[3]->members()->sync($students->take(3)->pluck('id')->mapWithKeys(fn ($id) => [$id => ['status' => 'approved', 'enrolled_at' => now()->subWeek(), 'decided_at' => now()->subWeek()]]));

        // Vài yêu cầu đang chờ duyệt để thử luồng phê duyệt.
        $classes[2]->members()->syncWithoutDetaching([
            $students[4]->id => ['status' => 'pending'],
            $students[5]->id => ['status' => 'pending'],
        ]);

        foreach ($students as $i => $student) {
            $class = $classes[$i % 3];
            Invoice::updateOrCreate(
                ['code' => 'INV-2026-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT)],
                [
                    'student_id' => $student->id,
                    'art_class_id' => $class->id,
                    'amount' => $class->course->tuition_fee,
                    'status' => $i % 3 === 0 ? 'unpaid' : 'paid',
                    'method' => $i % 3 === 0 ? null : 'bank',
                    'paid_at' => $i % 3 === 0 ? null : now()->subDays(10 - $i),
                ]
            );
        }

        foreach ($teachers as $i => $teacher) {
            SalaryPayment::updateOrCreate(
                [
                    'teacher_id' => $teacher->id,
                    'period_month' => now()->month,
                    'period_year' => now()->year,
                ],
                [
                    'teaching_hours' => 16 + $i * 4,
                    'amount' => ($teacher->hourly_rate ?? 180000) * (16 + $i * 4),
                    'status' => $i === 0 ? 'paid' : 'pending',
                    'paid_at' => $i === 0 ? now()->subDays(3) : null,
                ]
            );
        }

        foreach ($classes[0]->students as $j => $student) {
            Attendance::updateOrCreate(
                [
                    'art_class_id' => $classes[0]->id,
                    'student_id' => $student->id,
                    'session_date' => now()->toDateString(),
                ],
                ['status' => $j === 1 ? 'leave' : 'present']
            );
        }

        $sampleArt = collect(range(1, 6))->map(fn ($n) => "art-$n.svg");
        foreach ($sampleArt as $idx => $img) {
            Artwork::updateOrCreate(
                ['title' => 'Tác phẩm #' . ($idx + 1)],
                [
                    'user_id' => $students[$idx % $students->count()]->id,
                    'image' => $img,
                    'description' => 'Bài tập studio Madmun.Arthouse.',
                    'status' => $idx < 4 ? 'approved' : 'pending',
                    'reviewer_id' => $idx < 4 ? $admin->id : null,
                ]
            );
        }

        Review::query()->delete();
        Review::insert([
            ['author_name' => 'Phụ huynh Minh Anh', 'content' => 'Con tiến bộ rõ sau 2 tháng hình họa.', 'rating' => 5, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['author_name' => 'Học viên Hà My', 'content' => 'Giáo viên nhận xét tác phẩm rất chi tiết.', 'rating' => 5, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
            ['author_name' => 'Học viên Đức Huy', 'content' => 'Không gian studio đẹp, lịch học rõ ràng.', 'rating' => 4, 'is_visible' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
