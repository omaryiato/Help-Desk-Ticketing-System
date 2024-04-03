<nav class="navbar navbar-expand-lg bg-primary text-light">
    <div class="container">
        <a class="navbar-brand text-light" href="##">e-Ticketing System</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse text-light" id="navbarNavDropdown">
            <ul class="navbar-nav  d-flex w-100 justify-content-endtext-light">

                <li class="nav-item">
                    <a class="nav-link trans" href="#">Transactions<i class="fa-solid fa-caret-down ps-2"></i></a>
                    <ul class="tran" id="NavbarItems">

                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle='modal' data-bs-target="#SearchTicket" data-bs-whatever="SearchTicket"><i class="fa-solid fa-magnifying-glass px-2"></i>Search</a>
                </li>
                <li class="nav-item ms-3 mt-1">
                    <div class='d-flex'>
                        <label class="pe-1 mt-1" style="width: 125px;" for="refreshMode"> / Refresh Mode</label>
                        <select class="form-select w-50 " id="refreshMode">
                            <option value="auto" selected>Auto</option>
                            <option value="manually">Manually</option>
                        </select>
                    </div>
                </li>
                <li class="nav-item ms-auto wel mt-1">
                    <div class="d-flex ">
                        <span class="me-1 mt-1">Welcome: </span>
                        <span> <?php if ($_SESSION['NoAccount'] == 1) { ?>
                                <select class="form-select " id="UserAccount">
                                    <option value="<?php echo $_SESSION['USER_ID'] ?>" selected><?php echo $_SESSION['USERNAME']; ?></option>";
                                </select>
                            <?php
                                } else {
                                    // // Query to fetch users Information based on User Name
                                    $userInfo   = "SELECT USER_ID,USERNAME  FROM TICKETING.xxajmi_ticket_user_info WHERE EBS_EMPLOYEE_ID = '" . $_SESSION['EmpNo'] . "'";
                                    $info       = oci_parse($conn, $userInfo);
                                    oci_execute($info);
                            ?>
                                <select class="form-select " id="UserAccount">
                                    <?php
                                    while ($row = oci_fetch_assoc($info)) {
                                        echo  "<option value=\"" . $row["USER_ID"] . "\">" . $row["USERNAME"] . "</option>";
                                    }
                                    ?>
                                </select></span>
                    <?php
                                } ?>
                    <a href="logout.php" style="color: white; font-size: 20px;" class="mt-1 ps-2"><span class="turnOff" id="turnoff"><i class="fa-solid fa-power-off ps-2"></i></span></a>

                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>