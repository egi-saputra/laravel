document.addEventListener("DOMContentLoaded", function () {
        const toggleButton = document.getElementById("toggleSidebarMenu");
        const toggleIcon = document.getElementById("toggleIcon");
        const sidebarMenu = document.getElementById("sidebarMenu");

        if (toggleButton && sidebarMenu && toggleIcon) {
            // Inisialisasi untuk mobile
            if (window.innerWidth < 768) {
                sidebarMenu.style.overflow = 'hidden';
                sidebarMenu.style.transition = 'max-height 0.4s ease';
                sidebarMenu.style.maxHeight = '0px';
            }

            toggleButton.addEventListener("click", function () {
                const isCollapsed = sidebarMenu.style.maxHeight === '0px';

                if (isCollapsed) {
                    sidebarMenu.style.maxHeight = sidebarMenu.scrollHeight + 'px';
                    toggleIcon.classList.remove('bi-list');
                    toggleIcon.classList.add('bi-x');
                } else {
                    sidebarMenu.style.maxHeight = '0px';
                    toggleIcon.classList.remove('bi-x');
                    toggleIcon.classList.add('bi-list');
                }
            });

            // Reset saat resize ke desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768) {
                    sidebarMenu.style.maxHeight = null;
                    sidebarMenu.style.overflow = null;
                    toggleIcon.classList.remove('bi-x');
                    toggleIcon.classList.add('bi-list');
                } else {
                    sidebarMenu.style.maxHeight = '0px';
                    sidebarMenu.style.overflow = 'hidden';
                    toggleIcon.classList.remove('bi-x');
                    toggleIcon.classList.add('bi-list');
                }
            });
        }
    });
