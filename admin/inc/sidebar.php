<style>
    /* Sidebar Container */
    .sidebar {
        width: 230px;
        height: 200vh;
        position: fixed;
        background: #ffffff;
        padding: 25px 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.08);
        font-family: 'Poppins', sans-serif;
        transition: width 0.3s ease, padding 0.3s ease;
        z-index: 1000;
        overflow: hidden;
    }

    /* Collapsed Sidebar */
    .sidebar.collapsed {
        width: 90px;
        padding: 25px 10px;
    }

    .sidebar h2 {
        transition: opacity 0.3s ease;
    }

    .sidebar.collapsed h2 {
        opacity: 0;
        pointer-events: none;
        height: 40px;
        margin: 0;
    }

    /* Sidebar Links */
    .sidebar a {
        position: relative;
        display: flex;
        align-items: center;
        font-size: 16px;
        padding: 14px 14px;
        text-decoration: none;
        color: #333;
        font-weight: 500;
        border-radius: 10px;
        transition: background 0.25s ease, color 0.25s ease, padding 0.25s ease, transform 0.25s ease;
        white-space: nowrap;
        z-index: 1050;
        /* ensure it's above background */

    }

    /* Icons */
    .sidebar a i {
        width: 28px;
        margin-right: 15px;
        font-size: 20px;
        color: #555;
        transition: 0.25s ease;
    }

    .sidebar.collapsed a i {
        margin-right: 0 !important;
        width: 100%;
        font-size: 24px;
        text-align: center;
    }

    .sidebar.collapsed a span {
        opacity: 0;
        width: 0;
        pointer-events: none;
    }

    /* Hover */
    .sidebar a:hover {
        background: #f0f4ff;
        color: #2173ffff;
    }

    .sidebar a:hover i {
        color: #2173ffff;
    }

    /* ACTIVE (Normal pages) */
    .sidebar a.active:not(.toggle-btn) {
        background: #2173ffff;
        color: #fff;
    }

    .sidebar a.active i {
        color: white;
    }

    /* Prevent Settings parent from staying blue when submenu active */
    .sidebar a.has-submenu.active {
        background: none !important;
        color: #333 !important;
    }

    /* Collapsed active = circular */
    .sidebar.collapsed a.active {
        width: 58px !important;
        height: 58px !important;
        min-width: 58px;
        min-height: 58px;
        border-radius: 50%;
        margin: 15px auto !important;
        padding: 0 !important;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* Toggle Button */
    .toggle-btn {
        position: absolute;
        top: 15px;
        right: 10px;
        width: 40px;
        height: 40px;
        background: #ffffff;
        color: #5f5e5e;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 1100;
        border: 3px solid white;
    }

    .tooltip {
        position: absolute;
        left: 90px;
        background: #333;
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transform: translateY(-50%);
        top: 50%;
        transition: opacity 0.2s ease;
    }

    .sidebar.collapsed a:hover .tooltip {
        opacity: 1;
    }

    /* SUBMENU */
    .submenu {
        margin-left: 20px;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .submenu.open {
        max-height: 500px;
    }

    .submenu a {
        font-size: 14px;
        padding: 10px 14px !important;
    }

    .sidebar.collapsed .submenu {
        display: none !important;
    }

    .has-submenu i.arrow {
        margin-left: auto;
        transition: transform 0.3s ease;
    }

    .submenu-open .arrow {
        transform: rotate(90deg);
    }

    /* Logout Modal */
    .modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 999999;
    }

    .modal-box {
        width: 350px;
        background: #fff;
        padding: 25px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        animation: popupScale 0.2s ease;
    }

    .modal-actions {
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
    }

    .btn-cancel {
        background: #e0e0e0;
        padding: 8px 18px;
        border-radius: 6px;
    }

    .btn-logout {
        background: #e53935;
        color: white;
        padding: 8px 18px;
        border-radius: 6px;
        margin-top: auto;
        z-index: 1060;

    }

    .sidebar.collapsed a#logoutBtn i {
        pointer-events: auto;
    }



    @keyframes popupScale {
        from {
            transform: scale(0.8);
            opacity: 0;
        }

        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            left: -260px;
        }

        .sidebar.open {
            left: 0;
        }

        .toggle-btn {
            right: -45px !important;
        }
    }
</style>

<div class="sidebar" id="sidebar">

    <div class="toggle-btn" onclick="toggleSidebar()">
        <i class="fa-solid fa-bars"></i>
    </div>

    <h2>JANANI</h2>

    <?php
    $menu = [
        ["dashboard.php", "fa-chart-line", "Dashboard"],
        ["users.php", "fa-female", "Users"],
        ["doctors.php", "fa-user-doctor", "Doctors"],
        ["family.php", "fa-people-roof", "Family"],
        ["appointments.php", "fa-calendar-check", "Appointments"],
        ["tracker.php", "fa-heartbeat", "Tracker"],
    ];

    foreach ($menu as $item) {
        $active = basename($_SERVER["PHP_SELF"]) == $item[0] ? "active" : "";
        echo "
        <a href='{$item[0]}' class='$active'>
            <i class='fa-solid {$item[1]}'></i>
            <span>{$item[2]}</span>
            <div class='tooltip'>{$item[2]}</div>
        </a>";
    }

    // submenu pages
    $settingsPages = ["platform_config.php", "account_info.php", "change_password.php", "data_backup.php"];

    // ❗ Parent should NOT stay active
    $isSettingsActive = in_array(basename($_SERVER["PHP_SELF"]), $settingsPages) ? "submenu-open" : "";
    ?>

    <!-- SETTINGS PARENT -->
    <a href="#" class="has-submenu <?php echo $isSettingsActive; ?>" onclick="toggleSubmenu(event)">
        <i class="fa-solid fa-gear"></i>
        <span>Settings</span>
        <i class="fa-solid fa-angle-right arrow"></i>
        <div class="tooltip">Settings</div>
    </a>

    <div class="submenu <?php echo ($isSettingsActive ? 'open' : ''); ?>" id="settings-sub">

        <a href="platform_config.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'platform_config.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-sliders"></i>
            <span>Platform Configuration</span>
        </a>

        <a href="account_info.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'account_info.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-user"></i>
            <span>Account Information</span>
        </a>

        <a href="change_password.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'change_password.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-lock"></i>
            <span>Change Password</span>
        </a>

        <a href="data_backup.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'data_backup.php' ? 'active' : ''; ?>">
            <i class="fa-solid fa-database"></i>
            <span>Data & Backup</span>
        </a>

    </div>

    <!-- LOGOUT -->
    <a href="#" id="logoutBtn">
        <i class="fa-solid fa-right-from-bracket"></i>
        <span>Logout</span>
    </a>

</div>


<script>
    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        if (window.innerWidth <= 768) {
            sidebar.classList.toggle("open");
        } else {
            sidebar.classList.toggle("collapsed");
        }
    }

    function toggleSubmenu(event) {
        event.preventDefault();
        const btn = event.currentTarget;
        const submenu = document.getElementById("settings-sub");

        submenu.classList.toggle("open");
        btn.classList.toggle("submenu-open");
    }

    // LOGOUT SWEETALERT
    document.addEventListener("click", function(e) {
        if (e.target.closest("#logoutBtn")) {
            e.preventDefault();
            Swal.fire({
                title: "Are you sure?",
                text: "You will be logged out from your admin panel.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, Logout",
                cancelButtonText: "Cancel",
                backdrop: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../logout.php";
                }
            });
        }
    });
</script>