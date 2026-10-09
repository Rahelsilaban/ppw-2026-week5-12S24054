<p class="text-secondary small">Semua field wajib diisi kecuali sinopsis.</p>
    <div class="mb-3">
        <label for="isbn" class="form-label">ISBN</label>
        <input type="text" name="isbn" id="isbn" class="form-control @error('isbn') is-invalid @enderror" value="{{ old('isbn', $buku->isbn ?? '') }}" required inputmode="numeric" minlength="10" maxlength="13" pattern="[0-9]{10,13}" @error('isbn') aria-invalid="true" aria-describedby="isbn-error" @enderror>
        @error('isbn')
            <div id="isbn-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="judul" class="form-label">Judul</label>
        <input type="text" name="judul" id="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul', $buku->judul ?? '') }}" required minlength="5" maxlength="255" @error('judul') aria-invalid="true" aria-describedby="judul-error" @enderror>
        @error('judul')
            <div id="judul-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="penulis" class="form-label">Penulis</label>
        <input type="text" name="penulis" id="penulis" class="form-control @error('penulis') is-invalid @enderror" value="{{ old('penulis', $buku->penulis ?? '') }}" required maxlength="150" @error('penulis') aria-invalid="true" aria-describedby="penulis-error" @enderror>
        @error('penulis')
            <div id="penulis-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="penerbit" class="form-label">Penerbit</label>
        <input type="text" name="penerbit" id="penerbit" class="form-control @error('penerbit') is-invalid @enderror" value="{{ old('penerbit', $buku->penerbit ?? '') }}" required maxlength="150" @error('penerbit') aria-invalid="true" aria-describedby="penerbit-error" @enderror>
        @error('penerbit')
            <div id="penerbit-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" id="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror" value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}" required min="1900" max="{{ now()->year }}" step="1" @error('tahun_terbit') aria-invalid="true" aria-describedby="tahun_terbit-error" @enderror>
        @error('tahun_terbit')
            <div id="tahun_terbit-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori</label>
        <select name="kategori_id" id="kategori_id" class="form-select @error('kategori_id') is-invalid @enderror" required @error('kategori_id') aria-invalid="true" aria-describedby="kategori_id-error" @enderror>
            <option value="">Pilih kategori</option>
            @forelse ($kategoris as $kategori)
                <option value="{{ $kategori->id }}" @selected(old('kategori_id', $buku->kategori_id ?? '') == $kategori->id)>{{ $kategori->nama_kategori }}</option>
            @empty
                <option value="" disabled>Belum ada kategori</option>
            @endforelse
        </select>
        @error('kategori_id')
            <div id="kategori_id-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
        @if ($kategoris->isEmpty())
            <div class="form-text">Tambahkan kategori terlebih dahulu melalui <a href="{{ route('kategori.create') }}">form kategori</a>.</div>
        @endif
    </div>
    <div class="mb-3">
        <label for="stok" class="form-label">Stok</label>
        <input type="number" name="stok" id="stok" class="form-control @error('stok') is-invalid @enderror" value="{{ old('stok', $buku->stok ?? '') }}" required min="0" step="1" @error('stok') aria-invalid="true" aria-describedby="stok-error" @enderror>
        @error('stok')
            <div id="stok-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="sinopsis" class="form-label">Sinopsis (opsional)</label>
        <textarea name="sinopsis" id="sinopsis" rows="5" class="form-control @error('sinopsis') is-invalid @enderror" @error('sinopsis') aria-invalid="true" aria-describedby="sinopsis-error" @enderror>{{ old('sinopsis', $buku->sinopsis ?? '') }}</textarea>
        @error('sinopsis')
            <div id="sinopsis-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
