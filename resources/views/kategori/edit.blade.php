<x-layout title="Edit Kategori">
    <h1 class="h3 mb-4">Edit Kategori</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('kategori.update', $kategori) }}" method="POST">
                @csrf
                @method('PUT')
                @include('kategori._form')
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-layout>
