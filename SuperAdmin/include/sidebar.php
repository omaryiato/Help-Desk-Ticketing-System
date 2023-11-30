<div class="wrapper"> <!-- wrapper Div Start  -->
    <!-- Sidebar Start -->
    <aside id="sidebar">
        <div class="h-100">
            <div class="sidebar-logo">
                <a href="#">Sup Admin Panel</a>
            </div>
            <!-- Sidebar Navigation Start-->
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    <i class="fa-solid fa-gauge-high pe-2"></i>
                    Tools & Options
                </li>
                <li class="sidebar-item">
                    <a href="dashboard.php" class="sidebar-link">
                        <i class="fa-solid fa-gauge-high pe-2"></i>
                        Dashboard
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=Open" class="sidebar-link">
                        <i class="fa-solid fa-unlock pe-2"></i>
                        Open
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=Solved" class="sidebar-link">
                        <i class="fa-solid fa-circle-check pe-2"></i>
                        Solved
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=Rejected" class="sidebar-link">
                        <i class="fa-solid fa-circle-xmark pe-2"></i>
                        Rejected
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=Pending" class="sidebar-link">
                        <i class="fa-solid fa-circle-half-stroke pe-2"></i>
                        Pending
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=Assigned" class="sidebar-link">
                        <i class="fa-solid fa-at pe-2"></i>
                        Assigned
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=User" class="sidebar-link">
                        <i class="fa-solid fa-users pe-2"></i>
                        Users
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="?action=service" class="sidebar-link">
                        <i class="fa-solid fa-circle-info pe-2"></i>
                        Service Details
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="fa-regular fa-user pe-2"></i>
                        Auth
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <?php
                        $profileInfo = "SELECT ID FROM users WHERE NAME = :t_name ";
                        $profile = oci_parse($conn, $profileInfo);
                        oci_bind_by_name($profile, ':t_name', $_SESSION['leader']);
                        oci_execute($profile);
                        $row = oci_fetch_array($profile)
                        ?>
                        <li class="sidebar-item">
                            <a href="?action=Profile&userid=<?php echo $row['ID'] ?>" class="sidebar-link"><i class="fa-solid fa-address-card pe-2"></i>Profile</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="logout.php" class="sidebar-link"><i class="fa-solid fa-power-off pe-2"></i>Logout</a>
                        </li>

                    </ul>
                </li>

            </ul>
            <!-- Sidebar Navigation End-->
        </div>
    </aside>
    <!-- Sidebar End -->
    <!-- Main Component Start-->
    <div class="main">
        <nav class="navbar navbar-expand px-3 border-bottom d-flex justify-content-between">
            <!-- Button for sidebar toggle -->
            <button class="btn d-flex" type="button" data-bs-theme="dark">
                <h5 class="text-white pe-2">Ticketing System</h5>
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="dashboard.php" class="text-white"><i class="fa-solid fa-user pe-2"></i><span>Welcome: <?php echo $_SESSION['leader'] ?></span></a>
            <!-- Button for sidebar toggle -->
        </nav>