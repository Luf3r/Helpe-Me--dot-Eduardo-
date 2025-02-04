<?php
require_once __DIR__.'/../app/Models/BlogPost.php';

$postId = $_GET['id'] ?? 0;
$postModel = new BlogPost();
$post = $postModel->getPostById($postId);

// Include HTML template showing the post
?>