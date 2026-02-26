@extends('layouts.app')

@section('title', 'Master Jenis PO')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Master Jenis PO</h4>
        <a href="{{ route('jenis-po.create') }}" class="btn btn-primary">
            + Tambah Jenis PO
        </a>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- FILTER SEARCH --}}
    <form method="GET" action="{{ route('jenis-po.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari nama / kode..."
                   value="{{ request('search') }}">

            <button class="btn btn-dark">Cari</button>

            @if(request('search'))
                <a href="{{ route('jenis-po.index') }}" class="btn btn-secondary">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- TABLE --}}
    <div class="card shadow-sm">
        <div class="card-body p-0">

            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Kategori</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th style="width:90px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jenisPos as $i => $item)
                        <tr>
                            <td>{{ $jenisPos->firstItem() + $i }}</td>

                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $item->kategori }}
                                </span>
                            </td>

                            <td>
                                <strong>{{ $item->kode }}</strong>
                            </td>

                            <td>{{ $item->nama }}</td>

                            {{-- STATUS --}}
                            <td>
                                @if($item->is_active)
                                    <span class="badge bg-success">
                                        ✅ Active
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        ❌ Nonaktif
                                    </span>
                                @endif
                            </td>

                            <td>{{ $item->created_at->format('d M Y') }}</td>

                            {{-- ACTION --}}
                            <td class="text-center">

                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light border dropdown-toggle"
                                            type="button"
                                            data-bs-toggle="dropdown">
                                        ⋮
                                    </button>

                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">

                                        <li>
                                            <a class="dropdown-item"
                                               href="{{ route('jenis-po.edit', $item->id) }}">
                                                ✏️ Edit
                                            </a>
                                        </li>

                                        <li>
                                            <form action="{{ route('jenis-po.update', $item->id) }}"
                                                  method="POST">
                                                @csrf
                                                @method('PUT')
                                                <!-- <button type="submit"
                                                        name="toggle"
                                                        value="1"
                                                        class="dropdown-item">
                                                    {{ $item->is_active ? '🚫 Nonaktifkan' : '✅ Aktifkan' }}
                                                </button> -->
                                            </form>
                                        </li>

                                        <li><hr class="dropdown-divider"></li>

                                        <li>
                                            <button class="dropdown-item text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteModal{{ $item->id }}">
                                                🗑️ Hapus
                                            </button>
                                        </li>

                                    </ul>
                                </div>

                            </td>
                        </tr>

                        {{-- MODAL DELETE --}}
                        <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        Yakin ingin menghapus jenis PO
                                        <strong>{{ $item->nama }}</strong>?
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button"
                                                class="btn btn-secondary"
                                                data-bs-dismiss="modal">
                                            Batal
                                        </button>

                                        <form action="{{ route('jenis-po.destroy', $item->id) }}" method="POST">
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
                            <td colspan="7" class="text-center py-3">
                                Belum ada data jenis PO.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- PAGINATION --}}
            <div class="mt-3 mb-3 d-flex justify-content-center">
                {{ $jenisPos->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>

        </div>
    </div>
</div>

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