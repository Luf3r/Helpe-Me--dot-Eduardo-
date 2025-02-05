<?php
// includes/auth/logout.php

require_once __DIR__ . '/../../app/Helpers/functions.php';

session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to home page
redirect('/public/index.php');