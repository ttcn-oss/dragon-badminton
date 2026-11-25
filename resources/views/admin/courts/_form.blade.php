<div class="mb-3">
  <label class="form-label">Tên sân</label>
  <input name="name" class="form-control" value="{{ old('name', $court->name ?? '') }}">
  @error('name') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label class="form-label">Mô tả</label>
  <textarea name="description" class="form-control">{{ old('description', $court->description ?? '') }}</textarea>
  @error('description') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label class="form-label">Giá thuê mỗi giờ (đồng)</label>
  <input type="number" name="price_per_hour" min="0" class="form-control"
         value="{{ old('price_per_hour', $court->price_per_hour ?? 100000) }}">
  @error('price_per_hour') <div class="text-danger">{{ $message }}</div> @enderror
</div>
<div class="mb-3">
  <label class="form-label">Trạng thái</label>
  <select name="status" class="form-select">
    @foreach (['active' => 'Hoạt động', 'maintenance' => 'Bảo trì'] as $v => $label)
      <option value="{{ $v }}" @selected(old('status', $court->status ?? 'active') === $v)>{{ $label }}</option>
    @endforeach
  </select>
</div>
