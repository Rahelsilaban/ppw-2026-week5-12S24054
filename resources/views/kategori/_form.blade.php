<p class="text-secondary small">Semua field wajib diisi.</p>
    <div class="mb-3">
        <label for="kode_kategori" class="form-label">Kode Kategori</label>
        <input type="text" name="kode_kategori" id="kode_kategori" class="form-control @error('kode_kategori') is-invalid @enderror" value="{{ old('kode_kategori', $kategori->kode_kategori ?? '') }}" required maxlength="10" @error('kode_kategori') aria-invalid="true" aria-describedby="kode_kategori-error" @enderror>
        @error('kode_kategori')
            <div id="kode_kategori-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="mb-3">
        <label for="nama_kategori" class="form-label">Nama Kategori</label>
        <input type="text" name="nama_kategori" id="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}" required maxlength="100" @error('nama_kategori') aria-invalid="true" aria-describedby="nama_kategori-error" @enderror>
        @error('nama_kategori')
            <div id="nama_kategori-error" class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
