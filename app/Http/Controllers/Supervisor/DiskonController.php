<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diskon;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class DiskonController extends Controller
{
    public function index()
    {
        $this->autoPruneInactive();
        $discounts = \App\Models\Diskon::latest()->paginate(10);
        return view('supervisor.diskon.index', compact('discounts'));
    }

    /**
     * Automatically delete discounts that have been inactive for more than 5 days.
     */
    private function autoPruneInactive()
    {
        $threshold = now()->subDays(5);
        
        $inactiveDiscounts = Diskon::where('status', 'inactive')
            ->where('updated_at', '<', $threshold)
            ->get();

        if ($inactiveDiscounts->count() > 0) {
            foreach ($inactiveDiscounts as $d) {
                // Log deletion
                ActivityLog::create([
                    'id_user' => Auth::id() ?? 1, // System or current user
                    'action' => 'auto-delete',
                    'target' => $d->name,
                    'details' => 'Diskon otomatis dihapus karena tidak aktif > 5 hari'
                ]);
                
                $d->delete();
            }
        }
    }

    public function create()
    {
        return view('supervisor.diskon.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $diskon = Diskon::create($request->all());

        // Log Activity
        ActivityLog::create([
            'id_user' => Auth::id(),
            'action' => 'create',
            'target' => $diskon->name,
            'details' => 'Membuat diskon baru'
        ]);

        return redirect()->route('supervisor.diskon.index')->with('success', 'Diskon berhasil dibuat.');
    }

    public function edit($id)
    {
        $diskon = Diskon::findOrFail($id);
        return view('supervisor.diskon.edit', compact('diskon'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $diskon = Diskon::findOrFail($id);
        $diskon->update($request->all());

        // Log Activity
        ActivityLog::create([
            'id_user' => Auth::id(),
            'action' => 'update',
            'target' => $diskon->name,
            'details' => 'Memperbarui data diskon'
        ]);

        return redirect()->route('supervisor.diskon.index')->with('success', 'Diskon berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $diskon = Diskon::findOrFail($id);
        $name = $diskon->name;
        $diskon->delete();

        // Log Activity
        ActivityLog::create([
            'id_user' => Auth::id(),
            'action' => 'delete',
            'target' => $name,
            'details' => 'Menghapus diskon'
        ]);

        return redirect()->route('supervisor.diskon.index')->with('success', 'Diskon berhasil dihapus.');
    }
}
