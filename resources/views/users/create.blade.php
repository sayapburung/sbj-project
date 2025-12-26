@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah User</h4>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">⬅ Kembali</a>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                {{-- NAMA --}}
                <div class="mb-3">
                    <label class="form-label">Nama User</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- ROLE --}}
                <div class="mb-3">
                    <label class="form-label">Pilih Role</label>
                    <select name="role_id" class="form-select" required>
                        <option value="" disabled selected>-- Pilih Role --</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('role_id') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- PASSWORD CONFIRM --}}
                <div class="mb-3">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-2 px-4">
                    Simpan
                </button>
            </form>

        </div>
    </div>
</div>
@endsection
