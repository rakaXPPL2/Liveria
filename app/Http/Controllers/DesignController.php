<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Closure;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignController extends Controller
{
    public function index()
    {
        // Eloquent Eager Loading menghitung jumlah part BOM
        $designs = Design::withCount('bomItems')->latest()->get();
        return view('designs.index', compact('designs'));
    }

    public function create()
    {
        return view('designs.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code_id'       => 'required|unique:designs',
            'title'         => 'required|string|max:255',
            'workflow_type' => 'required|in:solidworks,blender,hybrid',
            'material'      => 'nullable|string',
            'mass_grams'    => 'nullable|numeric',
            'poly_count'    => 'nullable|string',
            'render_image'  => 'nullable|image|max:4096',
            'glb_file'      => 'nullable|file|max:30720',
            'stl_file'      => $this->modelFileRules('stl'),
            'blend_file'    => $this->modelFileRules('blend'),
            'part_names.*'  => 'nullable|string',
            'quantities.*'  => 'nullable|integer|min:1',
            'spec_notes.*'  => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('render_image')) {
            $data['render_image'] = $request->file('render_image')->store('renders', 'public');
        }
        if ($request->hasFile('glb_file')) {
            $data['glb_file'] = $request->file('glb_file')->store('models', 'public');
        }
        foreach (['stl_file', 'blend_file'] as $fileField) {
            if ($request->hasFile($fileField)) {
                $data[$fileField] = $request->file($fileField)->store('models', 'public');
            }
        }

        $design = Design::create($data);

        // Simpan relasi Eloquent ke tabel bom_items
        if ($request->filled('part_names')) {
            foreach ($request->part_names as $index => $partName) {
                if (!empty($partName)) {
                    $design->bomItems()->create([
                        'part_name' => $partName,
                        'quantity'  => $request->quantities[$index] ?? 1,
                        'spec_note' => $request->spec_notes[$index] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('designs.index')->with('success', 'Data 3D berhasil disimpan.');
    }

    public function show(Design $design)
    {
        $design->load('bomItems');
        return view('designs.show', compact('design'));
    }

    public function edit(Design $design)
    {
        return view('designs.edit', compact('design'));
    }

    public function update(Request $request, Design $design)
    {
        $data = $request->validate([
            'code_id'       => 'required|unique:designs,code_id,' . $design->id,
            'title'         => 'required|string|max:255',
            'workflow_type' => 'required|in:solidworks,blender,hybrid',
            'material'      => 'nullable|string',
            'mass_grams'    => 'nullable|numeric',
            'poly_count'    => 'nullable|string',
            'render_image'  => 'nullable|image|max:4096',
            'glb_file'      => 'nullable|file|max:30720',
            'stl_file'      => $this->modelFileRules('stl'),
            'blend_file'    => $this->modelFileRules('blend'),
            'part_names'    => 'nullable|array',
            'part_names.*'  => 'nullable|string',
            'quantities'    => 'nullable|array',
            'quantities.*'  => 'nullable|integer|min:1',
            'spec_notes.*'  => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('render_image')) {
            if ($design->render_image) Storage::disk('public')->delete($design->render_image);
            $data['render_image'] = $request->file('render_image')->store('renders', 'public');
        }
        if ($request->hasFile('glb_file')) {
            if ($design->glb_file) Storage::disk('public')->delete($design->glb_file);
            $data['glb_file'] = $request->file('glb_file')->store('models', 'public');
        }
        foreach (['stl_file', 'blend_file'] as $fileField) {
            if ($request->hasFile($fileField)) {
                if ($design->{$fileField}) {
                    Storage::disk('public')->delete($design->{$fileField});
                }

                $data[$fileField] = $request->file($fileField)->store('models', 'public');
            }
        }

        $design->update($data);
        $design->bomItems()->delete();

        if ($request->filled('part_names')) {
            foreach ($request->input('part_names') as $index => $partName) {
                if (filled($partName)) {
                    $design->bomItems()->create([
                        'part_name' => $partName,
                        'quantity' => $request->input("quantities.$index", 1),
                        'spec_note' => $request->input("spec_notes.$index"),
                    ]);
                }
            }
        }

        return redirect()->route('designs.index')->with('success', 'Data 3D berhasil diupdate.');
    }

    public function destroy(Design $design)
    {
        if ($design->render_image) Storage::disk('public')->delete($design->render_image);
        if ($design->glb_file) Storage::disk('public')->delete($design->glb_file);
        foreach (['stl_file', 'blend_file'] as $fileField) {
            if ($design->{$fileField}) {
                Storage::disk('public')->delete($design->{$fileField});
            }
        }

        $design->delete();
        return redirect()->route('designs.index')->with('success', 'Data 3D dihapus.');
    }

    /**
     * @return array<int, string|Closure>
     */
    private function modelFileRules(string $extension): array
    {
        return [
            'nullable',
            'file',
            'extensions:'.$extension,
            'max:30720',
            function (string $attribute, mixed $value, Closure $fail) use ($extension): void {
                if (! $value instanceof UploadedFile) {
                    return;
                }

                $realPath = $value->getRealPath();
                if ($realPath === false) {
                    $fail("Berkas {$extension} tidak dapat dibaca.");

                    return;
                }

                $file = fopen($realPath, 'rb');
                if ($file === false) {
                    $fail("Berkas {$extension} tidak dapat dibaca.");

                    return;
                }

                $header = fread($file, 1024);
                fclose($file);
                if ($header === false) {
                    $fail("Berkas {$extension} tidak dapat dibaca.");

                    return;
                }

                if ($extension === 'blend' && ! str_starts_with($header, 'BLENDER')) {
                    $fail('Berkas harus memiliki format Blender (.blend) yang valid.');

                    return;
                }

                if ($extension === 'stl' && ! $this->isValidStl($header, $value->getSize())) {
                    $fail('Berkas harus memiliki format STL yang valid.');
                }
            },
        ];
    }

    private function isValidStl(string $header, int|false $fileSize): bool
    {
        if ($fileSize === false) {
            return false;
        }

        $asciiHeader = strtolower(ltrim($header));
        if (str_starts_with($asciiHeader, 'solid') && str_contains($asciiHeader, 'facet normal')) {
            return true;
        }

        if (strlen($header) < 84) {
            return false;
        }

        $triangleCount = unpack('Vcount', substr($header, 80, 4))['count'];

        return $triangleCount > 0 && $fileSize === 84 + 50 * $triangleCount;
    }
}