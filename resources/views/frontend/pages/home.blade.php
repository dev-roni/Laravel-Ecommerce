@extends('frontend/layouts/masterLayout')
@section('content')
    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="row align-items-center hero-content">
                <div class="col-lg-6">
                    <h1>সবচেয়ে ভালো দামে<br><span class="text-accent">সেরা প্রোডাক্ট</span></h1>
                    <p class="lead">দ্রুত ডেলিভারি | নিরাপদ পেমেন্ট | ৭ দিনের রিটার্ন</p>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="#products" class="btn btn-accent btn-lg w-100 py-3 fs-5">
                                <i class="fas fa-shopping-bag me-2"></i>এখনই কিনুন
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="#categories" class="btn btn-outline-light btn-lg w-100 py-3 fs-5">
                                <i class="fas fa-th-large me-2"></i>ক্যাটাগরি
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <div class="hero-image">
                        <i class="fas fa-box-open fa-10x text-white opacity-20"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

        <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">ট্রেন্ডিং প্রোডাক্ট</h2>
                <p class="lead text-muted fs-5">সবচেয়ে জনপ্রিয় এবং বিক্রি হচ্ছে</p>
            </div>
            <div class="row g-4" id="featured">
                <!-- Product 1 -->
                <div class="col-lg-3 col-md-6">
                    <a href="product.html" class="product-card h-100 text-decoration-none">
                        <div class="card position-relative overflow-hidden h-100">
                            <img src="https://images.unsplash.com/photo-1592899677059-81a4a4e55b90?w=400&h=300&fit=crop" 
                                 class="card-img-top h-100"  alt="Samsung S24">
                            <div class="position-absolute top-3 end-3">
                                <span class="badge bg-success">ট্রেন্ডিং</span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2 section-title">Samsung Galaxy S24</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="h5 fw-bold text-accent mb-0">৫২,৯৯৯৳</div>
                                    <div class="text-muted text-decoration-line-through h6">৬৫,০০০৳</div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="fw-bold text-accent me-2">4.9</span>
                                    <span class="text-muted small">(1,247)</span>
                                </div>
                                <button class="btn btn-accent w-100 py-2 fs-6">
                                    <i class="fas fa-shopping-cart me-2"></i>কার্টে যোগ
                                </button>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Product 2 -->
                <div class="col-lg-3 col-md-6">
                    <a href="product.html" class="product-card h-100 text-decoration-none">
                        <div class="card position-relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400&h=300&fit=crop" 
                                 class="card-img-top" alt="iPhone">
                            <div class="position-absolute top-3 end-3">
                                <span class="badge bg-accent fs-6">১৫% ছাড়</span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2 section-title">iPhone 15 Pro</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="h5 fw-bold text-accent mb-0">১,০৫,০০০৳</div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="fw-bold text-accent me-2">4.8</span>
                                    <span class="text-muted small">(856)</span>
                                </div>
                                <button class="btn btn-accent w-100 py-2 fs-6">
                                    <i class="fas fa-shopping-cart me-2"></i>কার্টে যোগ
                                </button>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Product 3 -->
                <div class="col-lg-3 col-md-6">
                    <a href="product.html" class="product-card h-100 text-decoration-none">
                        <div class="card position-relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?w=400&h=300&fit=crop" 
                                 class="card-img-top" alt="MacBook">
                            <div class="position-absolute top-3 start-3">
                                <span class="badge bg-primary fs-6">নতুন</span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2 section-title">MacBook Air M3</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="h5 fw-bold text-accent mb-0">১,২৯,৯০০৳</div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="fw-bold text-accent me-2">4.9</span>
                                    <span class="text-muted small">(423)</span>
                                </div>
                                <button class="btn btn-accent w-100 py-2 fs-6">
                                    <i class="fas fa-shopping-cart me-2"></i>কার্টে যোগ
                                </button>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Product 4 -->
                <div class="col-lg-3 col-md-6">
                    <a href="product.html" class="product-card h-100 text-decoration-none">
                        <div class="card position-relative overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=300&fit=crop" 
                                 class="card-img-top" alt="AirPods">
                            <div class="position-absolute top-3 end-3">
                                <span class="badge bg-warning text-dark fs-6">হট ডিল</span>
                            </div>
                            <div class="card-body p-4">
                                <h5 class="card-title fw-bold mb-2 section-title">AirPods Pro 2</h5>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="h5 fw-bold text-accent mb-0">২৪,৯৯০৳</div>
                                    <div class="text-muted text-decoration-line-through h6">২৯,৯০০৳</div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <i class="fas fa-star text-warning me-1"></i>
                                    <span class="fw-bold text-accent me-2">4.7</span>
                                    <span class="text-muted small">(2,134)</span>
                                </div>
                                <button class="btn btn-accent w-100 py-2 fs-6">
                                    <i class="fas fa-shopping-cart me-2"></i>কার্টে যোগ
                                </button>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories -->
    <section id="categories" class="py-7 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title mb-3">জনপ্রিয় ক্যাটাগরি</h2>
                <p class="lead text-muted">আপনার পছন্দের ক্যাটাগরি থেকে কিনুন</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <a href="#" class="category-card">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1587563871167-1ee9c731aefb?w=400" class="card-img-top" alt="ইলেকট্রনিক্স">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2" style="color: var(--primary);">ইলেকট্রনিক্স</h5>
                                <p class="text-muted mb-2 small">মোবাইল, ল্যাপটপ</p>
                                <small class="text-success fw-bold"><i class="fas fa-fire me-1"></i>২৫% ছাড়</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a href="#" class="category-card">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400" class="card-img-top" alt="ফ্যাশন">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2" style="color: var(--primary);">ফ্যাশন</h5>
                                <p class="text-muted mb-2 small">পুরুষ, মহিলা</p>
                                <small class="text-success fw-bold"><i class="fas fa-fire me-1"></i>৩০% ছাড়</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a href="#" class="category-card">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400" class="card-img-top" alt="হোম">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2" style="color: var(--primary);">হোম & লিভিং</h5>
                                <p class="text-muted mb-2 small">আসবাব, কিচেন</p>
                                <small class="text-warning fw-bold"><i class="fas fa-bolt me-1"></i>ফ্ল্যাশ সেল</small>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <a href="#" class="category-card">
                        <div class="card h-100">
                            <img src="https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?w=400" class="card-img-top" alt="বই">
                            <div class="card-body">
                                <h5 class="fw-bold mb-2" style="color: var(--primary);">বই</h5>
                                <p class="text-muted mb-2 small">বই, স্টেশনারি</p>
                                <small class="text-primary fw-bold"><i class="fas fa-star me-1"></i>নতুন</small>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection