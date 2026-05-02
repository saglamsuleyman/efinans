const adminToggle = document.querySelector('.admin-menu-toggle');
const adminSidebar = document.querySelector('.admin-sidebar-panel');

if (adminToggle && adminSidebar) {
    adminToggle.addEventListener('click', () => {
        adminSidebar.classList.toggle('open');
    });
}

document.querySelectorAll('[data-confirm]').forEach((item) => {
    item.addEventListener('click', (event) => {
        if (!confirm(item.getAttribute('data-confirm'))) {
            event.preventDefault();
        }
    });
});
