<div class="toast-container position-fixed top-0 end-0 p-3"
     id="toastContainer"
     style="z-index: 1080;">
</div>

@if(session('success'))
    <div id="alert-message" class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3"
        role="alert" style="z-index: 1050; min-width: 250px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div id="alert-message" class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3"
        role="alert" style="z-index: 1050; min-width: 250px;">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alert = document.getElementById('alert-message');
        if (alert) {
            // ৫ সেকেন্ড (৫০০০ মিলিসেকেন্ড) পর এটি ফেড আউট হয়ে যাবে
            setTimeout(function() {
                // বুটস্ট্র্যাপের বিল্ট-ইন ক্লাস ব্যবহার করে ক্লোজ করা
                let bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000); 
        }
    });
</script>
