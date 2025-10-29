{{-- Sidebar Component for SEDAP Application --}}
<aside class="sidebar" id="mainSidebar">
    {{-- HEADER SECTION --}}
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            {{-- Logo SEDAP --}}
            <div class="brand-logo">
                <i class="bi bi-bar-chart-line-fill text-primary"></i>
            </div>
            <div class="brand-text">
                <span class="brand-title">SEDAP</span>
                <span class="brand-subtitle">BPS Bantul</span>
            </div>
        </a>
        <button class="sidebar-toggle-btn" type="button" id="sidebarInternalToggle">
            <i class="bi bi-list"></i>
        </button>
    </div>

    {{-- NAVIGATION MENU --}}
    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            {{-- Dashboard --}}
            <li class="menu-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link">
                    <i class="bi bi-grid-1x2-fill menu-icon"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>

            {{-- Wilayah Tugas / Blok Sensus --}}
            <li class="menu-item {{ request()->routeIs('wilayahTugas.*') ? 'active' : '' }}">
                <a href="{{ route('wilayahTugas.index') }}" class="menu-link">
                    <i class="bi bi-map-fill menu-icon"></i>
                    <span class="menu-text">Wilayah Tugas</span>
                    @if (isset($totalWilayahTugas) && $totalWilayahTugas > 0)
                        <span class="menu-badge">{{ $totalWilayahTugas }}</span>
                    @endif
                </a>
            </li>

            {{-- Sampel Rumah Tangga / DSRT --}}
            <li class="menu-item {{ request()->routeIs('dsrt.*') ? 'active' : '' }}">
                <a href="{{ route('dsrt.index') }}" class="menu-link">
                    <i class="bi bi-file-earmark-text-fill menu-icon"></i>
                    <span class="menu-text">Sampel Rumah Tangga</span>
                    @if (isset($totalDsrt) && $totalDsrt > 0)
                        <span class="menu-badge">{{ $totalDsrt }}</span>
                    @endif
                </a>
            </li>

            {{-- Responden --}}
            <li class="menu-item {{ request()->routeIs('responden.*') ? 'active' : '' }}">
                <a href="{{ route('responden.index') }}" class="menu-link">
                    <i class="bi bi-people-fill menu-icon"></i>
                    <span class="menu-text">Responden</span>
                    @if (isset($totalResponden) && $totalResponden > 0)
                        <span class="menu-badge">{{ $totalResponden }}</span>
                    @endif
                </a>
            </li>

            {{-- Divider --}}
            <li class="menu-divider">
                <span class="menu-divider-text">Pengaturan</span>
            </li>

            {{-- Pengguna --}}
            <li class="menu-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <a href="{{ route('pengguna.index') }}" class="menu-link">
                    <i class="bi bi-person-fill-gear menu-icon"></i>
                    <span class="menu-text">Pengguna</span>
                    @if (isset($totalPengguna) && $totalPengguna > 0)
                        <span class="menu-badge">{{ $totalPengguna }}</span>
                    @endif
                </a>
            </li>

            {{-- Pengaturan (Optional) --}}
            {{-- <li class="menu-item">
                <a href="#" class="menu-link">
                    <i class="bi bi-gear-fill menu-icon"></i>
                    <span class="menu-text">Pengaturan</span>
                </a>
            </li> --}}
        </ul>
    </nav>

    {{-- SIDEBAR FOOTER - USER PROFILE --}}
    <div class="sidebar-footer">
        <div class="user-profile">
            <button class="user-profile-btn" id="userProfileToggle" type="button">
                <div class="user-avatar">
                    @if (Auth::check() && Auth::user()->avatar)
                        <img src="{{ Auth::user()->avatar }}" alt="Avatar">
                    @else
                        <i class="bi bi-person-circle"></i>
                    @endif
                </div>
                <div class="user-info">
                    <span class="user-name">{{ Auth::user()->nama ?? (Auth::user()->name ?? 'Admin') }}</span>
                    <span class="user-email">{{ Auth::user()->email ?? 'admin@sedap.com' }}</span>
                </div>
                <i class="bi bi-chevron-up profile-arrow"></i>
            </button>

            {{-- User Dropdown Menu --}}
            <div class="user-dropdown-menu" id="userDropdownMenu">
                <a href="#" class="dropdown-item">
                    <i class="bi bi-person-fill"></i>
                    <span>Profil Saya</span>
                </a>
                <a href="#" class="dropdown-item">
                    <i class="bi bi-gear-fill"></i>
                    <span>Pengaturan</span>
                </a>
                <div class="dropdown-divider"></div>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>

{{-- POPUP MENU FOR COLLAPSED SIDEBAR --}}
<div id="sidebarPopup" class="sidebar-popup-menu">
    <div class="sidebar-popup-header">
        <span id="popupTitle" class="sidebar-popup-title">Menu</span>
        <button id="popupCloseBtn" class="sidebar-popup-close" type="button">&times;</button>
    </div>
    <div id="popupContent" class="sidebar-popup-content"></div>
</div>

{{-- OVERLAY FOR MOBILE --}}
<div id="sidebarOverlay" class="sidebar-overlay"></div>

{{-- SIDEBAR STYLES --}}
<style>
    :root {
        --sidebar-width: 280px;
        --sidebar-collapsed-width: 70px;
        --sidebar-bg: #ffffff;
        --sidebar-text: #4a5568;
        --sidebar-hover: #f7fafc;
        --sidebar-active: #667eea;
        --sidebar-border: #e2e8f0;
        --sidebar-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        --transition-speed: 0.3s;
    }

    /* SIDEBAR CONTAINER */
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: var(--sidebar-bg);
        border-right: 1px solid var(--sidebar-border);
        box-shadow: var(--sidebar-shadow);
        display: flex;
        flex-direction: column;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 1000;
        transition: width var(--transition-speed) ease, transform var(--transition-speed) ease;
    }

    .sidebar.collapsed {
        width: var(--sidebar-collapsed-width);
    }

    /* SIDEBAR HEADER */
    .sidebar-header {
        padding: 1.5rem 1.25rem;
        border-bottom: 1px solid var(--sidebar-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 70px;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        color: var(--sidebar-text);
        transition: opacity var(--transition-speed);
    }

    .brand-logo {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .brand-logo i {
        font-size: 1.5rem;
        color: white !important;
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        white-space: nowrap;
        overflow: hidden;
    }

    .brand-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1a202c;
        line-height: 1.2;
    }

    .brand-subtitle {
        font-size: 0.75rem;
        color: #718096;
        font-weight: 500;
    }

    .sidebar.collapsed .brand-text {
        display: none;
    }

    .sidebar-toggle-btn {
        background: transparent;
        border: none;
        color: var(--sidebar-text);
        font-size: 1.5rem;
        cursor: pointer;
        padding: 0.25rem;
        border-radius: 6px;
        transition: background-color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
    }

    .sidebar-toggle-btn:hover {
        background-color: var(--sidebar-hover);
    }

    .sidebar.collapsed .sidebar-toggle-btn {
        margin: 0 auto;
    }

    /* SIDEBAR NAVIGATION */
    .sidebar-nav {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 1rem 0;
    }

    .sidebar-nav::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar-nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar-nav::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 3px;
    }

    .sidebar-nav::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-item {
        margin: 0.25rem 0.75rem;
    }

    .menu-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: var(--sidebar-text);
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
        position: relative;
        gap: 0.75rem;
    }

    .menu-link:hover {
        background-color: var(--sidebar-hover);
        color: var(--sidebar-active);
        transform: translateX(2px);
    }

    .menu-item.active .menu-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-weight: 600;
        box-shadow: 0 4px 6px -1px rgba(102, 126, 234, 0.3);
    }

    .menu-item.active .menu-link .menu-icon {
        color: white;
    }

    .menu-icon {
        font-size: 1.25rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--sidebar-text);
    }

    .menu-text {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .sidebar.collapsed .menu-text {
        display: none;
    }

    .menu-badge {
        background: #667eea;
        color: white;
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 12px;
        font-weight: 600;
        min-width: 20px;
        text-align: center;
    }

    .menu-item.active .menu-badge {
        background: rgba(255, 255, 255, 0.3);
    }

    .sidebar.collapsed .menu-badge {
        display: none;
    }

    /* MENU DIVIDER */
    .menu-divider {
        margin: 1rem 1.5rem;
        padding: 0.5rem 0;
        border-top: 1px solid var(--sidebar-border);
    }

    .menu-divider-text {
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 600;
        color: #a0aec0;
        letter-spacing: 0.5px;
    }

    .sidebar.collapsed .menu-divider-text {
        display: none;
    }

    /* SIDEBAR FOOTER - USER PROFILE */
    .sidebar-footer {
        border-top: 1px solid var(--sidebar-border);
        padding: 1rem 0.75rem;
        background: var(--sidebar-bg);
    }

    .user-profile {
        position: relative;
    }

    .user-profile-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: transparent;
        border: 1px solid var(--sidebar-border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        text-align: left;
    }

    .user-profile-btn:hover {
        background-color: var(--sidebar-hover);
        border-color: #cbd5e0;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .user-avatar i {
        font-size: 1.5rem;
        color: white;
    }

    .user-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .user-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .user-name {
        font-size: 0.9rem;
        font-weight: 600;
        color: #1a202c;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-email {
        font-size: 0.75rem;
        color: #718096;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .sidebar.collapsed .user-info {
        display: none;
    }

    .profile-arrow {
        font-size: 1rem;
        color: #718096;
        transition: transform 0.2s;
    }

    .user-profile-btn[aria-expanded="true"] .profile-arrow {
        transform: rotate(180deg);
    }

    .sidebar.collapsed .profile-arrow {
        display: none;
    }

    /* USER DROPDOWN MENU */
    .user-dropdown-menu {
        position: absolute;
        bottom: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid var(--sidebar-border);
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        margin-bottom: 0.5rem;
        opacity: 0;
        visibility: hidden;
        transform: translateY(10px);
        transition: all 0.2s;
        z-index: 1001;
    }

    .user-dropdown-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
        color: var(--sidebar-text);
        text-decoration: none;
        transition: background-color 0.2s;
        border: none;
        background: transparent;
        width: 100%;
        text-align: left;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .dropdown-item:hover {
        background-color: var(--sidebar-hover);
    }

    .dropdown-item.text-danger:hover {
        background-color: #fff5f5;
        color: #e53e3e;
    }

    .dropdown-item i {
        font-size: 1.1rem;
        width: 20px;
    }

    .dropdown-divider {
        height: 1px;
        background: var(--sidebar-border);
        margin: 0.5rem 0;
    }

    /* SIDEBAR POPUP FOR COLLAPSED MODE */
    .sidebar-popup-menu {
        position: fixed;
        left: var(--sidebar-collapsed-width);
        top: 0;
        background: white;
        border: 1px solid var(--sidebar-border);
        border-radius: 8px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        max-width: 300px;
        z-index: 1100;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px);
        transition: all 0.2s;
    }

    .sidebar-popup-menu.show {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }

    .sidebar-popup-header {
        padding: 1rem;
        border-bottom: 1px solid var(--sidebar-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-popup-title {
        font-weight: 600;
        color: #1a202c;
    }

    .sidebar-popup-close {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        color: #718096;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-popup-close:hover {
        color: #1a202c;
    }

    .sidebar-popup-content {
        padding: 0.5rem;
    }

    /* OVERLAY */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 999;
        opacity: 0;
        transition: opacity var(--transition-speed);
    }

    .sidebar-overlay.show {
        display: block;
        opacity: 1;
    }

    /* RESPONSIVE */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        .sidebar.collapsed.show {
            transform: translateX(0);
            width: var(--sidebar-width);
        }
    }

    /* ANIMATION */
    @keyframes slideInLeft {
        from {
            transform: translateX(-20px);
            opacity: 0;
        }

        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .menu-item {
        animation: slideInLeft 0.3s ease forwards;
    }

    .menu-item:nth-child(1) {
        animation-delay: 0.05s;
    }

    .menu-item:nth-child(2) {
        animation-delay: 0.1s;
    }

    .menu-item:nth-child(3) {
        animation-delay: 0.15s;
    }

    .menu-item:nth-child(4) {
        animation-delay: 0.2s;
    }

    .menu-item:nth-child(5) {
        animation-delay: 0.25s;
    }

    .menu-item:nth-child(6) {
        animation-delay: 0.3s;
    }
</style>

{{-- SIDEBAR JAVASCRIPT --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('mainSidebar');
        const sidebarToggle = document.getElementById('sidebarInternalToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const userProfileBtn = document.getElementById('userProfileToggle');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        const sidebarPopup = document.getElementById('sidebarPopup');
        const popupCloseBtn = document.getElementById('popupCloseBtn');

        // Toggle sidebar collapse
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');

                // Save state to localStorage
                if (sidebar.classList.contains('collapsed')) {
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });
        }

        // Load saved sidebar state
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            sidebar.classList.add('collapsed');
        }

        // Mobile sidebar toggle
        const mobileSidebarToggle = document.getElementById('sidebar-toggle');
        if (mobileSidebarToggle) {
            mobileSidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        // Close sidebar when clicking overlay
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // User profile dropdown toggle
        if (userProfileBtn) {
            userProfileBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                userDropdownMenu.classList.toggle('show');
                userProfileBtn.setAttribute('aria-expanded',
                    userDropdownMenu.classList.contains('show') ? 'true' : 'false'
                );
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (userDropdownMenu && !userProfileBtn.contains(e.target)) {
                userDropdownMenu.classList.remove('show');
                userProfileBtn.setAttribute('aria-expanded', 'false');
            }
        });

        // Handle menu hover in collapsed mode
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            const menuLink = item.querySelector('.menu-link');

            item.addEventListener('mouseenter', function() {
                if (sidebar.classList.contains('collapsed')) {
                    const menuText = menuLink.querySelector('.menu-text').textContent;
                    showPopup(menuText, item.getBoundingClientRect());
                }
            });

            item.addEventListener('mouseleave', function() {
                if (sidebar.classList.contains('collapsed')) {
                    hidePopup();
                }
            });
        });

        // Popup functions
        function showPopup(title, rect) {
            const popupTitle = document.getElementById('popupTitle');
            popupTitle.textContent = title;

            sidebarPopup.style.top = rect.top + 'px';
            sidebarPopup.classList.add('show');
        }

        function hidePopup() {
            sidebarPopup.classList.remove('show');
        }

        if (popupCloseBtn) {
            popupCloseBtn.addEventListener('click', hidePopup);
        }

        // Close mobile sidebar when clicking menu item
        menuItems.forEach(item => {
            item.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            }, 250);
        });
    });
</script>
