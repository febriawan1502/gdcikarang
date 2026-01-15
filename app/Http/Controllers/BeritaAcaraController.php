<?php

namespace App\Http\Controllers;

use App\Models\BeritaAcara;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;


class BeritaAcaraController extends Controller
{
    // LIST / TABLE
    public function index()
    {
        $beritaAcaras = BeritaAcara::all();
        return view('material.berita-acara', compact('beritaAcaras'));
    }

    // FORM CREATE
    public function create()
    {
        return view('material.berita-acara-create');
    }

    // STORE DATA
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hari' => 'required',
            'tanggal' => 'required|date',
            'tanggal_teks' => 'required',
            'mengetahui' => 'required',
            'jabatan_mengetahui' => 'required',
            'pembuat' => 'required',
            'jabatan_pembuat' => 'required',
        ]);

        BeritaAcara::create($validated);

        return redirect()
            ->route('berita-acara.index')
            ->with('success', 'Berita Acara berhasil dibuat!');
    }

    // SHOW SURAT
    public function show($id)
    {
        $ba = BeritaAcara::findOrFail($id);
        return view('material.berita-acara-show', compact('ba'));
    }

    // EDIT FORM
    public function edit($id)
    {
        $ba = BeritaAcara::findOrFail($id);
        return view('material.berita-acara-edit', compact('ba'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'hari' => 'required',
            'tanggal' => 'required|date',
            'tanggal_teks' => 'required',
            'mengetahui' => 'required',
            'jabatan_mengetahui' => 'required',
            'pembuat' => 'required',
            'jabatan_pembuat' => 'required',
        ]);

        $ba = BeritaAcara::findOrFail($id);
        $ba->update($validated);

        return redirect()
            ->route('berita-acara.index')
            ->with('success', 'Berita Acara berhasil diperbarui!');
    }

    // DELETE
// DELETE
public function destroy($id)
{
    $ba = BeritaAcara::findOrFail($id);

    if ($ba->file_pdf && Storage::disk('local')->exists($ba->file_pdf)) {
        Storage::disk('local')->delete($ba->file_pdf);
    }

    $ba->delete();

    return redirect()
        ->route('berita-acara.index')
        ->with('success', 'Berita Acara berhasil dihapus!');

    }
    public function pdf($id)
{
    $ba = BeritaAcara::findOrFail($id);

    $pdf = Pdf::loadView('exports.berita-acara-pdf', compact('ba'))
        ->setPaper('A4', 'portrait');

    return $pdf->stream('Berita_Acara_'.$ba->id.'.pdf');
}

// UPLOAD PDF
public function uploadPdf(Request $request, $id)
{
    $request->validate([
        'file_pdf' => 'required|mimes:pdf|max:5120',
    ]);

    $ba = BeritaAcara::findOrFail($id);

    // hapus file lama
    if ($ba->file_pdf && Storage::disk('local')->exists($ba->file_pdf)) {
        Storage::disk('local')->delete($ba->file_pdf);
    }

    // SIMPAN & AMBIL PATH ASLI DARI LARAVEL
    $path = $request->file('file_pdf')
        ->store('berita-acara-pdf', 'local');

    // SIMPAN PATH INI KE DB
    $ba->update([
        'file_pdf' => $path
    ]);

    return back()->with('success', 'PDF berhasil diupload');
}

// VIEW PDF (STREAM)
public function viewUploadedPdf($id)
{
    
    $ba = BeritaAcara::findOrFail($id);

    if (!$ba->file_pdf || !Storage::disk('local')->exists($ba->file_pdf)) {
        abort(404);
    }

    return response()->file(storage_path('app/'.$ba->file_pdf));
}

}
