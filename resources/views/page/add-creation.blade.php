@extends('welcome')

@section('page')
<div class="container mt-4">
  <h1 class="mb-4">Tambah Karya</h1>

  <form action="{{ route('add-creation') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="mb-3">
          <label for="name" class="form-label">Nama Karya</label>
          <input type="text" name="name" id="name" class="form-control" required>
      </div>

      <div class="mb-3">
          <label for="desc" class="form-label">Deskripsi</label>
          <textarea name="desc" id="desc" class="form-control" rows="4"></textarea>
      </div>

      <div class="mb-3">
          <label for="min_price" class="form-label">Harga Minimum</label>
          <input type="number" name="min_price" id="min_price" class="form-control" required>
      </div>

      {{-- <div class="mb-3">
          <label for="type" class="form-label">Jenis Karya</label>
          <select name="type" id="type" class="form-select" required>
              <option value="image">Image</option>
              <option value="video">Video</option>
          </select>
      </div> --}}

      <div class="mb-3">
          <label for="creation_file" class="form-label">Unggah File Karya</label>
          <input type="file" name="creation_file" id="creation_file" class="form-control" required>
      </div>

      <button type="submit" class="btn btn-primary">Tambah Karya</button>
  </form>
</div>
@endsection