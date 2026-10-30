<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Pastikan hanya Admin yang dapat mengakses manajemen akun.
     */
    protected function checkAdminAuthorization(): void
    {
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Akses Ditolak. Halaman Kelola Pengguna hanya dapat diakses oleh Administrator.');
        }
    }

    /**
     * Menampilkan daftar seluruh akun login (Admin Saja).
     */
    public function index(Request $request)
    {
        $this->checkAdminAuthorization();

        $search = $request->input('search');
        $role = $request->input('role');

        $users = User::query()
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->orderBy('role', 'asc')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users', 'search', 'role'));
    }

    /**
     * Form tambah akun pengguna (Admin Saja).
     */
    public function create()
    {
        $this->checkAdminAuthorization();

        return view('users.create');
    }

    /**
     * Simpan akun pengguna baru (Admin Saja).
     */
    public function store(Request $request)
    {
        $this->checkAdminAuthorization();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role' => ['required', 'in:admin,staff'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'role.required' => 'Peran pengguna wajib dipilih.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => $validated['password'], // di-hash otomatis oleh model casts
        ]);

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna baru berhasil ditambahkan.');
    }

    /**
     * Form edit akun pengguna (Admin Saja).
     */
    public function edit(User $user)
    {
        $this->checkAdminAuthorization();

        return view('users.edit', compact('user'));
    }

    /**
     * Perbarui data akun pengguna (Admin Saja).
     */
    public function update(Request $request, User $user)
    {
        $this->checkAdminAuthorization();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'role' => ['required', 'in:admin,staff'],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        // Mencegah admin menghapus hak admin diri sendiri jika ia admin terakhir
        if ($user->id === auth()->id() && $user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->withInput()->with('error', 'Anda adalah satu-satunya administrator. Anda tidak dapat mengubah peran Anda sendiri.');
            }
        }

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = $validated['password'];
        }

        $user->update($data);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data akun pengguna berhasil diperbarui.');
    }

    /**
     * Hapus akun pengguna (Admin Saja).
     */
    public function destroy(User $user)
    {
        $this->checkAdminAuthorization();

        // 1. Tidak boleh menghapus akun yang sedang login saat ini
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan.');
        }

        // 2. Tidak boleh menghapus admin jika hanya tersisa 1 admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Tidak dapat menghapus administrator terakhir di sistem.');
            }
        }

        // 3. Cek riwayat transaksi terkait agar integritas data aman
        if ($user->sales()->exists() || $user->purchases()->exists()) {
            return back()->with('error', "Pengguna {$user->name} tidak dapat dihapus karena tercatat dalam riwayat transaksi (penjualan/pembelian).");
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Akun pengguna berhasil dihapus dari sistem.');
    }
}
