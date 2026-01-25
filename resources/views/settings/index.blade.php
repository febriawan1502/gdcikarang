@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">⚙️ Pengaturan Aplikasi</h2>
            <p class="text-gray-500 text-sm mt-1">Kelola pengaturan perusahaan dan manajemen user.</p>
        </div>
    </div>

    <!-- Tabs -->
    <div class="card border border-gray-100 shadow-xl shadow-gray-200/50">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-100">
            <nav class="flex gap-1 px-6 pt-4">
                <button onclick="switchTab('company')" id="tab-company" 
                        class="tab-btn active px-4 py-3 text-sm font-medium rounded-t-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-building"></i>
                    <span>Pengaturan Perusahaan</span>
                </button>
                <button onclick="switchTab('users')" id="tab-users" 
                        class="tab-btn px-4 py-3 text-sm font-medium rounded-t-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    <span>Manajemen User</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Tab: Pengaturan Perusahaan -->
            <div id="content-company" class="tab-content">
                <div class="max-w-xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-400 to-teal-500 flex items-center justify-center text-white">
                            <i class="fas fa-cog"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Informasi Perusahaan</h3>
                    </div>

                    <form action="{{ route('settings.company.update') }}" method="POST">
                        @csrf
                        <div class="space-y-5">
                            <div>
                                <label class="form-label">Nama Perusahaan</label>
                                <input type="text" name="company_name" class="form-input" 
                                       value="{{ \App\Models\Setting::get('company_name', 'PT PLN (Persero)') }}" required>
                                <p class="text-sm text-gray-500 mt-1">Nama perusahaan yang akan muncul di header dokumen.</p>
                            </div>

                            <div>
                                <label class="form-label">Nama Unit (UP3)</label>
                                <input type="text" name="up3_name" class="form-input" 
                                       value="{{ \App\Models\Setting::get('up3_name', 'UP3 Cimahi') }}" required>
                                <p class="text-sm text-gray-500 mt-1">Nama unit yang akan muncul di dokumen dan label barcode.</p>
                            </div>

                            <div>
                                <label class="form-label">Lokasi Gudang</label>
                                <input type="text" name="warehouse_location" class="form-input" 
                                       value="{{ \App\Models\Setting::get('warehouse_location') }}">
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="btn-teal px-6 py-3 shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center gap-2">
                                    <i class="fas fa-save"></i>
                                    <span>Simpan Pengaturan</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tab: Manajemen User -->
            <div id="content-users" class="tab-content hidden">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-400 to-purple-500 flex items-center justify-center text-white">
                            <i class="fas fa-user-cog"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800">Daftar User</h3>
                    </div>
                    
                    <button onclick="openModal('modalAddUser')" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors flex items-center gap-2">
                        <i class="fas fa-plus"></i>
                        <span>Tambah User Baru</span>
                    </button>
                </div>

                <div class="overflow-x-auto rounded-xl border border-gray-100">
                    <table class="table-purity w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider w-12">No</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Unit</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Role</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider">Bergabung</th>
                                <th class="px-4 py-3 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 bg-white">
                            @foreach($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-center text-gray-600">{{ $loop->iteration }}</td>
                                <td class="px-4 py-3 text-gray-800 font-medium">{{ $user->nama }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $user->unit->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($user->role == 'admin')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Admin</span>
                                    @elseif($user->role == 'user')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">User</span>
                                    @elseif($user->role == 'security')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Security</span>
                                    @elseif($user->role == 'petugas')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Petugas</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-end items-center gap-2">
                                        <button class="p-2 rounded-lg text-yellow-600 hover:bg-yellow-50 transition-colors btn-edit-user" 
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->nama }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}"
                                                data-unit-id="{{ $user->unit_id }}"
                                                title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        @if($user->id !== auth()->id())
                                        <button class="p-2 rounded-lg text-red-500 hover:bg-red-50 transition-colors" 
                                                onclick="confirmDeleteUser({{ $user->id }})"
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <form id="delete-user-{{ $user->id }}" action="{{ route('settings.users.delete', $user->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add User -->
<div id="modalAddUser" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-40 transition-opacity" onclick="closeModal('modalAddUser')"></div>
        
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative">
            <form action="{{ route('settings.users.store') }}" method="POST">
                @csrf
                <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Tambah User Baru
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeModal('modalAddUser')">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">NIP</label>
                        <input type="text" name="nip" class="form-input">
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required minlength="6">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Role</label>
                        <select name="role" class="form-input" required>
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                            <option value="security">Security</option>
                            <option value="guest">Guest</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Unit</label>
                        <select name="unit_id" class="form-input" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->plant }} / {{ $unit->storage_location }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeModal('modalAddUser')">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div id="modalEditUser" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="fixed inset-0 bg-gray-800 bg-opacity-40 transition-opacity" onclick="closeModal('modalEditUser')"></div>
        
        <div class="inline-block bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full relative">
            <form id="formEditUser" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                            <i class="fas fa-user-edit"></i>
                            Edit User
                        </h3>
                        <button type="button" class="text-white hover:text-gray-200 transition-colors" onclick="closeModal('modalEditUser')">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Role</label>
                        <select name="role" id="edit_role" class="form-input" required>
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                            <option value="security">Security</option>
                            <option value="guest">Guest</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Unit</label>
                        <select name="unit_id" id="edit_unit_id" class="form-input" required>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->plant }} / {{ $unit->storage_location }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <hr class="border-gray-200">
                    
                    <div>
                        <label class="form-label">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-input" minlength="6">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-input">
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                    <button type="button" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition-colors" onclick="closeModal('modalEditUser')">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 rounded-lg bg-yellow-500 text-white hover:bg-yellow-600 transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .tab-btn {
        color: #6b7280;
        border-bottom: 2px solid transparent;
        margin-bottom: -1px;
    }
    .tab-btn:hover {
        color: #374151;
    }
    .tab-btn.active {
        color: #0d9488;
        border-bottom-color: #0d9488;
        background-color: #f0fdfa;
    }
</style>
@endpush

@push('scripts')
<script>
    // Tab switching
    function switchTab(tabName) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(c => c.classList.add('hidden'));
        // Remove active from all tabs
        document.querySelectorAll('.tab-btn').forEach(t => t.classList.remove('active'));
        
        // Show selected content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        // Mark tab as active
        document.getElementById('tab-' + tabName).classList.add('active');
    }

    // Modal functions
    function openModal(id) {
        document.getElementById(id).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.add('hidden');
        document.body.style.overflow = '';
    }

    $(document).ready(function() {
        // Edit User Button
        $('.btn-edit-user').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            var email = $(this).data('email');
            var role = $(this).data('role');
            
            var url = "{{ route('settings.users.update', ':id') }}";
            url = url.replace(':id', id);
            
            $('#formEditUser').attr('action', url);
            $('#edit_name').val(name);
            $('#edit_email').val(email);
            $('#edit_role').val(role);
            $('#edit_unit_id').val($(this).data('unit-id'));
            
            openModal('modalEditUser');
        });
    });

    function confirmDeleteUser(userId) {
        Swal.fire({
            title: 'Hapus User?',
            text: "User yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-user-' + userId).submit();
            }
        })
    }
</script>
@endpush
