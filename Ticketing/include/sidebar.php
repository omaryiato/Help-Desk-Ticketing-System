<nav class="navbar navbar-expand-lg bg-primary text-light">
    <div class="container">
        <a class="navbar-brand text-light" href="dashboard.php">e-Ticketing System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-light" id="navbarNavDropdown">
            <ul class="navbar-nav  d-flex w-100 justify-content-endtext-light">

                <li class="nav-item">
                    <a class="nav-link trans" href="#">Transactions<i class="fa-solid fa-caret-down ps-2"></i></a>
                    <ul class="tran">
                        <li><a href="dashboard.php" aria-label="Go To The User Profile"><i class="fa-solid fa-ticket pe-2"></i>Ticketing Transactions</a></li>
                        <li><a href="teams.php" aria-label="Go To The User Orders"><i class="fa-solid fa-users pe-2"></i>Team Member</a></li>
                        <li><a href="?action=new" aria-label="Logout From User Account"><i class="fa-solid fa-plus pe-2"></i>New Tickets</a></li>
                        <li><a href="service.php" aria-label="Logout From User Account"><i class="fa-solid fa-headphones pe-2"></i>Services</a></li>
                        <li><a href="delegate.php" aria-label="Logout From User Account"><i class="fa-solid fa-user-minus pe-2"></i>Delegate Supervisors</a></li>
                        <li><a href="##" aria-label="Logout From User Account"><i class="fa-solid fa-circle-check pe-2"></i>Update Solved to Confirm</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                </li>
                <li class="nav-item ms-auto wel">
                    <a class="nav-link users " href="#"><i class="fa-solid fa-user pe-2"></i><span>Welcome: <?php echo $_SESSION['user'] ?></span><i class="fa-solid fa-caret-down ps-2"></i></a>
                    <ul class="userno">
                        <li><a href="?action=Profile&userid=<?php echo $row['ID'] ?>" aria-label="Go To The User Profile"><i class="fa-solid fa-user-pen pe-2"></i>My Account</a></li>
                        <li><a href="logout.php" aria-label="Logout From User Account"><i class="fa-regular fa-circle-xmark pe-2"></i>Sign Out</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>



<!-- <div class="wrapper"> wrapper Div Start  -->
<!-- Sidebar Start -->
<!-- <aside id="sidebar">
        <div class="h-100">
            <div class="sidebar-logo">
                <a href="#">Sup Admin Panel</a>
            </div> -->
<!-- Sidebar Navigation Start-->
<!-- <ul class="sidebar-nav">
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
            oci_bind_by_name($profile, ':t_name', $_SESSION['user']);
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

</ul> -->
<!-- Sidebar Navigation End-->
<!-- </div>
    </aside> -->
<!-- Sidebar End -->
<!-- Main Component Start-->
<!-- <div class="main">
        <nav class="navbar navbar-expand px-3 border-bottom d-flex justify-content-between"> -->
<!-- Button for sidebar toggle -->
<!-- <button class="btn d-flex" type="button" data-bs-theme="dark">
                <h5 class="text-white pe-2">Ticketing System</h5>
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="dashboard.php" class="text-white"><i class="fa-solid fa-user pe-2"></i><span>Welcome: <?php echo $_SESSION['user'] ?></span></a> -->
<!-- Button for sidebar toggle -->
<!-- </nav> -->