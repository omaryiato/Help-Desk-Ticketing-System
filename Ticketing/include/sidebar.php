<nav class="navbar navbar-expand-lg bg-primary text-light">
    <div class="container">
        <?php

        // $userSession = 'MAEN';
        // // Query to fetch users Information based on User Name
        $userInfo   = "SELECT USER_ID, USERNAME  FROM TICKETING.xxajmi_ticket_user_info WHERE USERNAME = '" . $_SESSION['USERNAME'] . "'";
        $info       = oci_parse($conn, $userInfo);
        oci_execute($info);
        $row        = oci_fetch_assoc($info);
        $UserSessionID = $row['USER_ID'];

        $permission = " SELECT ROLE_ID FROM TICKETING.TKT_REL_ROLE_USERS WHERE USER_ID =  " . $_SESSION['USER_ID'];
        $userPermission = oci_parse($conn, $permission);
        oci_execute($userPermission);
        $roles = oci_fetch_assoc($userPermission); // User Roles
        ?>
        <a class="navbar-brand text-light" href="home.php">e-Ticketing System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-light" id="navbarNavDropdown">
            <ul class="navbar-nav  d-flex w-100 justify-content-endtext-light">

                <li class="nav-item">
                    <a class="nav-link trans" href="#">Transactions<i class="fa-solid fa-caret-down ps-2"></i></a>
                    <ul class="tran">
                        <li><a href="TicketTransaction.php" aria-label="Go To The User Profile"><i class="fa-solid fa-ticket pe-2"></i>Ticketing Transactions</a></li>
                        <li><a href="##" id="CreateNewTicket" data-bs-toggle='modal' data-bs-target="#AddNewTicketPopup" data-bs-whatever="AddNewTicketPopup" aria-label="Logout From User Account"><i class="fa-solid fa-plus pe-2"></i>New Tickets</a></li>
                        <?php
                        if ($roles['ROLE_ID'] == 1 || $roles['ROLE_ID'] == 3) {
                        ?>
                            <li><a href="delegate.php" aria-label="Logout From User Account"><i class="fa-solid fa-user-minus pe-2"></i>Delegate Supervisors</a></li>
                        <?php
                        }
                        ?>
                        <?php
                        if ($roles['ROLE_ID'] == 1) {
                        ?>
                            <li><a href="teams.php" aria-label="Go To The User Orders"><i class="fa-solid fa-users pe-2"></i>Team Member</a></li>
                            <li><a href="service.php" aria-label="Logout From User Account"><i class="fa-solid fa-headphones pe-2"></i>Services</a></li>
                            <li><a href="##" aria-label="Confirm All Solved Ticket " id="UpdateAllSolveTicketToConfirm"><i class="fa-solid fa-circle-check pe-2"></i>Update Solved to Confirm</a></li>
                        <?php
                        }
                        ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle='modal' data-bs-target="#SearchTicket" data-bs-whatever="SearchTicket"><i class="fa-solid fa-magnifying-glass px-2"></i>Search</a>
                </li>
                <li class="nav-item ms-3 mt-2">
                    <div class='my-1  d-flex'>
                        <label class="pe-1" style="width: 125px;" for="refreshMode"> / Refresh Mode</label>
                        <select class="form-select w-50" id="refreshMode">
                            <option value="auto" selected>Auto</option>
                            <option value="manually">Manually</option>
                        </select>
                    </div>
                </li>
                <li class="nav-item ms-auto wel">
                    <span>Welcome: <?php echo $_SESSION['USERNAME'] ?></span></a>
                    <a href="logout.php"><span class="turnOff" id="turnoff"><i class="fa-solid fa-power-off ps-5"></i></span></a>

                </li>
            </ul>
        </div>
    </div>
</nav>