@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Role Management</h4>

        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            + Tambah Role
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- TABLE ROLE --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Role</th>
                            <th>Permissions</th>
                            <th style="width:90px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($roles as $i => $role)
                        <tr>
                            <td>{{ $i + 1 }}</td>

                            {{-- ROLE NAME --}}
                            <td>
                                <strong>{{ $role->name }}</strong>
                            </td>

                            {{-- PERMISSIONS --}}
                            <td>
                                @if(!empty($role->permissions))
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($role->permissions as $perm)
                                            <span class="badge bg-success">
                                                {{ $perm }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted fst-italic">
                                        Tidak ada permission
                                    </span>
                                @endif
                            </td>

                            {{-- ACTION DROPDOWN --}}
                            <td class="text-center">

                                <div class="dropdown">

                                    <button class="btn btn-sm btn-light border dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                        ⋮
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                        {{-- EDIT --}}
                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('roles.edit', $role->id) }}">
                                                ✏️ Edit Role
                                            </a>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        {{-- DELETE --}}
                                        <li>
                                            <form action="{{ route('roles.destroy', $role->id) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('Yakin hapus role ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="dropdown-item text-danger">
                                                    🗑️ Hapus Role
                                                </button>
                                            </form>
                                        </li>

                                    </ul>
                                </div>

                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-3">
                                Belum ada data role.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-3 mb-3 d-flex justify-content-center">
                {{ $roles->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

{{-- CSS TAMBAHAN --}}
<style>
    td form {
        margin: 0;
    }

    .dropdown-toggle::after {
        display: none;
    }

    .pagination {
        font-size: 13px;
    }

    .pagination .page-link {
        padding: 5px 10px;
    }
</style>
@endsection
