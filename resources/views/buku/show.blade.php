<x-layout title="Detail Buku">
    <h1 class="h3 mb-4">Detail Buku</h1>
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">ISBN</dt><dd class="col-sm-9">{{ $buku->isbn }}</dd>
                <dt class="col-sm-3">Judul</dt><dd class="col-sm-9">{{ $buku->judul }}</dd>
                <dt class="col-sm-3">Penulis</dt><dd class="col-sm-9">{{ $buku->penulis }}</dd>
                <dt class="col-sm-3">Penerbit</dt><dd class="col-sm-9">{{ $buku->penerbit }}</dd>
                <dt class="col-sm-3">Tahun Terbit</dt><dd class="col-sm-9">{{ $buku->tahun_terbit }}</dd>
                <dt class="col-sm-3">Kategori</dt><dd class="col-sm-9">{{ $buku->kategori->nama_kategori }}</dd>
                <dt class="col-sm-3">Stok</dt><dd class="col-sm-9">{{ $buku->stok }}</dd>
                <dt class="col-sm-3">Sinopsis</dt><dd class="col-sm-9 sinopsis">{{ $buku->sinopsis ?? 'Belum ada sinopsis.' }}</dd>
            </dl>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 align-items-center">
        <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Kembali</a>
        <a href="{{ route('buku.edit', $buku) }}" class="btn btn-primary">Edit Buku</a>
        <form action="{{ route('buku.destroy', $buku) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus buku ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Hapus {{ $buku->judul }}">Hapus</button>
                        </form>
    </div>
</x-layout>
