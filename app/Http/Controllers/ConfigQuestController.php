<?php

namespace App\Http\Controllers;

use App\Models\ConfigQuest;
use App\Models\ConfigRumus;
use Illuminate\Http\Request;

class ConfigQuestController extends Controller
{
    /**
     * Halaman konfigurasi quest
     */
    public function indexQuest()
    {
        $quests = ConfigQuest::orderBy('order')->get();
        return view('admin.config-quest.index', compact('quests'));
    }

    /**
     * Edit quest
     */
    public function updateQuest(Request $request, $id)
    {
        $quest = ConfigQuest::findOrFail($id);

        $validated = $request->validate([
            'label' => 'required|string|max:500',
            'description' => 'nullable|string',
            'type' => 'required|in:radio,dropdown,text,textarea',
            'options' => 'nullable|string',
            'is_active' => 'nullable|boolean', 
            'order' => 'required|integer',
            'conditional_target' => 'nullable|json', // Pastikan divalidasi sebagai JSON
        ]);

        $quest->label = $validated['label'];
        $quest->description = $validated['description'];
        $quest->type = $validated['type'];
        $quest->order = $validated['order'];
        
        $quest->is_active = $request->boolean('is_active');

        // Parsing options jika type adalah 'radio' ATAU 'dropdown'
        if (in_array($validated['type'], ['radio', 'dropdown']) && !empty($validated['options'])) {
            $quest->options = array_map('trim', explode(',', $validated['options']));
        } else {
            $quest->options = null;
        }

        // PERBAIKAN KRUSIAL: Menyimpan conditional target
        if ($request->has('conditional_target') && !empty($request->conditional_target)) {
            // Karena sudah divalidasi sebagai JSON, kita decode
            $decodedTarget = json_decode($request->conditional_target, true);
            
            // Simpan ke model (Laravel akan meng-cast ke format JSON database)
            $quest->conditional_target = $decodedTarget;
        } else {
            // Jika field tidak ada atau kosong (aturan dihapus), set null
            $quest->conditional_target = null;
        }

        $quest->save();

        return redirect()->route('admin.config.quest')
            ->with('success', 'Quest berhasil diupdate');
    }

    /**
     * Halaman konfigurasi rumus
     */
    public function indexRumus()
    {
        $rumus = ConfigRumus::all();
        return view('admin.config-rumus.index', compact('rumus'));
    }

    /**
     * Edit rumus
     */
    public function editRumus($id)
    {
        $rumus = ConfigRumus::findOrFail($id);
        $quests = ConfigQuest::where('is_active', true)->get();
        return view('admin.config-rumus.edit', compact('rumus', 'quests'));
    }

    /**
     * Update rumus
     */
    public function updateRumus(Request $request, $id)
    {
        $rumus = ConfigRumus::findOrFail($id);

        $validated = $request->validate([
            'nama_rumus' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kondisi_bekerja' => 'required|json',
            'kondisi_pengangguran' => 'required|json',
            'is_active' => 'nullable|boolean',
        ]);

        $rumus->nama_rumus = $validated['nama_rumus'];
        $rumus->deskripsi = $validated['deskripsi'];
        $rumus->kondisi_bekerja = json_decode($validated['kondisi_bekerja'], true);
        $rumus->kondisi_pengangguran = json_decode($validated['kondisi_pengangguran'], true);
        
        $rumus->is_active = $request->boolean('is_active'); 

        $rumus->save();

        return redirect()->route('admin.config.rumus')
            ->with('success', 'Rumus berhasil diupdate');
    }

    /**
     * Activate/deactivate rumus
     */
    public function toggleRumus($id)
    {
        $rumus = ConfigRumus::findOrFail($id);

        // Nonaktifkan semua rumus lain
        ConfigRumus::where('id', '!=', $id)->update(['is_active' => false]);

        // Aktifkan rumus ini
        $rumus->is_active = true;
        $rumus->save();

        return redirect()->route('admin.config.rumus')
            ->with('success', 'Rumus berhasil diaktifkan');
    }
}