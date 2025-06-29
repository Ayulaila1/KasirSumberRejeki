document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const content = document.getElementById('content');
    const toggleBtn = document.getElementById('toggleSidebar');

    toggleBtn?.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle('show');
        } else {
            sidebar.classList.toggle('sidebar-collapsed');
            content.classList.toggle('expanded');
        }
    });
});
