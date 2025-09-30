<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\DataSiswa;
use App\Models\DataGuru;
use Illuminate\Support\Facades\Hash;
use App\Exports\UserExport;
use App\Imports\UserImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
            });
        }

        // Filter role dari request tapi batasi developer
        if ($request->filled('role')) {
            $role = $request->role;
            if ($role === 'developer' && auth()->user()->role !== 'developer') {
                $role = null;
            }
            if ($role) {
                $query->where('role', $role);
            }
        }

        // Hapus developer untuk non-developer
        if (auth()->user()->role !== 'developer') {
            $query->where('role', '!=', 'developer');
        }

        $allUsers = $query->orderBy('role')
                    ->orderBy('name')
                    ->paginate(10)
                    ->withQueryString();

        return view('admin.users.index', ['allUsers' => $allUsers]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required','string','max:255'],
            'email' => ['required','string','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
            'role' => ['required','in:user,siswa,guru,staff,admin'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Buat data siswa/guru otomatis jika perlu
        if ($user->role === 'siswa') {
            DataSiswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'status' => 'Aktif',
            ]);
        }

        if ($user->role === 'guru') {
            DataGuru::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'kode' => 'G'.str_pad($user->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        return back()->with('alert', [
            'message' => 'User baru berhasil ditambahkan!',
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => ['required','in:user,siswa,guru,staff,admin'],
            'new_password' => ['nullable','string','min:6']
        ]);

        $user->role = $request->role;
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
            $message = 'Role dan password pengguna berhasil diperbarui!';
        } else {
            $message = 'Role pengguna berhasil diperbarui!';
        }
        $user->save();

        // Buat data siswa/guru jika belum ada
        if ($user->role === 'siswa' && !DataSiswa::where('user_id',$user->id)->exists()) {
            DataSiswa::create([
                'user_id' => $user->id,
                'nama_lengkap' => $user->name,
                'status' => 'Aktif',
            ]);
        }

        if ($user->role === 'guru' && !DataGuru::where('user_id',$user->id)->exists()) {
            DataGuru::create([
                'user_id' => $user->id,
                'nama' => $user->name,
                'kode' => 'G'.str_pad($user->id, 3, '0', STR_PAD_LEFT),
            ]);
        }

        return back()->with('alert', [
            'message' => $message,
            'type' => 'success',
            'title' => 'Berhasil',
        ]);
    }

    // public function destroy($id)
    // {
    //     $user = User::findOrFail($id);

    //     if ($user->role === 'admin' && $user->id == 1) {
    //         return back()->with('alert', [
    //             'message' => 'Super Admin tidak boleh dihapus!',
    //             'type' => 'success',
    //             'title' => 'Berhasil'
    //         ]);
    //     }

    //     $user->delete();

    //     return redirect()->route('admin.users.index')->with('alert', [
    //         'message' => 'User berhasil dihapus!',
    //         'type' => 'success',
    //         'title' => 'Berhasil'
    //     ]);
    // }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->role === 'admin' && $user->id == 1) {
            return back()->with('alert', [
                'message' => 'Super Admin tidak boleh dihapus!',
                'type' => 'success',
                'title' => 'Berhasil'
            ]);
        }

        // Hapus relasi siswa/guru sebelum hapus user
        if ($user->role === 'siswa') {
            DataSiswa::where('user_id', $user->id)->delete();
        }

        if ($user->role === 'guru') {
            DataGuru::where('user_id', $user->id)->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('alert', [
            'message' => 'User berhasil dihapus!',
            'type' => 'success',
            'title' => 'Berhasil'
        ]);
    }

    public function export() { return Excel::download(new UserExport,'template_tambah_user.xlsx'); }

    public function import(Request $request)
    {
        $request->validate(['file'=>'required|mimes:xlsx,xls']);
        try {
            Excel::import(new UserImport,$request->file('file'));

            // pastikan data siswa dibuat
            User::where('role','siswa')->each(function($s){
                if(!DataSiswa::where('user_id',$s->id)->exists()){
                    DataSiswa::create([
                        'user_id'=>$s->id,
                        'nama_lengkap'=>$s->name,
                        'status'=>'Aktif',
                    ]);
                }
            });

            return back()->with('alert',[
                'message'=>'Data user berhasil diimport.',
                'type'=>'success',
                'title'=>'Berhasil'
            ]);
        } catch (\Exception $e) {
            return back()->with('alert',[
                'message'=>'Terjadi kesalahan saat import: '.$e->getMessage(),
                'type'=>'error',
                'title'=>'Gagal'
            ]);
        }
    }
}
