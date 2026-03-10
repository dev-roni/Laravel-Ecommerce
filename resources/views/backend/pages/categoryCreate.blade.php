@extends('backend.layouts.masterLayout')
@section('content')

        <!-- টপ নেভবার -->
        <div class="top-navbar">
            <div class="d-flex align-items-center">
                <i class="fas fa-bars toggle-btn me-3" id="sidebarToggle"></i>
                <!-- ব্রেডক্রাম্ব -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="dashboard.html" class="text-decoration-none text-muted">ড্যাশবোর্ড</a></li>
                        <li class="breadcrumb-item"><a href="categories.html" class="text-decoration-none text-muted">ক্যাটাগরি</a></li>
                        <li class="breadcrumb-item active fw-bold" aria-current="page">নতুন যোগ করুন</li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex align-items-center gap-3">
                <img src="https://picsum.photos/seed/admin/40/40" class="rounded-circle" alt="Admin">
                <span class="fw-bold d-none d-md-block">অ্যাডমিন</span>
            </div>
        </div>


        <!-- ফর্ম সেকশন -->
        <div class="row">
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header-custom mb-4 border-bottom pb-2">
                        <h5 class="fw-bold text-primary"><i class="fas fa-plus-circle me-2"></i>নতুন ক্যাটাগরি তৈরি করুন</h5>
                    </div>
                   
                    @php
                        $isEdit = $category->exists;
                        $url = $isEdit ? route('admin.product-categories.update',$category->id) : route('admin.product-categories.store') ;
                    @endphp

                    <form action="{{$url}}" method="POST" enctype="multipart/form-data" >
                        @csrf
                        @if($isEdit)
                            @method('PUT')
                        @endif
                        <div class="row g-3">
                            <!-- ক্যাটাগরি নাম -->
                            <div class="col-md-8">
                                <label for="catName" class="form-label fw-bold @error('product_category_name') text-danger @enderror">@error('product_category_name'){{$message}}@else ক্যাটাগরির নাম @enderror</label>
                                <input type="text" name="product_category_name" class="form-control" id="catName" value="{{old('product_category_name',$category->product_category_name)}}" placeholder="যেমন: মেনস ফ্যাশন" required>
                                <div class="form-text">ক্যাটাগরির প্রদর্শিত নাম এখানে দিন।</div>
                            </div>

                            <!-- ডিসপ্লে অর্ডার -->
                            <div class="col-md-4">
                                <label for="displayOrder" class="form-label fw-bold @error('order') text-danger @enderror">অর্ডার</label>
                                <input type="number" name="order" value="{{old('order',$category->order)}}" class="form-control" id="displayOrder" >
                                <div class="form-text">নিচের দিকে কত নম্বরে থাকবে।</div>
                            </div>

                            <!-- স্লাগ -->
                            <div class="col-12">
                                <label for="catSlug" class="form-label fw-bold @error('category_slug') text-danger @enderror">@error('category_slug'){{$message}}@else স্লাগ (Slug) @enderror</label>
                                <div class="input-group">
                                    <span class="input-group-text">/category/</span>
                                    <input type="text" name="category_slug" value="{{old('category_slug',$category->category_slug)}}" class="form-control" id="catSlug" placeholder="mens-fashion" >
                                </div>
                                <div class="form-text">ইউআরএল (URL) এর জন্য ইংরেজিতে লিখুন, স্পেস ব্যবহার করবেন না।</div>
                            </div>


                            <!-- স্ট্যাটাস -->
                            <div class="col-md-6">
                                <label class="form-label fw-bold @error('is_active') text-danger @enderror">স্ট্যাটাস</label>
                                <div class="d-flex gap-3 mt-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active" value="1" id="statusActive" {{ old('is_active', $category->is_active ?? 1) == 1 ? 'checked' : '' }} >
                                        <label class="form-check-label" for="statusActive">সক্রিয়</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_active" value="0" id="statusInactive" {{ old('is_active', $category->is_active ?? 1) == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="statusInactive">নিষ্ক্রিয়</label>
                                    </div>
                                </div>
                            </div>

                            <!-- বিবরণ -->
                            <div class="col-12">
                                <label for="catDesc" class="form-label fw-bold @error('category_description') text-danger @enderror">@error('category_description'){{$message}}@else বিবরণ @enderror</label>
                                <textarea class="form-control" name="category_description" id="catDesc" rows="4" placeholder="এই ক্যাটাগরি সম্পর্কে বিস্তারিত লিখুন...">{{old('category_slug',$category->category_slug)}}</textarea>
                            </div>

                            <!-- ছবি আপলোড -->
                            <div class="col-12">
                                <label for="catImage" class="form-label fw-bold @error('category_image') text-danger @enderror">@error('category_image'){{$message}}@else ক্যাটাগরি ছবি @enderror</label>
                                <input class="form-control" name="category_image" value="old('category_image',$category->category_image)" type="file" id="catImage" accept="image/*">
                                <div class="form-text">প্রস্তাবিত সাইজ: ৮০০ x ৮০০ পিক্সেল।</div>
                                
                                <!-- ছবি প্রিভিউ -->
                                <div id="imagePreview" class="mt-3 d-none">
                                    <img src="" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-2"></i>সংরক্ষণ করুন</button>
                            <a href="{{route('admin.product-categories.index')}}" class="btn btn-outline-secondary px-4">বাতিল করুন</a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- রাইট সাইডবার (টিপস) -->
            <div class="col-lg-4">
                <div class="content-card bg-light border-0">
                    <h6 class="fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>নির্দেশনা</h6>
                    <ul class="small text-muted">
                        <li class="mb-2">ক্যাটাগরির নামটি সংক্ষিপ্ত এবং সহজবোধ্য রাখুন।</li>
                        <li class="mb-2">স্লাগ (Slug) এ কোনো বিশেষ চিহ্ন বা স্পেস দেবেন না। শব্দের মাঝে হাইফেন (-) ব্যবহার করতে পারেন।</li>
                        <li class="mb-2">ছবি সর্বোচ্চ 1 MB দিন</li>
                    </ul>
                </div>
            </div>
        </div>

        <script>
            // ছবি প্রিভিউ দেখানোর লজিক
            document.getElementById('catImage').addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const preview = document.getElementById('imagePreview');
                        preview.classList.remove('d-none');
                        preview.querySelector('img').src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });

            // অটো স্লাগ জেনারেট (নাম লেখার সাথে সাথে)
            document.getElementById('catName').addEventListener('keyup', function(e) {
                const text = e.target.value;
                // বাংলা বা বিশেষ ক্যারেক্ট রিমুভ করে স্লাগ বানানোর সিম্পল লজিক
                const slug = text.toLowerCase()
                            .replace(/[^a-z0-9]+/g, '-') // বিশেষ ক্যারেক্ট রিপ্লেস
                            .replace(/^-+/, '') // শুরুর হাইফেন রিমুভ
                            .replace(/-+$/, ''); // শেষের হাইফেন রিমুভ
                document.getElementById('catSlug').value = slug;
            });
        </script>
@endsection