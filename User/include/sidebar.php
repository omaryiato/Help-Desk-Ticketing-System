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
                    <a href="response.html" class="sidebar-link">
                        <i class="fa-solid fa-comment pe-2"></i>
                        Messages
                    </a>
                </li>

                <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed" data-bs-toggle="collapse" data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="fa-regular fa-user pe-2"></i>
                        Auth
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="?action=Info" class="sidebar-link"><i class="fa-solid fa-address-card pe-2"></i>Profile</a>
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
            <a href="logout.php" class="text-white"><i class="fa-solid fa-power-off pe-2"></i><span>Logout</span></a>
            <!-- Button for sidebar toggle -->
        </nav>