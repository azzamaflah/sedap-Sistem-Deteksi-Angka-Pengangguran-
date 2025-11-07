<aside class="sidebar" id="mainSidebar">
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

    <nav class="sidebar-nav" id="mainSidebarNav">
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
            @auth
    @if (Auth::user()->role === 'admin')
        <li class="menu-divider">
            <span class="menu-divider-text">Admin Panel</span>
        </li>
        {{-- Pengguna Menu Item (Reference Style) --}}
        <li class="menu-item {{ request()->routeIs('pengguna.*') ? 'active' : '' }}">
            <a href="{{ route('pengguna.index') }}" class="menu-link" title="Pengguna">
                <i class="bi bi-person-fill-gear menu-icon"></i>
                <span class="menu-text">Pengguna</span>
            </a>
        </li>

        {{-- Kelola Quest Menu Item (Style Updated) --}}
        <li class="menu-item {{ request()->routeIs('admin.config.quest') ? 'active' : '' }}">
            <a href="{{ route('admin.config.quest') }}" class="menu-link" title="Kelola Quest">
                <i class="bi bi-pen menu-icon"></i>
                <span class="menu-text">Kelola Quest</span>
            </a>
        </li>

        {{-- Kelola Rumus Status Menu Item (Style Updated) --}}
        <li class="menu-item {{ request()->routeIs('admin.config.rumus') ? 'active' : '' }}">
            <a href="{{ route('admin.config.rumus') }}" class="menu-link" title="Kelola Rumus Status">
                <i class="bi bi-calculator menu-icon"></i>
                <span class="menu-text">Kelola Rumus Status</span>
            </a>
        </li>

    @endif
@endauth

        </ul>
    </nav>

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


<div id="sidebarPopup" class="sidebar-popup-menu">
    <div class="sidebar-popup-header">
        <span id="popupTitle" class="sidebar-popup-title">Menu</span>
        <button id="popupCloseBtn" class="sidebar-popup-close" type="button">&times;</button>
    </div>
    <div id="popupContent" class="sidebar-popup-content"></div>
</div>

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
        --transition-speed: 0.3s;
        --transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
    }


    .admin-menu {
        margin-top: 20px;
    }

    .admin-menu-title {
        font-size: 1rem;
        font-weight: bold;
        color: var(--sidebar-text-muted);
        margin-bottom: 15px;
        padding-left: 1.5rem;
    }

    .admin-menu-list {
        list-style: none;
        padding-left: 0;
        margin: 0;
    }

    .admin-menu-item {
        margin: 10px 0;
    }

    .admin-menu-link {
        display: flex;
        align-items: center;
        padding: 10px 1.25rem;
        color: var(--sidebar-text);
        text-decoration: none;
        border-radius: 8px;
        transition: background 0.3s ease, color 0.3s ease;
    }

    .admin-menu-link:hover {
        background-color: var(--sidebar-hover);
        color: #fff;
    }

    .admin-menu-link i {
        margin-right: 10px;
        font-size: 1.2rem;
    }

    .admin-menu-item .admin-menu-link.active {
        background: var(--sidebar-active);
        font-weight: bold;
    }

    .sidebar-footer {
        border-top: 1px solid var(--sidebar-border);
        padding: 1rem 1.5rem;
        background-color: var(--sidebar-bg);
    }

    .user-profile-btn {
        display: flex;
        align-items: center;
        padding: 10px;
        background-color: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        width: 100%;
        cursor: pointer;
        transition: background-color 0.2s ease;
    }

    .user-profile-btn:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }

    .user-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        margin-right: 10px;
        background-color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar i {
        font-size: 1.5rem;
        color: white;
    }

    .user-info {
        flex: 1;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .user-email {
        font-size: 0.75rem;
        color: var(--sidebar-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .profile-arrow {
        font-size: 1rem;
        color: var(--sidebar-text);
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
        transition: width var(--transition-speed) var(--transition-timing),
            transform var(--transition-speed) var(--transition-timing);
        overflow: hidden;
    }

    /* Disable transition saat initial load untuk prevent flash */
    .sidebar.no-transition {
        transition: none !important;
    }

    .sidebar.no-transition * {
        transition: none !important;
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
        padding: 1.5rem 1rem;
        border-bottom: 1px solid var(--sidebar-border);
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 80px;
        gap: 1rem;
        position: relative;
        z-index: 1;
    }

    .sidebar.collapsed .sidebar-header {
        justify-content: center;
        padding: 1.5rem 0.5rem;
    }

    /* Brand */
    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 1rem;
        text-decoration: none;
        transition: opacity var(--transition-speed) var(--transition-timing);
        flex: 1;
        min-width: 0;
    }

    .sidebar.collapsed .sidebar-brand {
        opacity: 0;
        pointer-events: none;
        position: absolute;
    }

    .brand-logo {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        flex-shrink: 0;
        overflow: hidden;
        transition: transform 0.2s var(--transition-timing);
    }

    .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .sidebar-brand:hover .brand-logo {
        transform: scale(1.05);
    }

    .brand-text {
        display: flex;
        flex-direction: column;
        white-space: nowrap;
        overflow: hidden;
        min-width: 0;
    }

    .brand-title {
        font-size: 1.25rem;
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
        font-size: 1.125rem;
        cursor: pointer;
        padding: 0;
        border-radius: 10px;
        transition: all 0.2s var(--transition-timing);
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sidebar-toggle-btn:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.2);
        transform: scale(1.05);
    }

    .sidebar-toggle-btn:active {
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

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .menu-item {
        margin: 0.5rem 1rem;
    }

    .sidebar.collapsed .menu-item {
        margin: 0.5rem 0.5rem;
    }

    /* Menu Link */
    .menu-link {
        display: flex;
        align-items: center;
        padding: 0.875rem 1rem;
        color: var(--sidebar-text);
        text-decoration: none;
        border-radius: 12px;
        transition: all 0.2s var(--transition-timing);
        position: relative;
        gap: 1rem;
    }

    .sidebar.collapsed .menu-link {
        justify-content: center;
        padding: 0.875rem;
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
        transform: scale(1.05);
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

    .menu-item.active .menu-link .menu-icon {
        color: white;
    }

    /* Menu Icon */
    .menu-icon {
        font-size: 1.125rem;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s var(--transition-timing);
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
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: -0.2px;
        transition: opacity var(--transition-speed) var(--transition-timing);
    }

    .sidebar.collapsed .menu-text {
        opacity: 0;
        width: 0;
        position: absolute;
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
        transition: opacity var(--transition-speed) var(--transition-timing);
    }

    .sidebar.collapsed .menu-divider-text {
        opacity: 0;
        position: absolute;
    }

    /* ========================================
   SIDEBAR FOOTER
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
        padding: 1.25rem 0.5rem;
    }

    .user-profile {
        position: relative;
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
        transition: all 0.2s var(--transition-timing);
        text-align: left;
    }

    .sidebar.collapsed .user-profile-btn {
        justify-content: center;
        padding: 0.875rem;
        width: 55px;
        margin: 0.5rem auto;
        background: transparent;
        border: none;
        position: relative;
        box-shadow: none;
    }

    .sidebar.collapsed .user-profile-btn .user-avatar i {
    font-size: 1.5rem; /* Sesuaikan ukuran ikon */
    color: var(--sidebar-text); /* Gunakan warna teks standar */
}

    .sidebar.collapsed .user-profile-btn .user-avatar {
    margin: 0; /* Hapus margin yang tidak diperlukan */
    width: 32px; /* Ukuran yang lebih kecil/pas */
    height: 32px;
    background: transparent; /* Hilangkan background avatar */
    border: none; /* Hilangkan border avatar */
    box-shadow: none; /* Hilangkan shadow avatar */
}


    .user-profile-btn:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
    }

    .sidebar.collapsed .user-profile-btn:hover {
        background: var(--sidebar-hover); /* Gunakan efek hover menu link */
    transform: none; /* Hapus transform: scale(1.05) */
    box-shadow: none;
    }

    .sidebar.collapsed .user-info,
.sidebar.collapsed .profile-arrow {
    display: none;
}

    .sidebar.collapsed .user-profile-btn[aria-expanded="true"] {
    background: var(--sidebar-active); /* Gunakan warna aktif menu link */
    box-shadow: 0 8px 16px rgba(99, 102, 241, 0.4), inset 0 1px 0 rgba(255, 255, 255, 0.2);
    transform: none;
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
        transition: transform 0.2s var(--transition-timing);
    }

    .user-profile-btn:hover .user-avatar {
        transform: scale(1.05);
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
        transition: opacity var(--transition-speed) var(--transition-timing);
    }

    .sidebar.collapsed .user-info {
        opacity: 0;
        width: 0;
        position: absolute;
    }

    .user-name {
        font-size: 0.875rem;
        font-weight: 600;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    .user-email {
        font-size: 0.6875rem;
        color: var(--sidebar-text-muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.3;
    }

    /* Profile Arrow */
    .profile-arrow {
        font-size: 0.875rem;
        color: var(--sidebar-text);
        transition: all 0.3s var(--transition-timing);
        flex-shrink: 0;
    }

    .sidebar.collapsed .profile-arrow {
        display: none;
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
        font-size: 0.875rem;
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
        font-size: 1rem;
        width: 20px;
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
   SIDEBAR POPUP (COLLAPSED MODE)
======================================== */
    .sidebar-popup-menu {
        position: fixed;
        left: calc(var(--sidebar-collapsed-width) + 0.75rem);
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1px solid var(--sidebar-border);
        border-radius: 12px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4),
            0 10px 10px -5px rgba(0, 0, 0, 0.2);
        min-width: 100px;
        max-width: 90vh;
        bottom: auto;
        height: auto;
        display: flex;
        flex-direction: column;
        z-index: 1100;
        opacity: 0;
        visibility: hidden;
        transform: translateX(-10px) scale(0.95);
        transition: all 0.2s var(--transition-timing);
        **overflow-y: auto;
        overflow-x: hidden;
    }

    .sidebar-popup-menu.show-from-footer {
    top: auto; /* Tidak terikat di atas */
    bottom: 1.25rem; /* Jarak dari bawah sidebar-footer */
    /* Posisi Lurus Kanan Icon */
    left: calc(var(--sidebar-collapsed-width) + 0.75rem);
    /* Sedikit di atas footer agar tidak bentrok */
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
        flex-shrink: 0;
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
        flex-grow: 1;
        overflow-y: auto;
    }

    .sidebar-popup-content .menu-text {
        opacity: 1 !important;
        width: auto !important;
        position: static !important;
    }

    .sidebar-popup-content .menu-item {
        margin: 0.375rem 0.5rem;
    }

    .sidebar-popup-content .user-dropdown-menu .dropdown-item {
        padding: 0.75rem 1rem;
        /* Sedikit dikecilkan */
        margin-bottom: 0.25rem;
        border-radius: 8px;
        /* Tampilan yang lebih halus */
    }

    .sidebar-popup-content .user-dropdown-menu.show {
        position: static !important;
        /* Pastikan menu tidak mengambang */
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
        padding: 0;
        box-shadow: none;
        /* Hapus shadow ganda */
        background: transparent;
        /* Hapus background ganda */
        border: none;
    }

    /* ========================================
   RESPONSIVE
======================================== */

    /* Mobile (< 768px) */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
            width: var(--sidebar-width);
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

    .menu-link:focus-visible,
    .sidebar-toggle-btn:focus-visible,
    .user-profile-btn:focus-visible,
    .dropdown-item:focus-visible {
        outline: 2px solid #6366f1;
        outline-offset: 2px;
    }
</style>

<script>
    // =========================================================
    // EARLY INITIALIZATION - PREVENT FLASH OF WRONG STATE
    // =========================================================
    (function() {
        const sidebar = document.getElementById('mainSidebar');
        const body = document.body;
        const MOBILE_BREAKPOINT = 768;

        if (!sidebar || window.innerWidth <= MOBILE_BREAKPOINT) return;

        const savedState = localStorage.getItem('sidebarCollapsed');
        const isCollapsed = savedState === 'true';

        // Add no-transition class to prevent animation on load
        sidebar.classList.add('no-transition');

        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            body.classList.add('sidebar-is-collapsed');
        }

        // Remove no-transition after initial render
        setTimeout(() => {
            sidebar.classList.remove('no-transition');
        }, 50);
    })();

    // =========================================================
    // MAIN SIDEBAR LOGIC
    // =========================================================
    document.addEventListener('DOMContentLoaded', function() {
        // === CONSTANTS ===
        const MOBILE_BREAKPOINT = 768;

        // === DOM ELEMENTS ===
        const body = document.body;
        const sidebar = document.getElementById('mainSidebar');
        const sidebarToggle = document.getElementById('sidebarInternalToggle');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const userProfileBtn = document.getElementById('userProfileToggle');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        const mainSidebarNav = document.getElementById('mainSidebarNav');
        const sidebarPopup = document.getElementById('sidebarPopup');
        const popupContent = document.getElementById('popupContent');
        const popupCloseBtn = document.getElementById('popupCloseBtn');
        const menuLinks = document.querySelectorAll('.sidebar-nav .menu-link');

        if (!sidebar) return;

        // === UTILITY FUNCTIONS ===

        function isMobile() {
            return window.innerWidth <= MOBILE_BREAKPOINT;
        }

        function updateBodyClass(isCollapsed) {
            body.classList.toggle('sidebar-is-collapsed', isCollapsed);
        }

        function updateToggleIcon() {
            if (!sidebarToggle || isMobile()) return;

            const isCollapsed = sidebar.classList.contains('collapsed');
            const icon = sidebarToggle.querySelector('i');

            if (icon) {
                icon.classList.toggle('bi-list', !isCollapsed);
                icon.classList.toggle('bi-arrow-bar-right', isCollapsed);
            }
        }

        function closeDropdown() {
            if (!userDropdownMenu) return;

            userDropdownMenu.classList.remove('show');
            if (userProfileBtn) {
                userProfileBtn.setAttribute('aria-expanded', 'false');
            }
        }

        function closePopup() {
            if (!sidebarPopup) return;

            sidebarPopup.classList.remove('show');
            sidebarPopup.classList.remove('show-from-footer');
        }

        function closeAllMenus() {
            closeDropdown();
            closePopup();
        }

        // === INITIALIZATION ===

        function initializeSidebar() {
            let isCollapsed = false;

            if (isMobile()) {
                sidebar.classList.remove('collapsed');
            } else {
                const savedState = localStorage.getItem('sidebarCollapsed');
                isCollapsed = savedState === 'true';
                sidebar.classList.toggle('collapsed', isCollapsed);
            }

            updateBodyClass(isCollapsed);
            updateToggleIcon();
            closeAllMenus();
        }

        // === EVENT HANDLERS ===

        // Toggle Sidebar
        function handleToggleClick() {
            if (!isMobile()) {
                sidebar.classList.toggle('collapsed');
                const isCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
                updateBodyClass(isCollapsed);
            }

            updateToggleIcon();
            closeAllMenus();
        }

        // User Profile Click
        function handleProfileClick(e) {
            e.stopPropagation();

            if (sidebar.classList.contains('collapsed')) {
                showPopupMenu();
            } else {
                toggleDropdown();
            }
        }

        function showPopupMenu() {
            if (!userDropdownMenu || !sidebarPopup || !userProfileBtn) return;

            // Kloning hanya menu dropdown pengguna
            const userMenuClone = userDropdownMenu.cloneNode(true);

            // 1. Kosongkan konten popup
            popupContent.innerHTML = '';

            // 2. Bersihkan kelas dan tampilkan menu di dalam popup
            // Menggunakan user-menu-popup-content agar stylingnya terisolasi (opsional)
            userMenuClone.classList.remove('user-dropdown-menu');
            userMenuClone.classList.add('show'); // Tambahkan kelas show untuk tampilan
            sidebarPopup.classList.add('show-from-footer');
            userMenuClone.style.position = 'static'; // Penting: Reset posisi absolut/relatif
            userMenuClone.style.top = 'auto';
            userMenuClone.style.bottom = 'auto';

            // 3. Masukkan konten yang sudah dikloning ke dalam popup
            popupContent.appendChild(userMenuClone);

            // 4. Tampilkan popup
            sidebarPopup.classList.add('show');

            // 5. Ubah judul popup
            const popupTitle = document.getElementById('popupTitle');
            if (popupTitle) {
                // Mengubah judul pop-up menjadi "Akun" atau "Profil"
                popupTitle.textContent = 'Akun Pengguna';
            }

            if (sidebarOverlay && !isMobile()) {
                sidebarOverlay.classList.add('show');
            }
        }

        function toggleDropdown() {
            if (!userDropdownMenu) return;

            const isShowing = userDropdownMenu.classList.toggle('show');
            if (userProfileBtn) {
                userProfileBtn.setAttribute('aria-expanded', isShowing);
            }
        }

        // Document Click (Close menus when clicking outside)
        function handleDocumentClick(e) {
            // Close dropdown in expanded mode
            if (userDropdownMenu && userProfileBtn &&
                !sidebar.classList.contains('collapsed') &&
                !userProfileBtn.contains(e.target) &&
                !userDropdownMenu.contains(e.target)) {
                closeDropdown();
            }

            // Close popup in collapsed mode
            if (sidebarPopup && sidebarPopup.classList.contains('show') &&
                !sidebar.contains(e.target) &&
                !sidebarPopup.contains(e.target) &&
                !(userProfileBtn && userProfileBtn.contains(e.target))) {
                closePopup();
            }
        }

        // Window Resize (Debounced)
        let resizeTimer;

        function handleResize() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                initializeSidebar();
            }, 250);
        }

        // Keyboard Handler (ESC key)
        function handleKeyDown(e) {
            if (e.key === 'Escape') {
                closeAllMenus();
            }
        }

        // Menu Link Click Handler (Close popup if in collapsed mode)
        function handleMenuLinkClick() {
            if (sidebar.classList.contains('collapsed') && sidebarPopup && sidebarPopup.classList.contains(
                    'show')) {
                closePopup();
            }
        }

        // === EVENT LISTENERS ===

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', handleToggleClick);
        }

        if (userProfileBtn) {
            userProfileBtn.addEventListener('click', handleProfileClick);
        }

        if (popupCloseBtn) {
            popupCloseBtn.addEventListener('click', closePopup);
        }

        // Add click handler to all menu links
        menuLinks.forEach(link => {
            link.addEventListener('click', handleMenuLinkClick);
        });

        document.addEventListener('click', handleDocumentClick);
        window.addEventListener('resize', handleResize);
        document.addEventListener('keydown', handleKeyDown);

        // === FINAL INITIALIZATION ===
        initializeSidebar();
    });
</script>
