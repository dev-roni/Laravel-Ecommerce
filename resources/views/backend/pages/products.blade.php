@extends('backend/layouts/masterLayout')
@section('content')
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <input type="text" class="form-control w-25" placeholder="পণ্য খুঁজুন...">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal"><i class="fas fa-plus"></i> যোগ করুন</button>
            </div>

            <table class="table table-hover align-middle">
                <thead><tr><th>ছবি</th><th>নাম</th><th>দাম</th><th>স্টক</th><th>অ্যাকশন</th></tr></thead>
                <tbody>
                    <tr><td><img src="https://picsum.photos/seed/p1/40/40" class="rounded"></td><td>টি-শার্ট</td><td>৳ ৫০০</td><td>৫০</td><td><button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button></td></tr>
                    <tr><td><img src="https://picsum.photos/seed/p2/40/40" class="rounded"></td><td>জিন্স</td><td>৳ ১২০০</td><td>২০</td><td><button class="btn btn-sm btn-info"><i class="fas fa-edit"></i></button></td></tr>
                </tbody>
            </table>
        </div>
@endsection