{{-- @php
    use App\Models\ProfilSekolah;
    use Illuminate\Support\Facades\File;

    $profil = ProfilSekolah::first();

    $filePath = $profil && $profil->file_path
        ? storage_path('app/public/' . ltrim($profil->file_path, '/'))
        : storage_path('app/public/logo_sekolah/default-logo.png');

    $lastModified = file_exists($filePath) ? filemtime($filePath) : time();
@endphp

<img src="{{ url('/logo-sekolah') }}?v={{ $lastModified }}"
     alt="Logo Sekolah"
     {{ $attributes->merge(['class' => 'object-contain w-12 h-12']) }}> --}}


{{-- =================================================================================== --}}

@php
    use App\Models\ProfilSekolah;

    $profil = ProfilSekolah::first();

    $logoUrl = $profil && $profil->file_path
        ? asset('storage/' . ltrim($profil->file_path, '/')) // file dari storage
        : asset('storage/logo_sekolah/default-logo.png');     // fallback default
@endphp

<img src="{{ $logoUrl }}"
     alt="Logo Sekolah"
     {{ $attributes->merge(['class' => 'object-contain w-12 h-12']) }}>



