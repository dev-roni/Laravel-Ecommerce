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

            if (data.success) {
                const msg = data.message || 'পণ্য কার্টে যোগ হয়েছে!';
                showToast(msg, 'success');
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
                const msg = data.message || 'কিছু একটা সমস্যা হয়েছে!';
                showToast(msg, 'error');
            }
        })
        .catch(() => window.location.href = window.App.loginUrl);
}
window.addToCart = addToCart;



// ── Wishlist Toggle ──────────────────────────────────────
function toggleWishlist(event, productId, btn) {
    event.preventDefault();   // <a> এর default action বন্ধ করবে
    event.stopPropagation();  // parent এ event যেতে দেবে না

    fetch(window.App.wishlistToggle, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': window.App.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ product_id: productId }),
    })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;

            // Icon update
            btn.textContent = data.wished ? '❤️' : '🤍';
            btn.style.color = data.wished ? 'var(--error)' : 'var(--text-secondary)';

            // Bounce animation
            btn.style.transform = 'scale(1.35)';
            setTimeout(() => btn.style.transform = 'scale(1)', 250);

            // Toast
            showToast(data.message, 'success');


        })
        .catch(() => {
            window.location.href = window.App.loginUrl;
        });

}
window.toggleWishlist = toggleWishlist;

// clear recent view
function clearRecentlyViewed() {
    fetch(window.App.recentViewClear, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': window.App.csrfToken,
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(() => {
            // Section সরিয়ে দাও
            const section = document.querySelector('.recently-viewed-section');
            if (section) {
                section.style.opacity = '0';
                section.style.transition = 'opacity .3s';
                setTimeout(() => section.remove(), 300);
            }
            showToast('History clear হয়েছে।', 'success');
        });
}
window.clearRecentlyViewed = clearRecentlyViewed;