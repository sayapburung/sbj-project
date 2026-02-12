@extends('layouts.app')

@section('title','Role Management')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between mb-3">
        <h4>Role Management</h4>
        <a href="{{ route('roles.create') }}" class="btn btn-primary">
            + Tambah Role
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td>
                            @foreach($role->permissions ?? [] as $perm)
                                <span class="badge bg-success">{{ $perm }}</span>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('roles.edit',$role->id) }}"
                               class="btn btn-warning btn-sm">
                                Edit
                            </a>

                            <form action="{{ route('roles.destroy',$role->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button onclick="return confirm('Hapus role ini?')"
                                        class="btn btn-danger btn-sm">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
