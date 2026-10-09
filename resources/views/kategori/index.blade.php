<x-layout title="Daftar Kategori">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <h1 class="h3 mb-0">Daftar Kategori</h1>
        <a href="{{ route('kategori.create') }}" class="btn btn-primary">Tambah Kategori</a>
    </div>
    <div class="card shadow-sm mb-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <caption class="px-3">Kategori koleksi perpustakaan</caption>
                <thead><tr><th scope="col">Kode</th><th scope="col">Nama Kategori</th><th scope="col">Jumlah Buku</th><th scope="col">Aksi</th></tr></thead>
                <tbody>
                    @forelse ($kategoris as $kategori)
                        <tr>
                            <td>{{ $kategori->kode_kategori }}</td>
                            <td>{{ $kategori->nama_kategori }}</td>
                            <td>{{ $kategori->bukus_count }}</td>
                            <td class="text-nowrap">
                                <a href="{{ route('kategori.show', $kategori) }}" class="btn btn-sm btn-outline-primary" aria-label="Detail {{ $kategori->nama_kategori }}">Detail</a>
                                <a href="{{ route('kategori.edit', $kategori) }}" class="btn btn-sm btn-outline-secondary" aria-label="Edit {{ $kategori->nama_kategori }}">Edit</a>
                                <form action="{{ route('kategori.destroy', $kategori) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Hapus {{ $kategori->nama_kategori }}">Hapus</button>
                        </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-4">Belum ada kategori. Tambahkan kategori pertama Anda.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{ $kategoris->links() }}
</x-layout>
