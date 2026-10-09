<x-layout title="Edit Buku">
    <h1 class="h3 mb-4">Edit Buku</h1>
    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('buku.update', $buku) }}" method="POST">
                @csrf
                @method('PUT')
                @include('buku._form')
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('buku.index') }}" class="btn btn-outline-secondary">Batal</a>
            </form>
        </div>
    </div>
</x-layout>
