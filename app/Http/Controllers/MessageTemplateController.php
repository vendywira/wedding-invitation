<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class MessageTemplateController extends Controller
{
    public function index()
    {
        $templates = MessageTemplate::orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'template' => 'required|string',
            'is_active' => 'boolean',
            'is_default' => 'boolean'
        ]);

        // Jika di set sebagai default, non-aktifkan default lainnya
        if ($request->is_default) {
            MessageTemplate::where('is_default', true)->update(['is_default' => false]);
        }

        $template = MessageTemplate::create($validated);

        return response()->json([
            'success' => true,
            'template' => $template
        ]);
    }

    public function setDefault($id)
    {
        // Non-aktifkan semua template default
        MessageTemplate::where('is_default', true)->update(['is_default' => false]);

        // Set template yang dipilih sebagai default
        $template = MessageTemplate::findOrFail($id);
        $template->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Template default berhasil diubah'
        ]);
    }

    public function destroy($id)
    {
        $template = MessageTemplate::findOrFail($id);

        // Jangan hapus template default
        if ($template->is_default) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat menghapus template default'
            ], 422);
        }

        $template->delete();

        return response()->json([
            'success' => true,
            'message' => 'Template berhasil dihapus'
        ]);
    }

    public function getActiveTemplates()
    {
        $templates = MessageTemplate::where('is_active', true)
            ->orderBy('is_default', 'desc')
            ->orderBy('name')
            ->get();

        return response()->json($templates);
    }
}
