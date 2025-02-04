<?php
require_once __DIR__.'/../../app/Models/BlogPost.php';
require_once __DIR__.'/../../app/Helpers/functions.php';

session_start();
if(!isLoggedIn()) {
    redirect('/public/index.php');
}

$postModel = new BlogPost();
$userId = $_SESSION['user_id'];
$posts = $postModel->getPostsByAuthor($userId);

// Display posts in a table with edit/delete options
?>