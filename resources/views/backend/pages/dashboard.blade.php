 @extends('backend/layouts/masterLayout')
 @section('content')
 <!-- স্ট্যাটস কার্ড -->
        <div class="row g-4 mb-4">
            <div class="col-md-6 col-lg-3">
                <div class="stat-card position-relative blue">
                    <i class="fas fa-wallet stat-icon text-primary"></i>
                    <div class="card-title-text text-muted">আজকের বিক্রি</div>
                    <div class="h4 fw-bold text-dark">৳ ২৫,৪০০</div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> ১২%</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card position-relative orange">
                    <i class="fas fa-shopping-bag stat-icon text-warning"></i>
                    <div class="card-title-text text-muted">মোট অর্ডার</div>
                    <div class="h4 fw-bold text-dark">১২০</div>
                    <small class="text-muted">৫টি পেন্ডিং</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card position-relative green">
                    <i class="fas fa-users stat-icon text-success"></i>
                    <div class="card-title-text text-muted">নতুন গ্রাহক</div>
                    <div class="h4 fw-bold text-dark">৩৫</div>
                    <small class="text-success"><i class="fas fa-arrow-up"></i> ৫%</small>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="stat-card position-relative red">
                    <i class="fas fa-exclamation-triangle stat-icon text-danger"></i>
                    <div class="card-title-text text-muted">লো-স্টক</div>
                    <div class="h4 fw-bold text-dark">৮</div>
                    <small class="text-danger">দ্রুত রিস্টক করুন</small>
                </div>
            </div>
        </div>

        <!-- চার্ট এবং টেবিল -->
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="content-card h-100">
                    <h5 class="fw-bold mb-3">সাপ্তাহিক বিক্রয় রিপোর্ট</h5>
                    <div style="height: 300px;"><canvas id="salesChart"></canvas></div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="content-card h-100">
                    <h5 class="fw-bold mb-3">সাম্প্রতিক অর্ডার</h5>
                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span>#০০১২৩</span> <span class="fw-bold">৳ ১,৫০০</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span>#০০১২৪</span> <span class="fw-bold">৳ ৩,২০০</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom pb-2">
                            <span>#০০১২৫</span> <span class="fw-bold">৳ ৮৫০</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection