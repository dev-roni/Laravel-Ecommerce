// ── Navbar shadow on scroll ──────────────────────────────
window.addEventListener('scroll', () => {
    document.querySelector('.site-navbar').style.boxShadow =
        window.scrollY > 20
            ? '0 4px 22px rgba(10,37,64,.13)'
            : '0 1px 14px rgba(10,37,64,.07)';
});

// ── Flash auto-dismiss ───────────────────────────────────
['flash-success', 'flash-error'].forEach(id => {
    const el = document.getElementById(id);
    if (el) setTimeout(() => {
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 300);
    }, 4000);
});

// ── Mobile sub-category toggle (recursive-safe) ──────────
function toggleMobSub(btn, subId) {

    const sub = document.getElementById(subId);

    // already open?
    if (sub.classList.contains('open')) {
        sub.classList.remove('open');
        btn.classList.remove('open');
        return;
    }

    const parentPanel =
        btn.closest('.mob-sub-links') ||
        btn.closest('.offcanvas-body');

    if (parentPanel) {

        parentPanel
            .querySelectorAll('.mob-parent-toggle.open')
            .forEach(el => el.classList.remove('open'));

        parentPanel
            .querySelectorAll('.mob-sub-links.open')
            .forEach(el => {

                el.querySelectorAll('.mob-sub-links')
                    .forEach(inner => inner.classList.remove('open'));

                el.querySelectorAll('.mob-parent-toggle')
                    .forEach(inner => inner.classList.remove('open'));

                el.classList.remove('open');
            });
    }

    sub.classList.add('open');
    btn.classList.add('open');
}
// ── Custom Toast ─────────────────────────────────────────
function showToast(message, type = 'success') {
    const stack = document.getElementById('toast-stack');
    const icons = { success: 'fa-circle-check', error: 'fa-circle-exclamation', info: 'fa-circle-info' };
    const toast = document.createElement('div');
    toast.className = `toast-item toast-${type}`;
    toast.innerHTML = `
    <i class="fa-solid ${icons[type] || icons.info}" style="font-size:1rem;flex-shrink:0"></i>
    <span>${message}</span>
    <button class="toast-close-btn" onclick="this.parentElement.remove()">
      <i class="fa-solid fa-xmark"></i>
    </button>`;
    stack.appendChild(toast);
    setTimeout(() => {
        toast.style.cssText += 'opacity:0;transform:translateY(8px);transition:all .3s';
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}
window.showToast = showToast;

// ── Global addToCart ─────────────────────────────────────
function addToCart(productId, variantId = null, quantity = 1) {
    fetch(window.App.cartAddUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.App.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity }),
    })
        .then(async r => {
            if (r.status === 401) { window.location.href = window.App.loginUrl; return; }
            if (!r.ok) throw new Error('Request failed');
            return r.json();
        })
        .then(data => {
            if (!data) return;
            const toastEl = document.getElementById('cart-toast');
            const toastMsg = document.getElementById('toast-message');

            if (data.success) {
                toastEl.classList.remove('text-bg-danger', 'text-bg-success');
                toastEl.classList.add(data.success ? 'text-bg-success' : 'text-bg-danger');
                toastMsg.textContent = data.message || 'পণ্য কার্টে যোগ হয়েছে!';
                // update badge
                let badge = document.getElementById('cart-count');
                if (data.count > 0) {
                    if (!badge) {
                        badge = document.createElement('span');
                        badge.id = 'cart-count';
                        badge.className = 'cart-badge-pill';
                        document.querySelector('a[href*="cart"].nav-icon-btn')?.appendChild(badge);
                    }
                    badge.textContent = data.count;
                    badge.style.transform = 'scale(1.4)';
                    setTimeout(() => badge.style.transform = 'scale(1)', 300);
                }
            } else {
                toastEl.classList.replace('text-bg-success', 'text-bg-danger');
                toastMsg.textContent = data.message || 'কিছু একটা সমস্যা হয়েছে।';
            }
            new bootstrap.Toast(toastEl, { delay: 3000 }).show();
        })
        .catch(() => window.location.href = window.App.loginUrl);
}
window.addToCart = addToCart;