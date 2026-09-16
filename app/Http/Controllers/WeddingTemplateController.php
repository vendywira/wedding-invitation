<?php

namespace App\Http\Controllers;

use App\Models\WeddingTemplate;
use Illuminate\Http\Request;

class WeddingTemplateController extends Controller
{
    public function index()
    {
        $templates = WeddingTemplate::all();

        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:wedding_templates,slug',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:500',
            'sections_config' => 'nullable|array',
            'styling_config' => 'nullable|array',
            'assets_config' => 'nullable|array',
        ]);

        $template = WeddingTemplate::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template berhasil ditambahkan',
            'template' => $template,
        ]);
    }

    public function update(Request $request, $id)
    {
        $template = WeddingTemplate::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string|max:500',
            'sections_config' => 'nullable|array',
            'styling_config' => 'nullable|array',
            'assets_config' => 'nullable|array',
        ]);

        $template->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Template berhasil diperbarui',
            'template' => $template,
        ]);
    }

    public function destroy($id)
    {
        $template = WeddingTemplate::findOrFail($id);

        if ($template->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Template aktif tidak bisa dihapus. Aktifkan template lain terlebih dahulu.',
            ], 400);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template berhasil dihapus',
        ]);
    }

    public function activate($id)
    {
        $template = WeddingTemplate::findOrFail($id);
        $template->activateTemplate();

        return response()->json([
            'success' => true,
            'message' => "Template \"{$template->name}\" berhasil diaktifkan!",
            'template' => $template,
            'settings_url' => route('admin.dashboard').'#settings',
            'invitation_url' => url('/invitation'),
        ]);
    }

    public function preview($id)
    {
        $template = WeddingTemplate::findOrFail($id);

        return response()->json([
            'template' => $template,
            'preview_url' => url('/invitation'),
        ]);
    }
}
