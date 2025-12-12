<?php

namespace App\Http\Controllers;

use App\Models\SupplierSchedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SupplierDelayedNotification;

class SupplierScheduleController extends Controller
{
    // Admin melihat semua jadwal
    public function index(Request $request)
    {
        $query = SupplierSchedule::with('supplier')
            ->orderByDesc('expected_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('admin.schedules.index', [
            'schedules' => $query->paginate(15),
        ]);
    }

    // Admin membuat jadwal kedatangan supplier
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id'   => ['required', 'exists:users,id'],
            'expected_date' => ['required', 'date'],
        ]);

        SupplierSchedule::create([
            'supplier_id'   => $validated['supplier_id'],
            'expected_date' => $validated['expected_date'],
            'status'        => 'scheduled',
        ]);

        return back()->with('success', 'Jadwal supplier ditambahkan.');
    }

    // Supplier mengisi realisasi kedatangan (actual_date)
    public function arrive(Request $request, SupplierSchedule $schedule)
    {
        // pastikan supplier yang bersangkutan
        if (Auth::user()->id !== $schedule->supplier_id) {
            abort(403);
        }

        $validated = $request->validate([
            'actual_date' => ['required', 'date'],
        ]);

        $schedule->actual_date = $validated['actual_date'];
        $schedule->status = $schedule->actual_date > $schedule->expected_date ? 'delayed' : 'arrived';
        $schedule->save();

        // Notifikasi ke admin jika delayed
        if ($schedule->status === 'delayed') {
            $admins = User::role('admin')->get();
            Notification::send($admins, new SupplierDelayedNotification($schedule));
        }

        return back()->with('success', 'Realisasi kedatangan dicatat.');
    }

    // Admin mengubah status manual jika perlu
    public function updateStatus(Request $request, SupplierSchedule $schedule)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:scheduled,delayed,arrived'],
        ]);

        $schedule->status = $validated['status'];
        $schedule->save();

        return back()->with('success', 'Status jadwal diperbarui.');
    }
}
