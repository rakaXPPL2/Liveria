# LiveriaWeb: Panduan Lengkap Backend CRUD, Eloquent, Middleware, & Integrasi API

> Dokumentasi pembelajaran arsitektur backend Laravel 12 pada aplikasi **Liveria 3D Engineering & Modeling Hub (SolidWorks CAD & Blender 3D)**.

---

## Daftar Isi
1. [Arsitektur & Konsep Utama](#1-arsitektur--konsep-utama)
2. [Tabel Database & Migrations](#2-tabel-database--migrations)
3. [Eloquent ORM & Relasi Antar Tabel](#3-eloquent-orm--relasi-antar-tabel)
4. [Routing & Proteksi Middleware](#4-routing--proteksi-middleware)
5. [Autentikasi Login & Manajemen Session](#5-autentikasi-login--manajemen-session)
6. [Implementasi CRUD Lengkap & File Upload](#6-implementasi-crud-lengkap--file-upload)
7. [REST API Endpoint & Integrasi Frontend Asynchronous](#7-rest-api-endpoint--integrasi-frontend-asynchronous)
8. [Panduan Menjalankan Proyek](#8-panduan-menjalankan-proyek)

---

## 1. Arsitektur & Konsep Utama

Aplikasi ini dibangun menggunakan arsitektur **MVC (Model-View-Controller)** yang dikombinasikan dengan **Headless REST API**:

```
Pengguna (Browser)
    │
    ├── [Request HTTP Web] ──> routes/web.php ──> Middleware (auth/guest) ──> Web Controller ──> Blade View
    │                                                                               │
    │                                                                               └── Eloquent Model <──> Database (SQLite)
    │                                                                               │
    └── [Fetch API Asinkron] ─> routes/api.php ─────────────────────────────> API Controller ──> Respon JSON
```

### Komponen Inti
- **Framework**: Laravel 12 (PHP 8.5)
- **Database**: SQLite (`database/database.sqlite`)
- **Frontend Engine**: Blade Templates + Tailwind CSS + Alpine.js
- **3D Visualization**: Google `<model-viewer>` Web Component (GLB/GLTF)
- **Storage**: Symlink Public Disk (`storage/app/public` ke `public/storage`)

---

## 2. Tabel Database & Migrations

Database terdiri atas dua tabel utama yang saling berelasi: `designs` dan `bom_items` (*Bill of Materials*).

### A. Tabel `designs`
File migration: `database/migrations/2026_11_23_003608_create_designs_table.php`

```php
Schema::create('designs', function (Blueprint $table) {
    $table->id();                                                   // Primary Key auto-increment
    $table->string('code_id')->unique();                           // Kode unik part (contoh: CAD-001, BLD-001)
    $table->string('title');                                       // Nama part atau model 3D
    $table->enum('workflow_type', ['solidworks', 'blender', 'hybrid']); // Kategori pipeline desain
    $table->string('material')->nullable();                        // Atribut spesifik CAD SolidWorks
    $table->decimal('mass_grams', 8, 2)->nullable();               // Berat dalam gram (presisi 2 desimal)
    $table->string('poly_count')->nullable();                      // Kerapatan polygon model Blender (misal: "1.25M")
    $table->string('render_image')->nullable();                    // Path gambar thumbnail 2D
    $table->string('glb_file')->nullable();                        // Path file model 3D interaktif (.glb)
    $table->timestamps();                                          // created_at & updated_at otomatis
});
```

### B. Tabel `bom_items` (Bill of Materials)
Komponen penyusun dari tiap desain. Berelasi *one-to-many* ke tabel `designs`.

```php
Schema::create('bom_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('design_id')                                 // Foreign key ke tabel designs
          ->constrained('designs')
          ->onDelete('cascade');                                   // Hapus BOM otomatis jika design dihapus
    $table->string('part_name');                                   // Nama komponen
    $table->integer('quantity')->default(1);                       // Jumlah kuantitas kebutuhan
    $table->string('spec_note')->nullable();                       // Catatan spesifikasi teknis / toleransi
    $table->timestamps();
});
```

> **Catatan Penting**: Fitur `->onDelete('cascade')` memastikan tidak terjadi *orphan data* (data komponen BOM tertinggal di database saat desain induknya dihapus).

---

## 3. Eloquent ORM & Relasi Antar Tabel

Eloquent ORM memetakan tabel database ke dalam class PHP object-oriented.

### A. Model `Design` (`app/Models/Design.php`)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    // Mencegah Mass Assignment Exception saat pemanggilan Design::create($data)
    protected $fillable = [
        'code_id',
        'title',
        'workflow_type',
        'material',
        'mass_grams',
        'poly_count',
        'render_image',
        'glb_file',
    ];

    // Relasi 1-ke-Banyak (One-to-Many): 1 Desain memiliki banyak item BOM
    public function bomItems()
    {
        return $this->hasMany(BomItem::class);
    }
}
```

### B. Model `BomItem` (`app/Models/BomItem.php`)
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BomItem extends Model
{
    protected $fillable = [
        'design_id',
        'part_name',
        'quantity',
        'spec_note',
    ];

    // Relasi Kebalikan (Belongs To): Komponen BOM milik 1 Desain induk
    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
```

### C. Teknik Eager Loading & Count Query
Untuk menghindari masalah performa **N+1 Query Problem**, Laravel menyediakan fungsi eager loading:

```php
// 1. Menghitung jumlah relasi tanpa memuat seluruh datanya ke memori:
$designs = Design::withCount('bomItems')->latest()->get();
// Menghasilkan atribut virtual: $item->bom_items_count

// 2. Memuat relasi data sekaligus (hanya 2 query dieksekusi):
$designs = Design::with('bomItems')->get();
```

---

## 4. Routing & Proteksi Middleware

Routing memisahkan akses pengguna umum (publik) dan pengguna berwenang (administrator/engineer).

### A. Route Web (`routes/web.php`)
```php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesignController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Beranda (Landing Page)
Route::get('/', fn() => view('landing'))->name('home');

// 2. Rute Autentikasi (Hanya untuk tamu yang belum login)
Route::get('/login',   [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login',  [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 3. CRUD Terproteksi (Hanya admin/engineer yang login)
// PENTING: /designs/create WAJIB didaftarkan SEBELUM wildcard /designs/{design}
Route::middleware('auth')->group(function () {
    Route::get('/designs/create',        [DesignController::class, 'create'])->name('designs.create');
    Route::post('/designs',              [DesignController::class, 'store'])->name('designs.store');
    Route::get('/designs/{design}/edit', [DesignController::class, 'edit'])->name('designs.edit')->whereNumber('design');
    Route::put('/designs/{design}',      [DesignController::class, 'update'])->name('designs.update')->whereNumber('design');
    Route::delete('/designs/{design}',   [DesignController::class, 'destroy'])->name('designs.destroy')->whereNumber('design');
});

// 4. Katalog Terbuka (Publik)
Route::get('/designs',          [DesignController::class, 'index'])->name('designs.index');
Route::get('/designs/{design}', [DesignController::class, 'show'])->name('designs.show')->whereNumber('design');
```

### B. Fitur Unggulan Routing Laravel
1. **Pentingnya Route Order (Urutan Rute)**: Jika rute `/designs/{design}` diletakkan di atas `/designs/create`, Laravel akan menangkap kata `"create"` sebagai parameter `$design` dan menghasilkan error 404 Model Not Found. Oleh karena itu, rute statis harus diletakkan sebelum wildcard atau dibatasi dengan `->whereNumber('design')`.
2. **Route Model Binding**: Parameter `{design}` pada URL `/designs/{design}` secara otomatis di-*inject* oleh Laravel menjadi instance `$design = Design::findOrFail($id)`.
3. **Middleware `auth`**: Mencegah akses ke form tambah/edit/hapus jika pengguna belum login, dan secara otomatis me-redirect ke rute `/login`.
4. **Middleware `guest`**: Mencegah pengguna yang sudah login untuk membuka form login kembali.

---

## 5. Autentikasi Login & Manajemen Session

File: `app/Http/Controllers/AuthController.php`

```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Verifikasi Kredensial via Hash Password
        if (Auth::attempt($credentials)) {
            // 3. Mencegah Session Fixation Attack
            $request->session()->regenerate();

            return redirect()->intended(route('designs.index'));
        }

        // 4. Jika Gagal, Kembalikan dengan Pesan Error
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Bersihkan dan amankan sesi
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('designs.index');
    }
}
```

### Penerapan Directives di Blade
Di file Blade (`layouts/app.blade.php`), kita mengontrol tampilan tombol berdasarkan status login:

```blade
@guest
    {{-- Tampil HANYA jika pengunjung belum login --}}
    <a href="{{ route('login') }}">Admin Login</a>
@endguest

@auth
    {{-- Tampil HANYA jika pengguna telah terautentikasi --}}
    <span>Halo, {{ auth()->user()->name }}</span>
    <a href="{{ route('designs.create') }}">+ New 3D Part</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endauth
```

---

## 6. Implementasi CRUD Lengkap & File Upload

File: `app/Http/Controllers/DesignController.php`

Controller ini mengelola siklus hidup data teknis 3D secara komprehensif:

### A. Create / Simpan Data (`store`)
Menangani validasi, upload file fisik ke disk `public`, dan penyimpanan relasi array komponen BOM:

```php
public function store(Request $request)
{
    // 1. Validasi Request
    $data = $request->validate([
        'code_id'       => 'required|unique:designs,code_id',
        'title'         => 'required|string|max:255',
        'workflow_type' => 'required|in:solidworks,blender,hybrid',
        'material'      => 'nullable|string',
        'mass_grams'    => 'nullable|numeric|min:0',
        'poly_count'    => 'nullable|string',
        'render_image'  => 'nullable|image|max:4096',             // Maks 4 MB
        'glb_file'      => 'nullable|file|max:30720',             // Format 3D maks 30 MB
        'part_names.*'  => 'nullable|string',
        'quantities.*'  => 'nullable|integer|min:1',
        'spec_notes.*'  => 'nullable|string|max:255',
    ]);

    // 2. Upload File Gambar jika dilampirkan
    if ($request->hasFile('render_image')) {
        $data['render_image'] = $request->file('render_image')->store('renders', 'public');
    }

    // 3. Upload File 3D (.glb) jika dilampirkan
    if ($request->hasFile('glb_file')) {
        $data['glb_file'] = $request->file('glb_file')->store('models', 'public');
    }

    // 4. Simpan Record Desain Induk
    $design = Design::create($data);

    // 5. Simpan Item-Item BOM Relasional
    if ($request->filled('part_names')) {
        foreach ($request->input('part_names') as $index => $partName) {
            if (filled($partName)) {
                $design->bomItems()->create([
                    'part_name' => $partName,
                    'quantity'  => $request->input("quantities.$index", 1),
                    'spec_note' => $request->input("spec_notes.$index"),
                ]);
            }
        }
    }

    return redirect()->route('designs.index')->with('success', 'Data 3D berhasil disimpan.');
}
```

### B. Update & Hapus File Lama (`update`)
Saat model di-*update* dan pengguna melampirkan berkas baru, berkas lama wajib dihapus agar media storage tidak membengkak:

```php
public function update(Request $request, Design $design)
{
    $data = $request->validate([
        'code_id'       => 'required|unique:designs,code_id,' . $design->id, // Kecualikan ID saat ini
        'title'         => 'required|string|max:255',
        'workflow_type' => 'required|in:solidworks,blender,hybrid',
        'material'      => 'nullable|string',
        'mass_grams'    => 'nullable|numeric',
        'poly_count'    => 'nullable|string',
        'render_image'  => 'nullable|image|max:4096',
        'glb_file'      => 'nullable|file|max:30720',
        'part_names.*'  => 'nullable|string',
        'quantities.*'  => 'nullable|integer|min:1',
        'spec_notes.*'  => 'nullable|string',
    ]);

    // Update File Render (Hapus file lama dari disk public)
    if ($request->hasFile('render_image')) {
        if ($design->render_image) {
            Storage::disk('public')->delete($design->render_image);
        }
        $data['render_image'] = $request->file('render_image')->store('renders', 'public');
    }

    // Update File GLB (Hapus file 3D lama)
    if ($request->hasFile('glb_file')) {
        if ($design->glb_file) {
            Storage::disk('public')->delete($design->glb_file);
        }
        $data['glb_file'] = $request->file('glb_file')->store('models', 'public');
    }

    // Update data utama
    $design->update($data);

    // Re-sinkronisasi BOM (Hapus data lama lalu input kembali baris terkini)
    $design->bomItems()->delete();
    if ($request->filled('part_names')) {
        foreach ($request->input('part_names') as $index => $partName) {
            if (filled($partName)) {
                $design->bomItems()->create([
                    'part_name' => $partName,
                    'quantity'  => $request->input("quantities.$index", 1),
                    'spec_note' => $request->input("spec_notes.$index"),
                ]);
            }
        }
    }

    return redirect()->route('designs.index')->with('success', 'Data 3D berhasil diupdate.');
}
```

### C. Delete Permanen (`destroy`)
```php
public function destroy(Design $design)
{
    // Hapus file fisik dari storage
    if ($design->render_image) {
        Storage::disk('public')->delete($design->render_image);
    }
    if ($design->glb_file) {
        Storage::disk('public')->delete($design->glb_file);
    }

    // Hapus record dari database (bom_items terhapus otomatis via cascade)
    $design->delete();

    return redirect()->route('designs.index')->with('success', 'Data 3D berhasil dihapus.');
}
```

---

## 7. REST API Endpoint & Integrasi Frontend Asynchronous

### A. Rute API (`routes/api.php`)
```php
use App\Http\Controllers\Api\DesignApiController;
use Illuminate\Support\Facades\Route;

Route::get('/designs', [DesignApiController::class, 'index']);
```

### B. Controller API (`app/Http/Controllers/Api/DesignApiController.php`)
Controller bertugas mengambil data Eloquent, memfilter berdasarkan query URL (`?type=`), menyertakan relasi BOM, dan memformat URL file publik:

```php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;

class DesignApiController extends Controller
{
    public function index(Request $request)
    {
        // 1. Eager load relasi bomItems
        $query = Design::with('bomItems');

        // 2. Filter opsional berdasarkan parameter URL ?type=
        if ($request->filled('type') && in_array($request->query('type'), ['solidworks', 'blender', 'hybrid'], true)) {
            $query->where('workflow_type', $request->query('type'));
        }

        // 3. Transform data: tambahkan properti URL absolut file GLB
        $designs = $query->latest()->get()->map(function (Design $design) {
            $design->glb_url = $design->glb_file
                ? asset('storage/' . $design->glb_file)
                : null;
            return $design;
        });

        // 4. Return respon standar JSON dengan kode 200 OK
        return response()->json($designs);
    }
}
```

### C. Integrasi Frontend Tanpa Reload (`Fetch API` di `designs/index.blade.php`)
Pengguna memilih dropdown kategori -> JavaScript mengeksekusi request HTTP ke API -> Tabel ter-update instan tanpa me-refresh halaman:

```javascript
const filter  = document.getElementById('category-filter');
const tbody   = document.getElementById('design-table-body');
const loading = document.getElementById('api-loading');

filter.addEventListener('change', async () => {
    const type = filter.value;
    const url = type ? `/api/designs?type=${encodeURIComponent(type)}` : '/api/designs';

    loading.classList.remove('hidden');
    tbody.innerHTML = '';

    try {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error('API Error');

        const designs = await res.json();

        // Render baris tabel secara dinamis
        tbody.innerHTML = designs.map(item => `
            <tr>
                <td>${item.code_id}</td>
                <td><a href="/designs/${item.id}">${item.title}</a></td>
                <td>${item.workflow_type}</td>
                <td>${item.bom_items ? item.bom_items.length : 0} parts</td>
                <td><a href="/designs/${item.id}">View 3D</a></td>
            </tr>
        `).join('');
    } catch (e) {
        tbody.innerHTML = '<tr><td colspan="5">Gagal memuat data.</td></tr>';
    } finally {
        loading.classList.add('hidden');
    }
});
```

---

## 8. Panduan Menjalankan Proyek

### A. Persyaratan Sistem
- PHP >= 8.2 (Disarankan PHP 8.4 / 8.5)
- SQLite3
- Composer

### B. Langkah Instalasi & Menjalankan
```bash
# 1. Masuk ke direktori proyek
cd c:\laragon\www\LiveriaWeb

# 2. Salin environment & generate app key (jika baru di-clone)
cp .env.example .env
php artisan key:generate

# 3. Jalankan migration database & seed data percontohan
php artisan migrate:fresh --seed

# 4. Buat symlink storage publik agar file upload dapat diakses browser
php artisan storage:link

# 5. Jalankan server lokal
php artisan serve
```

### C. Akun Login Default
- **URL**: `http://localhost:8000/login` (atau `http://liveria.test/login` di Laragon)
- **Email**: `admin@liveria.test`
- **Password**: `password123`

### D. Pengujian Endpoint REST API
Dapat diuji melalui browser, Postman, atau curl:
```bash
# Ambil seluruh desain
curl http://localhost:8000/api/designs

# Ambil khusus model Blender (.blend)
curl http://localhost:8000/api/designs?type=blender

# Ambil khusus model CAD SolidWorks
curl http://localhost:8000/api/designs?type=solidworks
```

---

*LiveriaWeb &copy; 2026 — 3D Engineering &amp; Modeling Hub.*
