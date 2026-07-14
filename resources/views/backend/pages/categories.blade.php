@extends('backend.layouts.masterLayout')
@section('title', 'Categories')
@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.categories.index') }}" 
                class="text-decoration-none  text-primary">
                    Main Category
                </a>
            </li>
            @isset($breadcrumb)
                @foreach($breadcrumb as $crumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active fw-bold">{{ $crumb->name }}</li>
                    @else
                        <li class="breadcrumb-item ">
                            <a href="{{ route('admin.categories.children', $crumb) }}">
                                {{ $crumb->name }}
                            </a>
                        </li>
                    @endif
                @endforeach
            @endisset
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">
            @isset($category)
                {{ $category->name }} — এর অন্তর্গত
            @else
                মূল Category সমূহ
            @endisset
        </h5>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
            + নতুন Category
        </a>
    </div>


    {{-- Table --}}
    @php $list = $children ?? $categories; @endphp

    @if($list->isEmpty())
        <div class="alert alert-secondary">কোনো category নেই।</div>
    @else
        <div class="card">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:50px">ক্রম</th>
                        <th style="width:50px">ফটো</th>
                        <th>নাম</th>
                        <th style="width:80px">মোট প্রডাক্ট</th>
                        <th style="width:80px">স্তর</th>
                        <th style="width:90px">অবস্থা</th>
                        <th style="width:80px">Child</th>
                        <th style="width:130px">Action</th>
                    </tr>
                </thead>
                <tbody id="sortable-list"
                       data-url="{{route('admin.categories.reorder')}}">
                    @foreach($list as $cat)
                        <tr data-id="{{ $cat->id }}">

                            {{-- Drag handle --}}
                            <td class="text-center" style="cursor:grab; color:#aaa">
                                &#9776;
                            </td>

                            <td>
                                <div class="category-icon-box text-center p-2">
                                    @if($cat->image && file_exists(public_path('storage/' . $cat->image)))
                                        <img src="{{ asset('storage/' . $cat->image) }}" class="img-fluid" alt="{{ $cat->name }}" 
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('storage/' . 'No_Image.jpg') }}" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </div>
                            </td>

                            {{-- নাম — ক্লিক করলে child লোড হবে --}}
                            <td>
                                <a href="{{ route('admin.categories.children', $cat) }}"
                                   class="text-decoration-none fw-500 fw-bold text-dark">
                                    {{ $cat->name }}
                                </a>
                            </td>

                            {{-- Level badge --}}
                            <td><span class="badge bg-info text-dark">{{$cat->products->count()}}</span></td>

                            {{-- Level badge --}}
                            <td>
                                <span class="badge bg-secondary">Level {{ $cat->level }}</span>
                            </td>

                            {{-- Active/Inactive --}}
                            <td>
                                @if($cat->is_active)
                                    <span class="badge bg-success">সক্রিয়</span>
                                @else
                                    <span class="badge bg-danger">নিষ্ক্রিয়</span>
                                @endif
                            </td>

                            {{-- Child আছে কিনা --}}
                            <td>
                                @if($cat->children->count())
                                    <a href="{{ route('admin.categories.children', $cat) }}"
                                       class="badge bg-info text-decoration-none">
                                        {{ $cat->children->count() }}টি
                                    </a>
                                @else
                                    <span class="text-muted small">নেই</span>
                                @endif
                            </td>

                            {{-- Edit / Delete --}}
                            <td>
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>

                                <button type="button" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal{{ $cat->id }}"
                                    data-id="{{ $cat->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>

                        </tr>
                        

                        <!-- category Delete Modal -->
                        <div class="modal fade" id="deleteCategoryModal{{ $cat->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-danger text-white">
                                        <h6 class="modal-title">ক্যাটাগরি মুছে ফেলুন</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{route('admin.categories.destroy',$cat->id)}}" method="POST">
                                        @csrf
                                        @method('DELETE')

                                        <div class="modal-body text-center">
                                            <p>আপনি কি নিশ্চিত যে আপনি এই ক্যাটাগরি মুছে ফেলতে চান?</p>
                                            <p class="text-danger">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
                                        </div>
                                        <div class="modal-footer justify-content-center">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                            <button type="submit" name="submit" class="btn btn-danger">মুছে ফেলুন</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Back button --}}
    @isset($category)
        @if($category->parent)
            <a href="{{ route('admin.categories.children', $category->parent) }}"
               class="btn btn-outline-secondary btn-sm mt-3">
                ← আগের স্তরে ফিরুন
            </a>
        @else
            <a href="{{ route('admin.categories.index') }}"
               class="btn btn-outline-secondary btn-sm mt-3">
                ← মূল Category-তে ফিরুন
            </a>
        @endif
    @endisset

</div>

{{-- SortableJS --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
    const tbody = document.getElementById('sortable-list');

    Sortable.create(tbody, {
        animation: 150,
        onEnd: function () {
            const rows  = [...tbody.querySelectorAll('tr')];
            const order = rows.map((row, index) => ({
                id:       parseInt(row.dataset.id),
                position: index + 1,
            }));

            fetch(tbody.dataset.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ order }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                } else {
                    showToast('Something went wrong', 'danger');
                }
            })
            .catch(error => {
                console.error(error);
                showToast('Server error occurred', 'danger');
            });
        }
    });
</script>
@endsection