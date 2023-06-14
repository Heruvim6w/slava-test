<form method="POST" action="{{ url('/upload-excel') }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
        <label for="file" class="form-label">Upload Excel File</label>
        <input type="file" class="form-control" id="file" name="file">
        @error('file')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn btn-primary">Submit</button>
</form>
<div id="rows">
    <row v-for="row in rows" :key="row.id" :row="row"></row>
</div>
