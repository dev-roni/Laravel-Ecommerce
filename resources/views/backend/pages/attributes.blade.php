{{-- admin/attributes/index.blade.php --}}
@extends('backend.layouts.masterLayout')
@section('title', 'Attributes')
@section('content')
<div class="container py-4" style="max-width:700px">

    <div class="row g-4">

        {{-- বাম: নতুন attribute form --}}
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">নতুন Attribute</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.attributes.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">নাম</label>
                            <input type="text" name="name"
                                   placeholder="যেমন: Color, Size"
                                   class="form-control @error('name') is-invalid @enderror">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">ধরন</label>
                            <select name="type" class="form-select">
                                <option value="select">Dropdown</option>
                                <option value="color">Color Swatch</option>
                                <option value="button">Button</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100">যোগ করুন</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ডান: attribute list --}}
        <div class="col-md-7">
            @foreach($attributes as $attribute)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between">
                        <span>{{ $attribute->name }}
                            <span class="badge bg-secondary ms-1">{{ $attribute->type }}</span>
                        </span>
                        <a href="{{route('admin.attributes.show', $attribute)}}"
                           class="btn btn-sm btn-outline-primary">
                            Values দেখুন
                        </a>
                    </div>
                    @if($attribute->values->count())
                        <div class="card-body py-2">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($attribute->values as $value)
                                    <span class="badge bg-light text-dark border">
                                        {{ $value->value }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

    </div>
</div>
@endsection