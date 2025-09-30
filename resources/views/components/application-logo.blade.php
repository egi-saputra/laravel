{{-- @php
use Illuminate\Support\Facades\Storage;

$logoUrl = $profil?->file_path
    ? Storage::url($profil->file_path)
    : 'https://via.placeholder.com/50';
@endphp

<img src="{{ $logoUrl }}" alt="Logo Sekolah" class="object-contain w-12 h-12"> --}}

{{-- ============================================================================= --}}

{{-- @php
use Illuminate\Support\Facades\Storage;

$logoUrl = $profil?->file_path
    ? Storage::url($profil->file_path) . '?v=' . ($profil->updated_at?->timestamp ?? time())
    : asset('images/default-logo.png');
@endphp

<img src="{{ $logoUrl }}" alt="Logo Sekolah" class="object-contain w-12 h-12"> --}}

{{-- =================================================================================== --}}

@php
    use App\Models\ProfilSekolah;
    use Illuminate\Support\Facades\File;

    $profil = ProfilSekolah::first();

    // Tentukan path logo
    $filePath = $profil && $profil->file_path
        ? storage_path('app/public/' . ltrim($profil->file_path, '/'))
        : storage_path('app/public/logo_sekolah/default-logo.png');

    // Ambil last modified (buat query string biar cache bust kalau update)
    $lastModified = file_exists($filePath) ? filemtime($filePath) : time();
@endphp

<img src="{{ url('/logo-sekolah') }}?v={{ $lastModified }}"
     alt="Logo Sekolah"
     class="object-contain w-12 h-12">


