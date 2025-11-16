<form>
    <div class="row">
        <div class="mb-3 col-12 col-md-4 col-lg-3">
            <div class="form-group">
                <label for="name">Name</label><br><input type="text" name="name"
                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $query->name ?? '') }}"
                    placeholder="Enter Name..." id="view_name" disabled>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 col-12 col-md-4 col-lg-3">
            <div class="form-group">
                <label for="roll">Roll</label><br><input type="number" name="roll" min="0"
                    class="form-control @error('roll') is-invalid @enderror"
                    value="{{ old('roll', $query->roll ?? '') }}" placeholder="Enter Roll..." id="view_roll" disabled>
                @error('roll')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 col-12 col-md-4 col-lg-3">
            <div class="form-group">
                <label for="is_active"> Active</label><br><input type="radio" name="is_active" id="view_is_active_yes"
                    value="1" {{ old('is_active', $query->is_active ?? '') == 1 ? 'checked' : '' }} disabled>
                <label for="editis_active_yes" disabled>Yes </label>
                <input type="radio" name="is_active" id="view_is_active_no" value="0"
                    {{ old('is_active', $query->is_active ?? '') == 0 ? 'checked' : '' }} disabled>
                <label for="editis_active_no" disabled>No </label>
                @error('is_active')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="mb-3 text-right">
            <a type="button" class="btn bg-danger" href="{{ route('admin.students.index') }}">Close</a>
        </div>
    </div>
</form>
