@extends('layouts.app')

@section('title', 'Pengaturan Aplikasi')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#company" data-toggle="tab" aria-expanded="true">
                        <i class="fa fa-building"></i> Pengaturan Perusahaan
                    </a>
                </li>
                <li class="">
                    <a href="#users" data-toggle="tab" aria-expanded="false">
                        <i class="fa fa-users"></i> Manajemen User
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <!-- Tab Pengaturan Perusahaan -->
                <div class="tab-pane active" id="company">
                    <div class="row">
                        <div class="col-md-6">
                            <form action="{{ route('settings.company.update') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Nama Perusahaan</label>
                                    <input type="text" name="company_name" class="form-control" 
                                           value="{{ \App\Models\Setting::get('company_name', 'PT PLN (Persero)') }}" required>
                                    <small class="text-muted">Nama perusahaan yang akan muncul di header dokumen.</small>
                                </div>

                                <div class="form-group">
                                    <label>Nama Unit (UP3)</label>
                                    <input type="text" name="up3_name" class="form-control" 
                                           value="{{ \App\Models\Setting::get('up3_name', 'UP3 Cimahi') }}" required>
                                    <small class="text-muted">Nama unit yang akan muncul di dokumen dan label barcode.</small>
                                </div>

                                <div class="form-group">
                                    <label>Lokasi Gudang</label>
                                    <input type="text" name="warehouse_location" class="form-control" 
                                           value="{{ \App\Models\Setting::get('warehouse_location') }}">
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-save"></i> Simpan Pengaturan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tab Manajemen User -->
                <div class="tab-pane" id="users">
                    <div class="row mb-3">
                        <div class="col-md-12 text-right" style="margin-bottom: 15px;">
                            <button class="btn btn-success" data-toggle="modal" data-target="#modalAddUser">
                                <i class="fa fa-plus"></i> Tambah User Baru
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Bergabung</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="label label-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'user' ? 'primary' : 'default') }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-xs btn-warning btn-edit-user" 
                                                data-id="{{ $user->id }}"
                                                data-name="{{ $user->name }}"
                                                data-email="{{ $user->email }}"
                                                data-role="{{ $user->role }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        
                                        @if($user->id !== auth()->id())
                                        <button class="btn btn-xs btn-danger" onclick="confirmDeleteUser({{ $user->id }})">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <form id="delete-user-{{ $user->id }}" action="{{ route('settings.users.delete', $user->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        @endif
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
</div>

<!-- Modal Add User -->
<div class="modal fade" id="modalAddUser" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tambah User Baru</h4>
            </div>
            <form action="{{ route('settings.users.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                            <option value="security">Security</option>
                            <option value="guest">Guest</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit User</h4>
            </div>
            <form id="formEditUser" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="user">User</option>
                            <option value="admin">Administrator</option>
                             <option value="security">Security</option>
                            <option value="guest">Guest</option>
                            <option value="petugas">Petugas</option>
                        </select>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control" minlength="6">
                    </div>
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .nav-tabs-custom {
        background: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .nav-tabs {
        border-bottom: 2px solid #ddd;
        margin-bottom: 20px;
    }
    .nav-tabs > li.active > a, 
    .nav-tabs > li.active > a:hover, 
    .nav-tabs > li.active > a:focus {
        border: none;
        border-bottom: 2px solid #3c8dbc;
        color: #444;
    }
    .btn-xs {
        padding: 1px 5px;
        font-size: 12px;
        line-height: 1.5;
        border-radius: 3px;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Edit User Modal
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
            
            $('#modalEditUser').modal('show');
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
