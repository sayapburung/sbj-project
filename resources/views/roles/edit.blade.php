@extends('layouts.app')

@section('title','Edit Role')

@section('content')
<div class="container">

    <h4>Edit Role: {{ $role->name }}</h4>

    <form action="{{ route('roles.update',$role->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Role</label>
            <input type="text" name="name"
                   value="{{ $role->name }}"
                   class="form-control">
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
                                   class="form-check-input"
                                   @if(in_array($key,$role->permissions ?? [])) checked @endif>
                            <label class="form-check-label">
                                {{ $label }}
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
    </form>

</div>
@endsection
