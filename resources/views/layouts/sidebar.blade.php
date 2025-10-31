<!-- SIDEBAR SEDAP START -->
<aside class="sidebar" id="mainSidebar">
    <!-- HEADER SECTION -->
    <div class="sidebar-header">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="brand-logo">
                <img src="{{ asset('logov.png') }}" alt="Logo SEDAP">
            </div>
            <div class="brand-text">
                <span class="brand-title">SEDAP</span>
                <span class="brand-subtitle">BPS Bantul</span>
            </div>
        </a>
        <button class="sidebar-toggle-btn" type="button" id="sidebarInternalToggle" aria-label="Toggle Sidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>
    <!-- NAVIGATION MENU -->
    <nav class="sidebar-nav">
        <ul class="sidebar-menu">
            <li class="menu-item {{ request()->routeIs('dashboard*') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="menu-link" title="Dashboard">
                    <i class="bi bi-grid-1x2-fill menu-icon"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('wilayahTugas.*') ? 'active' : '' }}">
                <a href="{{ route('wilayahTugas.index') }}" class="menu-link" title="Wilayah Tugas">
                    <i class="bi bi-map-fill menu-icon"></i>
                    <span class="menu-text">Wilayah Tugas</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('dsrt.*') ? 'active' : '' }}">
                <a href="{{ route('dsrt.index') }}" class="menu-link" title="Sampel Rumah Tangga">
                    <i class="bi bi-file-earmark-text-fill menu-icon"></i>
                    <span class="menu-text">Sampel Rumah Tangga</span>
                </a>
            </li>
            <li class="menu-item {{ request()->routeIs('responden.*') ? 'active' : '' }}">
                <a href="{{ route('responden.index') }}" class="menu-link" title="Responden">
                    <i class="bi bi-people-fill menu-icon"></i>
                    <span class="menu-text">Responden</span>
                </a>
            </li>
            <li class="menu-divider">
                <span class="menu-divider-text">Pengaturan</span>
            </li>
            <li class="menu-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
                <a href="{{ route('pengguna.index') }}" class="menu-link" title="Pengguna">
                    <i class="bi bi-person-fill-gear menu-icon"></i>
                    <span class="menu-text">Pengguna</span>
                </a>
            </li>
        </ul>
    </nav>
    <!-- SIDEBAR FOOTER - USER PROFILE -->
    <div class="sidebar-footer">
        <div class="user-profile">
            <button class="user-profile-btn" id="userProfileToggle" type="button" aria-label="User Menu">
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
            <!-- User Dropdown Menu -->
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
<!-- POPUP MENU FOR COLLAPSED SIDEBAR -->
<div id="sidebarPopup" class="sidebar-popup-menu">
    <div class="sidebar-popup-header">
        <span id="popupTitle" class="sidebar-popup-title">Menu</span>
        <button id="popupCloseBtn" class="sidebar-popup-close" type="button">&times;</button>
    </div>
    <div id="popupContent" class="sidebar-popup-content"></div>
</div>
<!-- OVERLAY FOR MOBILE -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<style>
/* ========================================
   CSS VARIABLES
   ======================================== */
:root {
    --sidebar-width: 280px;
    --sidebar-collapsed-width: 75px;
    --sidebar-bg: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    --sidebar-text: #cbd5e1;
    --sidebar-text-muted: #64748b;
    --sidebar-hover: rgba(255, 255, 255, 0.08);
    --sidebar-active: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    --sidebar-border: rgba(255, 255, 255, 0.1);
    --sidebar-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
    --accent-primary: #6366f1;
    --accent-secondary: #8b5cf6;
    --transition-speed: 0.4s;
    --transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
    --smooth-transition: cubic-bezier(0.65, 0, 0.35, 1);
}

/* ========================================
   SIDEBAR CONTAINER
   ======================================== */
.sidebar {
    width: var(--sidebar-width);
    height: 100vh;
    background: var(--sidebar-bg);
    backdrop-filter: blur(10px);
    border-right: 1px solid var(--sidebar-border);
    box-shadow: var(--sidebar-shadow);
    display: flex;
    flex-direction: column;
    position: fixed;
    left: 0;
    top: 0;
    z-index: 1000;
    transition: width var(--transition-speed) var(--transition-timing);
    overflow: hidden;
}

.sidebar::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 200px;
    background: radial-gradient(circle at 50% 0%, rgba(99, 102, 241, 0.15), transparent 70%);
    pointer-events: none;
}

.sidebar.collapsed {
    width: var(--sidebar-collapsed-width);
}

/* ========================================
   SIDEBAR HEADER
   ======================================== */
.sidebar-header {
    padding: 1.75rem 1.5rem;
    border-bottom: 1px solid var(--sidebar-border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    min-height: 80px;
    position: relative;
    z-index: 1;
    gap: 1rem;
}

/* Saat collapsed, header jadi centered */
.sidebar.collapsed .sidebar-header {
    padding: 1.75rem 0.75rem; /* Kurangi padding horizontal */
    justify-content: center; /* Pastikan tombol di tengah */
    min-height: 80px; /* Jaga tinggi header */
    position: relative; /* Pastikan posisi relatif untuk tombol absolut */
}

/* Brand Section */
.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 1rem;
    text-decoration: none;
    transition: all var(--transition-speed) var(--transition-timing);
    flex: 1;
    min-width: 0;
}

.sidebar.collapsed .sidebar-brand {
    display: none;
}

/* Logo */
.brand-logo {
    width: 40px; /* Diperkecil dari 45px */
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    transition: all var(--transition-speed) var(--transition-timing);
}

.brand-logo img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 12px;
}

.sidebar-brand:hover .brand-logo {
    transform: scale(1.05) rotate(-5deg);
}

/* Brand Text */
.brand-text {
    display: flex;
    flex-direction: column;
    white-space: nowrap;
    overflow: hidden;
    transition: all var(--transition-speed) var(--transition-timing);
    min-width: 0;
}

.sidebar.collapsed .brand-text {
    opacity: 0;
    width: 0;
    visibility: hidden;
}

.brand-title {
    font-size: 1.25rem; /* Diperkecil dari 1.375rem */
    font-weight: 800;
    background: linear-gradient(135deg, #ffffff 0%, #e0e7ff 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    line-height: 1.2;
    letter-spacing: -0.5px;
}

.brand-subtitle {
    font-size: 0.75rem;
    color: var(--sidebar-text-muted);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Toggle Button */
.sidebar-toggle-btn {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.12);
    color: var(--sidebar-text);
    font-size: 1.125rem; /* Diperkecil dari 1.25rem */
    cursor: pointer;
    padding: 0;
    border-radius: 10px;
    transition: all 0.2s var(--transition-timing);
    width: 36px; /* Diperkecil dari 38px */
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.sidebar.collapsed .sidebar-toggle-btn {
    position: static;
    transform: none;
}

.sidebar-toggle-btn:hover {
    background: rgba(255, 255, 255, 0.15);
    border-color: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.sidebar.collapsed .sidebar-toggle-btn:hover {
    transform: scale(1.05);
}

.sidebar-toggle-btn:active {
    transform: scale(0.95);
}

.sidebar.collapsed .sidebar-toggle-btn:active {
    transform: scale(0.95);
}

/* ========================================
   SIDEBAR NAVIGATION
   ======================================== */
.sidebar-nav {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
    padding: 1.25rem 0;
    position: relative;
    z-index: 1;
}

.sidebar-nav::-webkit-scrollbar {
    width: 5px;
}

.sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.sidebar-nav::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
}

.sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.3);
}

/* Menu List */
.sidebar-menu {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-item {
    margin: 0.375rem 1rem;
    position: relative;
}

.sidebar.collapsed .menu-item {
    margin: 0.5rem auto; /* Center dengan margin auto */
    display: flex;
    justify-content: center;
    align-items: center;
}

/* Menu Link */
.menu-link {
    display: flex;
    align-items: center;
    padding: 0.875rem 1.125rem;
    color: var(--sidebar-text);
    text-decoration: none;
    border-radius: 12px;
    transition: all 0.25s var(--transition-timing);
    position: relative;
    gap: 1rem;
    overflow: hidden;
}

.sidebar.collapsed .menu-link {
    justify-content: center;
    padding: 0.875rem 0.5rem;
    height: 50px; /* Beri tinggi tetap agar konsisten */
    width: 50px; /* Beri lebar tetap */
}

.menu-link::before {
    content: '';
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    width: 4px;
    height: 0;
    background: linear-gradient(180deg, #6366f1, #8b5cf6);
    border-radius: 0 4px 4px 0;
    transition: height 0.3s var(--transition-timing);
}

.sidebar.collapsed .menu-link::before {
    display: none;
}

.menu-link:hover {
    background: var(--sidebar-hover);
    color: #ffffff;
    transform: translateX(4px);
}

.sidebar.collapsed .menu-link:hover {
    transform: scale(1.08);
}

.menu-link:hover::before {
    height: 70%;
}

/* Active State */
.menu-item.active .menu-link {
    background: var(--sidebar-active);
    color: white;
    font-weight: 600;
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
}

.menu-item.active .menu-link::before {
    height: 0;
}

.menu-item.active .menu-link .menu-icon {
    color: white;
}

/* Menu Icon */
.menu-icon {
    font-size: 1.125rem;
    width: 24px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: var(--sidebar-text);
    transition: all 0.2s var(--transition-timing);
}

.menu-link:hover .menu-icon {
    transform: scale(1.1);
}

/* Menu Text */
.menu-text {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    font-size: 0.875rem; /* Diperkecil dari 0.9375rem */
    font-weight: 500;
    letter-spacing: -0.2px;
    transition: all var(--transition-speed) var(--transition-timing);
}

.sidebar.collapsed .menu-text {
    opacity: 0;
    width: 0;
    visibility: hidden;
}

/* ========================================
   MENU DIVIDER
   ======================================== */
.menu-divider {
    margin: 1.5rem 1.75rem;
    padding: 0.625rem 0;
    border-top: 1px solid var(--sidebar-border);
    position: relative;
}

.sidebar.collapsed .menu-divider {
    margin: 1.5rem 0.75rem;
}

.menu-divider::before {
    content: '';
    position: absolute;
    top: -1px;
    left: 0;
    width: 40px;
    height: 1px;
    background: linear-gradient(90deg, #6366f1, transparent);
}

.sidebar.collapsed .menu-divider::before {
    width: 100%;
}

.menu-divider-text {
    font-size: 0.6875rem;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--sidebar-text-muted);
    letter-spacing: 1.2px;
    transition: all var(--transition-speed) var(--transition-timing);
}

.sidebar.collapsed .menu-divider-text {
    opacity: 0;
    width: 0;
    visibility: hidden;
    display: none;
}
.sidebar.collapsed .menu-link {
    justify-content: center;
    padding: 0.75rem;
    height: 48px; /* Diperkecil dari 50px */
    width: 48px; /* Diperkecil dari 50px */
    margin: 0 auto; /* nolkan padding agar ikon benar-benar center */
}

.sidebar.collapsed .menu-icon {
    margin: 0;
    width: 22px; /* Lebih kecil saat collapsed */
    height: 22px;
    font-size: 1rem;;
}

/* ========================================
   SIDEBAR FOOTER - USER PROFILE
   ======================================== */
.sidebar-footer {
    border-top: 1px solid var(--sidebar-border);
    padding: 1.25rem 1rem;
    background: rgba(0, 0, 0, 0.2);
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 1;
}

.sidebar.collapsed .sidebar-footer {
    padding: 1.25rem 0;
    border-top: 1px solid var(--sidebar-border);
}

.user-profile {
    position: relative;
    display: flex;
    justify-content: center;
}

/* User Profile Button */
.user-profile-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.875rem 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.25s var(--transition-timing);
    text-align: left;
}

.sidebar.collapsed .user-profile-btn {
    justify-content: center;
    align-items: center;
    padding: 0.5rem;
    height: 48px; /* Diperkecil dari 50px */
    width: 48px;
    background: transparent;
    border: none;
    box-shadow: none;
    margin: 0 auto; /* Center horizontal */
}

.sidebar.collapsed .user-profile-btn:hover {
    transform: scale(1.05); /* Hover standar */
    background: var(--sidebar-hover); /* Efek hover seperti menu */
    box-shadow: none;
}

.user-profile-btn:hover {
    background: rgba(255, 255, 255, 0.1);
    border-color: rgba(255, 255, 255, 0.2);
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
}

/* User Avatar */
.user-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    overflow: hidden;
    border: 2px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.4);
    transition: all 0.3s var(--transition-timing);
}

.user-profile-btn:hover .user-avatar {
    transform: scale(1.05);
    box-shadow: 0 6px 16px rgba(99, 102, 241, 0.6);
}

.user-avatar i {
    font-size: 1.25rem;
    color: white;
}

.user-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* User Info */
.user-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    min-width: 0;
    transition: all var(--transition-speed) var(--transition-timing);
}

.sidebar.collapsed .user-info,
.sidebar.collapsed .profile-arrow {
    display: none; /* Pastikan benar-benar hilang */
}

.sidebar.collapsed .user-avatar {
     width: 36px; /* Sedikit lebih kecil */
     height: 36px;
     border-width: 1px; /* Border lebih tipis */
     box-shadow: none; /* Hapus shadow agar lebih flat */
}

.sidebar.collapsed .user-profile-btn:hover .user-avatar {
    transform: none; /* Hapus transform hover avatar */
    box-shadow: none;
}

.user-name {
    font-size: 0.875rem; /* Diperkecil dari 0.9375rem */
    font-weight: 600;
    color: #fff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.user-email {
    font-size: 0.6875rem; /* Diperkecil dari 0.75rem */
    color: var(--sidebar-text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

/* Profile Arrow */
.profile-arrow {
    font-size: 0.875rem; /* Diperkecil dari 1rem */
    color: var(--sidebar-text);
    transition: all 0.3s var(--transition-timing);
    flex-shrink: 0;
}

.user-profile-btn[aria-expanded="true"] .profile-arrow {
    transform: rotate(180deg);
    color: #ffffff;
}

/* ========================================
   USER DROPDOWN MENU
   ======================================== */
.user-dropdown-menu {
    position: absolute;
    bottom: calc(100% + 0.75rem);
    left: 0;
    right: 0;
    background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid var(--sidebar-border);
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4), 
                0 10px 10px -5px rgba(0, 0, 0, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
    opacity: 0;
    visibility: hidden;
    transform: translateY(10px) scale(0.95);
    transition: all 0.3s var(--transition-timing);
    z-index: 1001;
    overflow: hidden;
}

.user-dropdown-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0) scale(1);
}

/* Dropdown Item */
.dropdown-item {
    display: flex;
    align-items: center;
    gap: 0.875rem;
    padding: 0.875rem 1.125rem;
    color: var(--sidebar-text);
    text-decoration: none;
    transition: all 0.2s var(--transition-timing);
    border: none;
    background: transparent;
    width: 100%;
    text-align: left;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    position: relative;
}

.dropdown-item::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #6366f1, #8b5cf6);
    transform: scaleY(0);
    transition: transform 0.2s var(--transition-timing);
}

.dropdown-item:hover {
    background: var(--sidebar-hover);
    color: #ffffff;
    padding-left: 1.375rem;
}

.dropdown-item:hover::before {
    transform: scaleY(1);
}

.dropdown-item.text-danger {
    color: #f87171;
}

.dropdown-item.text-danger:hover {
    background: rgba(248, 113, 113, 0.1);
    color: #fca5a5;
}

.dropdown-item i {
    font-size: 1rem; /* Diperkecil dari 1.125rem */
    width: 20px; /* Diperkecil dari 22px */
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s var(--transition-timing);
}

.dropdown-item:hover i {
    transform: scale(1.1);
}

.dropdown-divider {
    height: 1px;
    background: var(--sidebar-border);
    margin: 0.5rem 0;
    opacity: 0.5;
}

/* ========================================
   SIDEBAR POPUP FOR COLLAPSED MODE
   ======================================== */
.sidebar-popup-menu {
    position: fixed;
    left: calc(var(--sidebar-collapsed-width) + 0.75rem);
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid var(--sidebar-border);
    border-radius: 12px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4),
                0 10px 10px -5px rgba(0, 0, 0, 0.2);
    min-width: 220px;
    max-width: 280px;
    z-index: 1100;
    opacity: 0;
    visibility: hidden;
    transform: translateX(-10px) scale(0.95);
    transition: all 0.2s var(--transition-timing);
    overflow: hidden;
}

.sidebar-popup-menu.show {
    opacity: 1;
    visibility: visible;
    transform: translateX(0) scale(1);
}

.sidebar-popup-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--sidebar-border);
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(99, 102, 241, 0.1);
}

.sidebar-popup-title {
    font-weight: 700;
    font-size: 0.9375rem;
    color: #ffffff;
    letter-spacing: -0.2px;
}

.sidebar-popup-close {
    background: rgba(255, 255, 255, 0.1);
    border: none;
    font-size: 1.375rem;
    color: var(--sidebar-text);
    cursor: pointer;
    padding: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    transition: all 0.2s var(--transition-timing);
}

.sidebar-popup-close:hover {
    background: rgba(255, 255, 255, 0.2);
    color: #ffffff;
    transform: rotate(90deg);
}

.sidebar-popup-content {
    padding: 0.75rem;
}

/* ========================================
   OVERLAY
   ======================================== */
.sidebar-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    backdrop-filter: blur(4px);
    z-index: 999;
    opacity: 0;
    transition: opacity var(--transition-speed) var(--transition-timing);
}

.sidebar-overlay.show {
    display: block;
    opacity: 1;
}

/* ========================================
   ANIMATIONS
   ======================================== */
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
    animation: slideInLeft 0.4s var(--transition-timing) forwards;
    opacity: 0;
}

.menu-item:nth-child(1) { animation-delay: 0.05s; }
.menu-item:nth-child(2) { animation-delay: 0.1s; }
.menu-item:nth-child(3) { animation-delay: 0.15s; }
.menu-item:nth-child(4) { animation-delay: 0.2s; }
.menu-item:nth-child(5) { animation-delay: 0.25s; }
.menu-item:nth-child(6) { animation-delay: 0.3s; }
.menu-item:nth-child(7) { animation-delay: 0.35s; }

/* ========================================
   RESPONSIVE
   ======================================== */
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

/* ========================================
   ACCESSIBILITY
   ======================================== */
@media (prefers-reduced-motion: reduce) {
    *,
    *::before,
    *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Focus States */
.menu-link:focus-visible,
.sidebar-toggle-btn:focus-visible,
.user-profile-btn:focus-visible,
.dropdown-item:focus-visible {
    outline: 2px solid #6366f1;
    outline-offset: 2px;
}


</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('mainSidebar');
    const sidebarToggle = document.getElementById('sidebarInternalToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const userProfileBtn = document.getElementById('userProfileToggle');
    const userDropdownMenu = document.getElementById('userDropdownMenu');
    // Icon toggle
    function updateToggleIcon() {
        if (!sidebarToggle) return;
        const icon = sidebarToggle.querySelector('i');
        if (sidebar.classList.contains('collapsed')) {
            icon.classList.remove('bi-list');
            icon.classList.add('bi-arrow-bar-right');
        } else {
            icon.classList.remove('bi-arrow-bar-right');
            icon.classList.add('bi-list');
        }
    }
    // Toggle Sidebar (Desktop)
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            // Extra: transisi smooth dengan menjeda child lewat CSS sudah cukup (tidak butuh JS delay)
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebarCollapsed', sidebar.classList.contains('collapsed') ? 'true' : 'false');
            updateToggleIcon();
        });
    }
    // LocalStorage collapse state
    const savedState = localStorage.getItem('sidebarCollapsed');
    if (savedState === 'true') {
        sidebar.classList.add('collapsed');
    }
    updateToggleIcon();
    // Mobile Sidebar toggle
    const mobileSidebarToggle = document.getElementById('sidebar-toggle');
    if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });
    }
    // Overlay close for mobile
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }
    // User Profile Dropdown
    if (userProfileBtn) {
        userProfileBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            if (sidebar.classList.contains('collapsed')) return;
            userDropdownMenu.classList.toggle('show');
            userProfileBtn.setAttribute('aria-expanded', userDropdownMenu.classList.contains('show') ? 'true' : 'false');
        });
    }
    document.addEventListener('click', function(e) {
        if (userDropdownMenu && userProfileBtn &&
            !userProfileBtn.contains(e.target) &&
            !userDropdownMenu.contains(e.target)
        ) {
            userDropdownMenu.classList.remove('show');
            userProfileBtn.setAttribute('aria-expanded', 'false');
        }
    });
    // Keyboard ESC handler
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (userDropdownMenu && userDropdownMenu.classList.contains('show')) {
                userDropdownMenu.classList.remove('show');
                userProfileBtn.setAttribute('aria-expanded', 'false');
                userProfileBtn.focus();
            }
            if (window.innerWidth <= 768 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        }
    });
});
</script>