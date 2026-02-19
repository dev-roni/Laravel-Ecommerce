// js/main.js

// সাইডবার টগল ফাংশন
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarToggle');
    const overlay = document.getElementById('overlay');

    if(toggleBtn && sidebar) {
        function toggleSidebar() {
            sidebar.classList.toggle('active');
            if(overlay) overlay.classList.toggle('active');
        }
        toggleBtn.addEventListener('click', toggleSidebar);
        if(overlay) overlay.addEventListener('click', toggleSidebar);
    }
});

// টোস্ট নোটিফিকেশন ফাংশন
function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if(!container) return;

    const toastEl = document.createElement('div');
    let bgClass = 'text-bg-primary';
    if(type === 'success') bgClass = 'text-bg-success';
    if(type === 'danger' || type === 'warning') bgClass = 'text-bg-danger';
    if(type === 'info') bgClass = 'text-bg-info';

    toastEl.className = `toast align-items-center ${bgClass} border-0 show`;
    toastEl.setAttribute('role', 'alert');
    toastEl.setAttribute('aria-live', 'assertive');
    toastEl.setAttribute('aria-atomic', 'true');
    
    toastEl.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    container.appendChild(toastEl);

    setTimeout(() => {
        toastEl.remove();
    }, 3000);
}