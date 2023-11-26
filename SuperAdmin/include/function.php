<?php 

/*
    ** Title Function That Echo The Page Title In Case The Page
    ** Has The Variable $pageTitle And Echo Default Title For Other Pages
*/

function getTitle()
{
    global $pageTitle;

    if (isset($pageTitle)) {
        echo $pageTitle;
    } else {
        echo 'Ticketing System';
    }
}

/*
    ** Redirect Function That Redirect to the prives page 
    ** Has The Parmeter $url that reference to the next page 
*/

function redirect($url) {
    header('Location:' .$url);
    exit();
}