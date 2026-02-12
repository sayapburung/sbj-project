@extends('layouts.app')

@section('title','Tambah Role')

@section('content')
<div class="container">

    <h4>Tambah Role</h4>

    <form action="{{ route('roles.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Role</label>
            <input type="text" name="name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Checklist Permission</label>
            <div class="row">
                @foreach($permissions as $key => $label)
                    <div class="col-md-4">
                        <div class="form-check">
                            <input type="checkbox"
                                   name="permissions[]"
                                   value="{{ $key }}"
                                   class="form-check-input">
                            <label class="form-check-label">
                                {{ $label }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary">Simpan</button>
    </form>

</div>
@endsection
