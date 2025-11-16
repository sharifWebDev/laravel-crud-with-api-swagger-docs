<form method="POST" id="createstudentsForm" action="{{ url('api/v1/students') }}" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="row">
        <div class="mb-3 col-12 col-md-4 col-lg-3">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="Enter Name..." id="create_name" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 col-12 col-md-4 col-lg-3">
            <div class="form-group">
                <label for="roll">Roll</label>
                <input type="number" name="roll" min="0"
                    class="form-control @error('roll') is-invalid @enderror" value="{{ old('roll') }}"
                    placeholder="Enter Roll..." id="create_roll" required>
                @error('roll')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 col-12 col-md-4 col-lg-3">
            <div class="form-group">
                <label for="is_active"> Active</label> <br>
                <input type="radio" name="is_active" id="create_is_active_yes" value="1"
                    {{ old('is_active') == 1 ? 'checked' : '' }} checked>
                <label for="create_is_active_yes"> Yes</label>
                <input type="radio" name="is_active" id="create_is_active_no" value="0"
                    {{ old('is_active') == 0 ? 'checked' : '' }}>
                <label for="create_is_active_no"> No</label>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 text-right">
            <a type="button" class="btn bg-danger" href="{{ route('admin.students.index') }}">Cancel</a>
            <button type="submit" id="submitButton" class="btn btn-primary">Save</button>
        </div>
    </div>
</form>
