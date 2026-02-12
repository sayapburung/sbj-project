@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Manajemen User</h4>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            + Tambah User
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER SEARCH --}}
    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama user..."
                   value="{{ request('search') }}">

            <button class="btn btn-dark">Cari</button>

            @if(request('search'))
                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- TABEL USER --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px">#</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Dibuat</th>
                            <th>Last Login</th>
                            <th>IP Address</th>
                            <th style="width: 260px">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $i => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $i }}</td>

                            <td>{{ $user->name }}</td>

                            <td>{{ $user->email }}</td>

                            <td>
                                <span class="badge bg-success">
                                    {{ $user->role->name ?? '-' }}
                                </span>
                            </td>

                            <td>{{ $user->created_at->format('d M Y') }}</td>

                            {{-- LAST LOGIN --}}
                            <td>
                                @if($user->last_login_at)
                                    {{ \Carbon\Carbon::parse($user->last_login_at)->format('d-m-Y H:i') }}
                                @else
                                    -
                                @endif
                            </td>

                            {{-- IP --}}
                            <td>{{ $user->last_login_ip ?? '-' }}</td>

                            {{-- AKSI --}}
                            <td>
                                <div class="d-flex flex-wrap gap-1">

                                    {{-- EDIT --}}
                                    <a href="{{ route('users.edit', $user->id) }}"
                                       class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- AKTIF / NONAKTIF --}}
                                    <form action="{{ route('users.toggle', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="btn btn-sm btn-info"
                                                onclick="return confirm('{{ $user->is_active ? 'Nonaktifkan user ini?' : 'Aktifkan user ini?' }}')">
                                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- RESET PASSWORD --}}
                                    <form action="{{ route('users.resetPassword', $user->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                                class="btn btn-sm btn-secondary"
                                                onclick="return confirm('Reset password user ke default?')">
                                            Reset
                                        </button>
                                    </form>

                                    {{-- DELETE --}}
                                    <button class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $user->id }}">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </div>
                            </td>
                        </tr>

                        {{-- MODAL HAPUS --}}
                        <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Apakah yakin ingin menghapus user
                                        <strong>{{ $user->name }}</strong>?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-3">
                                Belum ada data user.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-3 mb-3 d-flex justify-content-center">
                {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>

</div>

{{-- CSS Tambahan --}}
<style>
    td form {
        margin: 0;
    }

    .pagination {
        font-size: 13px;
    }

    .pagination .page-link {
        padding: 5px 10px;
    }
</style>
@endsection
