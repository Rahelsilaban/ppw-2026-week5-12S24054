<x-layout title="Detail Kategori">
    <h1 class="h3 mb-4">Detail Kategori</h1>
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Kode Kategori</dt><dd class="col-sm-9">{{ $kategori->kode_kategori }}</dd>
                <dt class="col-sm-3">Nama Kategori</dt><dd class="col-sm-9">{{ $kategori->nama_kategori }}</dd>
                <dt class="col-sm-3">Jumlah Buku</dt><dd class="col-sm-9">{{ $kategori->bukus_count }}</dd>
            </dl>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Kembali</a>
        <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-primary">Edit Kategori</a>
        <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Hapus {{ $kategori->nama_kategori }}">Hapus</button>
                        </form>
    </div>
</x-layout>
