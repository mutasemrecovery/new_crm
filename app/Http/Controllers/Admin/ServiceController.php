<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('clients')->latest()->get();
        return view('admin.services.index', compact('services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                        => 'required|string|max:255',
            'name_en'                     => 'nullable|string|max:255',
            'color'                       => 'nullable|string|max:20',
            'icon'                        => 'nullable|string|max:50',
            'description'                 => 'nullable|string',
            // ── الجديد ──
            'service_type'                => 'nullable|in:recurring,project',
            'estimated_minutes_per_unit'  => 'nullable|integer|min:1',
        ]);

        $data['slug']      = Str::slug($data['name_en'] ?? $data['name']);
        $data['is_active'] = true;
        // default values if not provided
        $data['service_type']               = $data['service_type'] ?? 'recurring';
        $data['estimated_minutes_per_unit'] = $data['estimated_minutes_per_unit'] ?? 60;

        Service::create($data);
        return back()->with('success', __('admin.created_successfully', ['item' => __('admin.service')]));
    }

    public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'                        => 'required|string|max:255',
            'name_en'                     => 'nullable|string|max:255',
            'color'                       => 'nullable|string|max:20',
            'icon'                        => 'nullable|string|max:50',
            'description'                 => 'nullable|string',
            'is_active'                   => 'nullable',
            // ── الجديد ──
            'service_type'                => 'nullable|in:recurring,project',
            'estimated_minutes_per_unit'  => 'nullable|integer|min:1',
        ]);

        $data['is_active'] = $request->has('is_active');

        $service->update($data);
        return back()->with('success', __('admin.updated_successfully', ['item' => __('admin.service')]));
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', __('admin.deleted_successfully', ['item' => __('admin.service')]));
    }
}