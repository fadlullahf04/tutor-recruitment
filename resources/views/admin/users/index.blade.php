@extends('layouts.app')

@section('title', 'Kelola Pengguna')
@section('header_title', 'Kelola Akun Pengguna')
@section('header_subtitle', 'Kelola hak akses untuk Super Admin, Admin, dan Calon Tutor.')

@section('content')
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <form action="{{ route('admin.users.index') }}" method="GET" style="display: flex; gap: 0.5rem; flex: 1; max-width: 500px;">
            <input type="text" name="search" class="form-control" value="{{ $search }}" placeholder="Cari nama atau email...">
            <select name="role" class="form-control form-select" style="width: 150px;">
                <option value="">-- Semua Role --</option>
                <option value="super_admin" {{ $role === 'super_admin' ? 'selected' : '' }}>Super Admin</option>
                <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin PJJ</option>
                <option value="tutor" {{ $role === 'tutor' ? 'selected' : '' }}>Tutor</option>
            </select>
            <button type="submit" class="btn btn-primary">Cari</button>
            @if($search || $role)
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Reset</a>
            @endif
        </form>

        <button type="button" class="btn btn-primary" onclick="openAddUserModal()">
            <i class="fa-solid fa-user-plus"></i> Tambah Pengguna
        </button>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa-solid fa-users"></i> Daftar Akun Pengguna</h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Tanggal Terdaftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td style="font-weight: 600;">{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->isSuperAdmin())
                                        <span class="badge" style="background-color: #f1f5f9; color: #0f172a; border: 1px solid #cbd5e1;">SUPER ADMIN</span>
                                    @elseif($user->isAdmin())
                                        <span class="badge" style="background-color: #e0f2fe; color: #0369a1;">ADMIN PJJ</span>
                                    @else
                                        <span class="badge badge-draft">TUTOR</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->translatedFormat('d M Y') }}</td>
                                <td>
                                    <div style="display: flex; gap: 0.5rem;">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}')">
                                            Edit <i class="fa-solid fa-pen"></i>
                                        </button>
                                        @if($user->id !== Auth::id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus akun ini? Akun profil yang berkaitan akan ikut terhapus.');">
                                                    Hapus <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: var(--color-text-muted); padding: 3rem 0;">
                                    Tidak ada data pengguna ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div style="padding: 1rem 1.5rem; border-top: 1px solid #e2e8f0; display: flex; justify-content: center;">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Tambah User -->
    <div class="modal-overlay" id="add-user-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-user-plus"></i> Tambah Akun Pengguna</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeAddUserModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama Lengkap <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label for="email" class="form-label">Alamat Email <span style="color: var(--color-danger);">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="tutor@uinsi.ac.id" required>
                    </div>
                    <div class="form-group">
                        <label for="role" class="form-label">Role Akses <span style="color: var(--color-danger);">*</span></label>
                        <select id="role" name="role" class="form-control form-select" required>
                            <option value="tutor">Tutor (Calon Tutor)</option>
                            <option value="admin">Admin UPT PJJ</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password <span style="color: var(--color-danger);">*</span></label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeAddUserModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal-overlay" id="edit-user-modal">
        <div class="modal-container">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-user-pen"></i> Edit Akun Pengguna</h3>
                <button type="button" class="btn btn-outline btn-sm" style="padding: 0.25rem 0.5rem;" onclick="closeEditUserModal()"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form id="edit-user-form" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="edit_name" class="form-label">Nama Lengkap <span style="color: var(--color-danger);">*</span></label>
                        <input type="text" id="edit_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_email" class="form-label">Alamat Email <span style="color: var(--color-danger);">*</span></label>
                        <input type="email" id="edit_email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_role" class="form-label">Role Akses <span style="color: var(--color-danger);">*</span></label>
                        <select id="edit_role" name="role" class="form-control form-select" required>
                            <option value="tutor">Tutor (Calon Tutor)</option>
                            <option value="admin">Admin UPT PJJ</option>
                            <option value="super_admin">Super Admin</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_password" class="form-label">Password Baru (Isi jika ingin diubah)</label>
                        <input type="password" id="edit_password" name="password" class="form-control" placeholder="Biarkan kosong jika tidak diubah" minlength="6">
                    </div>
                </div>
                <div class="card-footer" style="padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 0.75rem; border-top: 1px solid #e2e8f0; background-color: var(--color-bg-primary);">
                    <button type="button" class="btn btn-outline" onclick="closeEditUserModal()">Batal</button>
                    <button type="submit" class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function openAddUserModal() {
            document.getElementById('add-user-modal').style.display = 'flex';
        }
        
        function closeAddUserModal() {
            document.getElementById('add-user-modal').style.display = 'none';
        }

        function openEditUserModal(id, name, email, role) {
            document.getElementById('edit-user-form').action = "{{ url('/admin/users') }}/" + id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_role').value = role;
            document.getElementById('edit-user-modal').style.display = 'flex';
        }
        
        function closeEditUserModal() {
            document.getElementById('edit-user-modal').style.display = 'none';
        }
    </script>
@endsection
