<div class="wrapper"> <!-- wrapper Div Start  -->
    <!-- Sidebar Start -->
    <aside id="sidebar">
        <div class="h-100">
            <div class="sidebar-logo">
                <a href="#">User Panel</a>
            </div>
            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                <li class="sidebar-header">
                    <i class="fa-solid fa-gauge-high pe-2"></i>
                    Dashboard
                </li>
                <li class="sidebar-item">
                    <a href="ticket.php" class="sidebar-link">
                        <i class="fa-solid fa-ticket-simple pe-2"></i>
                        My Ticket
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
                        oci_bind_by_name($profile, ':t_name', $_SESSION['employee']);
                        oci_execute($profile);
                        $row = oci_fetch_array($profile)
                        ?>
                        <li class="sidebar-item">
                            <a href="?action=Info&userid=<?php echo $row['ID'] ?>" class="sidebar-link"><i class="fa-solid fa-address-card pe-2"></i>Profile</a>
                        </li>
                        <li class="sidebar-item">
                            <a href="logout.php" class="sidebar-link"><i class="fa-solid fa-power-off pe-2"></i>Logout</a>
                        </li>
                    </ul>
                </li>

            </ul>
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
            <a href="ticket.php" class="text-white"><i class="fa-solid fa-user pe-2"></i><span>Welcome: <?php echo $_SESSION['employee'] ?></span></a>
            <!-- Button for sidebar toggle -->
        </nav>