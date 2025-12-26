@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit User</h4>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">⬅ Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label">Nama User</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $user->name) }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $user->email) }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- ROLE --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Role</label>
                    <select name="role_id" class="form-select" required>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- PASSWORD (opsional) --}}
                <div class="mb-3">
                    <label class="form-label">Password Baru (Opsional)</label>
                    <input type="password" name="password" class="form-control" placeholder="Isi jika ingin ganti password">
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- KONFIRM PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-control"
                           placeholder="Isi jika ingin ganti password">
                </div>

                <button type="submit" class="btn btn-primary mt-2 px-4">
                    Update
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
