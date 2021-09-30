<?php
/*
* This file is to process the logout and remove authentication session.
*
*/
session_start();
session_destroy();
header('Location: login.php');
exit;
