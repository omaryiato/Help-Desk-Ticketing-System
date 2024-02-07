<?php

// This File To Include All Files You Have to Use It in Every File 

// Include The Important Files

// include 'connect.php';  // Connection Database File 

$inc = 'include/'; // Functions Directory
$css = 'layout/css/';      // Css Directory 
$js = 'layout/js/';       // Js Directory 

include 'function.php'; // All Function You Need In This Project
include $inc . 'header.php'; // Header File For This Project 
// include $inc . 'footer.php';

// IF This Variable Is Exist So Dont Include The SideBar In This Page (Like Login Page (index))

if (!isset($no_sidebar)) {
    include $inc . 'sidebar.php';
}
