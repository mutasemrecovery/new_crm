/* ============================================================
   NovaCRM Admin — app.js
   ============================================================ */

// ── Sidebar Toggle ──────────────────────────────────────────
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.remove('open');
    overlay.classList.remove('open');
}

// ── Notification Dropdown ────────────────────────────────────
function toggleNotif() {
    const el = document.getElementById('notifDropdown');
    el.classList.toggle('open');
    closeAdminMenu();
}

// ── Admin Avatar Dropdown ────────────────────────────────────
function toggleAdminMenu() {
    const el = document.getElementById('adminDropdown');
    el.classList.toggle('open');
    closeNotif();
}

function closeNotif() {
    const el = document.getElementById('notifDropdown');
    if (el) el.classList.remove('open');
}

function closeAdminMenu() {
    const el = document.getElementById('adminDropdown');
    if (el) el.classList.remove('open');
}

// ── Close dropdowns on outside click ─────────────────────────
document.addEventListener('click', function (e) {
    // Notif
    const notifWrap = document.querySelector('.notif-wrap');
    if (notifWrap && !notifWrap.contains(e.target)) closeNotif();

    // Admin menu
    const adminWrap = document.querySelector('.admin-menu-wrap');
    if (adminWrap && !adminWrap.contains(e.target)) closeAdminMenu();

    // Sidebar overlay (mobile)
    if (window.innerWidth <= 900) {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.querySelector('.topbar-toggle');
        if (sidebar && !sidebar.contains(e.target) && toggle && !toggle.contains(e.target)) {
            closeSidebar();
        }
    }
});

// ── Active nav item highlight ─────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const path = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(link => {
        const href = link.getAttribute('href');
        if (href && href !== '#' && path.startsWith(href)) {
            link.classList.add('active');
        }
    });
});

// ── Delete confirmation ───────────────────────────────────────
function confirmDelete(formId, message) {
    const msg = message || 'Are you sure you want to delete this item?';
    if (confirm(msg)) {
        document.getElementById(formId).submit();
    }
}