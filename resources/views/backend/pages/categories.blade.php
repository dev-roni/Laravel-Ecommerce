@extends('backend.layouts.masterLayout')
@section('content')

        <!-- কন্টেন্ট কার্ড -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                <div class="input-group w-auto" style="min-width: 300px;">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" class="form-control bg-light border-start-0" id="searchCategory" placeholder="ক্যাটাগরি খুঁজুন..." onkeyup="filterTable()">
                </div>
                <a class="btn btn-primary" href="{{route('admin.product-categories.create')}}">
                    <i class="fas fa-plus me-2"></i>নতুন ক্যাটাগরি যোগ করুন
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle" id="categoryTable">
                    <thead class="table-light">
                        <tr>
                            <th>ইমেজ</th>
                            <th>ক্যাটাগরির নাম</th>
                            <th>স্লাগ (URL)</th>
                            <th>পণ্য সংখ্যা</th>
                            <th>স্টেটাস</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productCategory as $category)
                        <tr>
                            <td>
                                <div class="category-icon-box text-center p-2">
                                    @if($category->category_image && file_exists(public_path('storage/' . $category->category_image)))
                                        <img src="{{ asset('storage/' . $category->category_image) }}" class="img-fluid" alt="{{ $category->category_name }}" 
                                        style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('storage/' . 'No_Image.jpg') }}" class="img-fluid" style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif
                                </div>
                            </td>
                            <td><span class="fw-bold">{{$category->product_category_name}}</span></td>
                            <td><span class="text-muted">{{$category->category_slug}}</span></td>
                            <td><span class="badge bg-info text-dark">১২০</span></td>
                            <td>{{$category->is_active ? "Active" : "Deactive"}}</td>
                            <td>
                                <a href="{{route('admin.product-categories.edit',$category->id)}}" class="btn btn-sm btn-outline-primary " title="এডিট করুন"><i class="fas fa-edit"></i></a>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="মুছে ফেলুন" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteCategoryModal{{ $category->id }}"
                                    data-id="{{ $category->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                            <!-- Member Delete Modal -->
                            <div class="modal fade" id="deleteCategoryModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title">সদস্য মুছে ফেলুন</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="{{route('admin.product-categories.destroy',$category->id)}}" method="POST">
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
                        @empty
                        <tr>
                            কোন ক্যাটাগরি নেই
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


@endsection