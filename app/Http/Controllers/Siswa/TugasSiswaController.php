<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TugasSiswa;
use App\Models\DataKelas;
use App\Models\DataMapel;
use Illuminate\Support\Str;

class TugasSiswaController extends Controller
{
    /**
     * Tampilkan form input tugas + daftar tugas siswa
     */
    public function index()
    {
        $user = Auth::user();
        $kelasUser = $user->dataSiswa ? $user->dataSiswa->kelas : null;

        // Ambil semua mapel beserta guru
        $mapel = DataMapel::with('guru.user')->get();

        // Ambil guru unik dari semua mapel, buang yang null, urutkan A-Z
        $gurus = $mapel->map(fn($m) => $m->guru)   // ambil relasi guru
            ->filter()                             // buang null
            ->unique('id')                          // unique berdasarkan ID
            ->sortBy(fn($g) => $g->user->name ?? ''); // urut A-Z

        // Ambil daftar tugas milik user login
        $tugas = TugasSiswa::where('user_id', $user->id)->latest()->get();

        return view('siswa.tugas', compact('kelasUser', 'gurus', 'tugas'))
            ->with('pageTitle', 'Input Tugas');
    }

    // public function index()
    // {
    //     $user = Auth::user();
    //     // $mapel = DataMapel::all();
    //     $kelasUser = $user->dataSiswa ? $user->dataSiswa->kelas : null;
    //     $kelasId = $user->dataSiswa ? $user->dataSiswa->kelas_id : null;

    //     // Ambil mapel untuk guru yang mengajar kelas siswa
    //     $mapel = DataMapel::whereHas('guru', function($q) use ($kelasId) {
    //         $q->where('kelas_id', $kelasId);
    //     })->with('guru.user')->get();

    //     // Ambil daftar tugas milik user login
    //     $tugas = TugasSiswa::where('user_id', $user->id)->latest()->get();

    //     return view('siswa.tugas', compact('kelasUser', 'mapel', 'tugas'))
    //         ->with('pageTitle', 'Input Tugas');
    // }

    /**
     * Simpan tugas baru
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'judul'      => 'required|string|max:255',
    //         'mapel_id'   => 'required|exists:data_mapel,id',
    //         'file_tugas' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,jpg,jpeg,png,gif,svg,webp,mp4,mkv,avi,mov,wmv,flv|max:51200',
    //     ]);

    //     $user = Auth::user();

    //     // Tentukan folder upload
    //     $folder = 'uploads/tugas';

    //     // Buat folder otomatis jika belum ada
    //     if (!\Storage::disk('public')->exists($folder)) {
    //         \Storage::disk('public')->makeDirectory($folder, 0755, true);
    //     }

    //     // Upload file
    //     $file = $request->file('file_tugas');
    //     $fileName = time() . '_' . $file->getClientOriginalName();
    //     $filePath = $file->storeAs($folder, $fileName, 'public');
    //     $kelasId = $user->dataSiswa ? $user->dataSiswa->kelas_id : null;

    //     TugasSiswa::create([
    //         'user_id'   => $user->id,
    //         'nama'      => $user->name,
    //         'judul'     => $request->judul,
    //         'kelompok'  => $request->kelompok,
    //         'kelas_id'  => $kelasId,
    //         'mapel_id'  => $request->mapel_id,
    //         'file_name' => $fileName,
    //         'file_path' => $filePath,
    //     ]);

    //     return redirect()->route('siswa.tugas.index')
    //         ->with('alert', [
    //             'message' => 'Tugas berhasil dibuat!',
    //             'type'    => 'success',
    //             'title'   => 'Berhasil',
    //         ]);
    // }

    public function store(Request $request)
    {
        $request->validate([
            'judul'    => 'required|string|max:255',
            'guru_id'  => 'required|exists:data_guru,id', // input dari form
            'file_tugas' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,jpg,jpeg,png,gif,svg,webp,mp4,mkv,avi,mov,wmv,flv|max:51200',
        ]);

        $user = Auth::user();

        // Cari mapel yang diajar guru terpilih
        $mapel = DataMapel::where('guru_id', $request->guru_id)->first();
        if (!$mapel) {
            return back()->withErrors(['guru_id' => 'Guru yang dipilih belum memiliki mapel.'])->withInput();
        }

        // Tentukan folder upload
        $folder = 'uploads/tugas';
        if (!\Storage::disk('public')->exists($folder)) {
            \Storage::disk('public')->makeDirectory($folder, 0755, true);
        }

        // Upload file dan rename otomatis: judul_user.ext
        $file = $request->file('file_tugas');
        $ext = $file->getClientOriginalExtension();
        $safeJudul = Str::slug($request->judul); // buat aman untuk filename
        $safeUser  = Str::slug($user->name);
        $fileName = "{$safeJudul}_{$safeUser}.{$ext}";
        $filePath = $file->storeAs($folder, $fileName, 'public');

        $kelasId = $user->dataSiswa?->kelas_id;

        // Simpan ke database
        TugasSiswa::create([
            'user_id'   => $user->id,
            'nama'      => $user->name,
            'judul'     => $request->judul,
            'kelompok'  => $request->kelompok,
            'kelas_id'  => $kelasId,
            'mapel_id'  => $mapel->id,
            'file_name' => $fileName,
            'file_path' => $filePath,
        ]);

        return redirect()->route('siswa.tugas.index')
            ->with('alert', [
                'message' => 'Tugas berhasil dibuat!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    public function update(Request $request, $id)
    {
        $tugas = TugasSiswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'judul'    => 'required|string|max:255',
            'guru_id'  => 'required|exists:data_guru,id',
            'file_tugas' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,rar,jpg,jpeg,png,gif,svg,webp,mp4,mkv,avi,mov,wmv,flv|max:51200',
        ]);

        // Cari mapel sesuai guru baru
        $mapel = DataMapel::where('guru_id', $request->guru_id)->first();
        if (!$mapel) {
            return back()->withErrors(['guru_id' => 'Guru yang dipilih belum memiliki mapel.'])->withInput();
        }

        $tugas->judul    = $request->judul;
        $tugas->kelompok = $request->kelompok;
        $tugas->mapel_id = $mapel->id;

        // Jika ada file baru, upload dan hapus file lama
        if ($request->hasFile('file_tugas')) {
            if ($tugas->file_path && \Storage::disk('public')->exists($tugas->file_path)) {
                \Storage::disk('public')->delete($tugas->file_path);
            }

            $file = $request->file('file_tugas');
            $ext = $file->getClientOriginalExtension();
            $safeJudul = Str::slug($request->judul);
            $safeUser  = Str::slug(Auth::user()->name);
            $fileName = "{$safeJudul}_{$safeUser}.{$ext}";
            $filePath = $file->storeAs('uploads/tugas', $fileName, 'public');

            $tugas->file_name = $fileName;
            $tugas->file_path = $filePath;
        }

        $tugas->save();

        return redirect()->route('siswa.tugas.index')
            ->with('alert', [
                'message' => 'Tugas berhasil diperbarui!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    /**
     * Hapus tugas
     */
    public function destroy($id)
    {
        $tugas = TugasSiswa::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hapus file jika ada
        if ($tugas->file_path && \Storage::disk('public')->exists($tugas->file_path)) {
            \Storage::disk('public')->delete($tugas->file_path);
        }

        $tugas->delete();

        return redirect()->route('siswa.tugas.index')
            ->with('alert', [
                'message' => 'Tugas berhasil dihapus!',
                'type'    => 'success',
                'title'   => 'Berhasil',
            ]);
    }

    public function download($id)
    {
        $tugas = TugasSiswa::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $filePath = $tugas->file_path;

        if (!\Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan');
        }

        return \Storage::disk('public')->download($filePath, $tugas->file_name);
    }

}
