/* RecoveryCRM Employee Portal — app.js */

function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}
function toggleNotif() {
    document.getElementById('notifDropdown').classList.toggle('open');
    closeUserMenu();
}
function toggleUserMenu() {
    document.getElementById('userDropdown').classList.toggle('open');
    closeNotif();
}
function closeNotif()    { document.getElementById('notifDropdown')?.classList.remove('open'); }
function closeUserMenu() { document.getElementById('userDropdown')?.classList.remove('open'); }

document.addEventListener('click', function (e) {
    if (!document.querySelector('.notif-wrap')?.contains(e.target))   closeNotif();
    if (!document.querySelector('.user-menu-wrap')?.contains(e.target)) closeUserMenu();
    if (window.innerWidth <= 900) {
        const sidebar = document.getElementById('sidebar');
        const toggle  = document.querySelector('.topbar-toggle');
        if (sidebar && !sidebar.contains(e.target) && toggle && !toggle.contains(e.target)) closeSidebar();
    }
});

function confirmDelete(formId, message) {
    if (confirm(message || 'Are you sure?')) document.getElementById(formId).submit();
}