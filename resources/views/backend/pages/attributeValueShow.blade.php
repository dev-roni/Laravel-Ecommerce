{{-- admin/attributes/show.blade.php --}}
@extends('backend.layouts.masterLayout')
@section('content')
<div class="container py-4" style="max-width:600px">

    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="{{ route('admin.attributes.index') }}"
           class="btn btn-outline-secondary btn-sm">← ফিরুন</a>
        <h5 class="mb-0">{{ $attribute->name }} — এর Values</h5>
    </div>

    {{-- নতুন value form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST"
                  action="{{ route('admin.attributes.values.store', $attribute) }}"
                  class="d-flex gap-2">
                @csrf
                <input type="text" name="value"
                       placeholder="যেমন: Red, XL, Cotton"
                       class="form-control">

                @if($attribute->type === 'color')
                    <input type="color" name="color_code"
                           class="form-control" style="width:60px">
                @endif

                <button class="btn btn-primary px-4">যোগ করুন</button>
            </form>
        </div>
    </div>

    {{-- Values list --}}
    <div class="card">
        <table class="table mb-0">
            <thead class="table-light">
                <tr>
                    <th>Value</th>
                    @if($attribute->type === 'color')
                        <th>রং</th>
                    @endif
                    <th style="width:80px">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($values as $value)
                    <tr>
                        <td>{{ $value->value }}</td>
                        @if($attribute->type === 'color')
                            <td>
                                <span style="display:inline-block; width:24px; height:24px;
                                             border-radius:50%; background:{{ $value->color_code }};
                                             border:1px solid #ddd"></span>
                                {{ $value->color_code }}
                            </td>
                        @endif
                        <td>
                            <form method="POST"
                                  action="{{ route('admin.attributes.values.destroy', $value) }}"
                                  onsubmit="return confirm('মুছবেন?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">মুছুন</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-muted text-center">
                            এখনো কোনো value নেই
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
