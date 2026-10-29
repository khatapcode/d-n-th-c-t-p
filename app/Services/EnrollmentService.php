<?php

namespace App\Services;

use App\Models\ArtClass;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class EnrollmentService
{
    /**
     * Duyệt một yêu cầu đăng ký lớp và tạo học phí tương ứng (nếu có).
     */
    public function approve(ArtClass $class, User $student): ?Invoice
    {
        $member = $class->members()->where('users.id', $student->id)->first();

        if (!$member || $member->pivot->status !== 'pending') {
            throw ValidationException::withMessages(['enrollment' => 'Yêu cầu đăng ký không còn ở trạng thái chờ duyệt.']);
        }

        if ($class->isFull()) {
            throw ValidationException::withMessages(['enrollment' => 'Lớp đã đủ học viên, hãy tăng sĩ số trước khi duyệt.']);
        }

        $class->members()->updateExistingPivot($student->id, [
            'status' => 'approved',
            'enrolled_at' => now(),
            'decided_at' => now(),
        ]);

        return $this->createTuitionInvoice($class, $student);
    }

    public function reject(ArtClass $class, User $student, ?string $note = null): void
    {
        $class->members()->updateExistingPivot($student->id, [
            'status' => 'rejected',
            'note' => $note,
            'decided_at' => now(),
        ]);
    }

    public function createTuitionInvoice(ArtClass $class, User $student): ?Invoice
    {
        $amount = (int) ($class->course->tuition_fee ?? 0);

        if ($amount <= 0) {
            return null;
        }

        $exists = Invoice::where('student_id', $student->id)
            ->where('art_class_id', $class->id)
            ->exists();

        if ($exists) {
            return null;
        }

        return Invoice::create([
            'code' => 'HP' . now()->format('ymd') . '-' . strtoupper(substr(uniqid(), -5)),
            'student_id' => $student->id,
            'art_class_id' => $class->id,
            'amount' => $amount,
            'status' => 'unpaid',
            'note' => 'Học phí lớp ' . $class->name,
        ]);
    }
}
