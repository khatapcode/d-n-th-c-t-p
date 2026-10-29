<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArtClass;
use App\Models\Invoice;
use App\Models\SalaryPayment;
use App\Models\User;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');

        $invoiceQuery = Invoice::with(['student', 'artClass'])->latest();
        if (in_array($status, ['unpaid', 'pending', 'paid'])) {
            $invoiceQuery->where('status', $status);
        }
        $invoices = $invoiceQuery->paginate(10, ['*'], 'invoices')->withQueryString();
        $salaries = SalaryPayment::with('teacher')->latest()->paginate(10, ['*'], 'salaries');

        $summary = [
            'paid' => Invoice::where('status', 'paid')->sum('amount'),
            'unpaid' => Invoice::where('status', 'unpaid')->sum('amount'),
            'pending' => Invoice::where('status', 'pending')->sum('amount'),
            'pending_count' => Invoice::where('status', 'pending')->count(),
            'salary_paid' => SalaryPayment::where('status', 'paid')->sum('amount'),
            'salary_pending' => SalaryPayment::where('status', 'pending')->sum('amount'),
        ];

        $students = User::where('role', User::ROLE_STUDENT)->where('is_active', true)->orderBy('name')->get();
        $artClasses = ArtClass::orderBy('name')->get();

        return view('admin.finance.index', compact('invoices', 'salaries', 'summary', 'students', 'artClasses', 'status'));
    }

    public function confirmInvoice(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'paid',
            'paid_at' => now(),
            'method' => $invoice->method ?: 'cash',
        ]);

        return back()->with('success', 'Đã xác nhận thanh toán hóa đơn ' . $invoice->code . '.');
    }

    /** Trả hóa đơn về trạng thái chưa nộp khi học viên báo sai. */
    public function rejectPayment(Invoice $invoice)
    {
        $invoice->update([
            'status' => 'unpaid',
            'paid_at' => null,
            'paid_note' => null,
        ]);

        return back()->with('success', 'Đã trả hóa đơn ' . $invoice->code . ' về trạng thái chưa thanh toán.');
    }

    public function confirmSalary(SalaryPayment $salary)
    {
        $salary->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Đã xác nhận chi lương giáo viên.');
    }

    public function storeInvoice(Request $request)
    {
        $data = $request->validate([
            'student_id' => 'required|exists:users,id',
            'art_class_id' => 'nullable|exists:art_classes,id',
            'amount' => 'required|integer|min:0',
            'note' => 'nullable|string|max:500',
        ]);

        $code = 'INV-' . strtoupper(uniqid());
        Invoice::create([
            'code' => $code,
            'student_id' => $data['student_id'],
            'art_class_id' => $data['art_class_id'] ?? null,
            'amount' => $data['amount'],
            'status' => 'unpaid',
            'note' => $data['note'] ?? null,
        ]);

        return back()->with('success', 'Đã tạo hóa đơn ' . $code . '.');
    }
}
