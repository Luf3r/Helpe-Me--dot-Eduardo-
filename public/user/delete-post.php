<?php
// public/user/delete-post.php

require_once __DIR__ . '/../../app/Config/config.php';
require_once __DIR__ . '/../../app/Models/BlogPost.php';
require_once __DIR__ . '/../../app/Helpers/functions.php';
require_once __DIR__ . '/../../app/Helpers/sanitize.php';

session_start();

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('/public/auth/login.php');
}

// Check if post ID exists
if (!isset($_GET['id'])) {
    $_SESSION['error'] = 'Invalid post ID';
    redirect('/public/user/dashboard.php');
}

// Sanitize and validate post ID
$postId = Sanitizer::sanitizeInt($_GET['id']);
$userId = $_SESSION['user_id'];

try {
    $blogPost = new BlogPost();
    
    // Verify post ownership before deletion
    $post = $blogPost->getPostById($postId);
    
    if (!$post || $post['author_id'] != $userId) {
        $_SESSION['error'] = 'You are not authorized to delete this post';
        redirect('/public/user/dashboard.php');
    }

    // Attempt deletion
    if ($blogPost->deletePost($postId, $userId)) {
        $_SESSION['success'] = 'Post deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete post';
    }
} catch (PDOException $e) {
    error_log('Delete Post Error: ' . $e->getMessage());
    $_SESSION['error'] = 'A database error occurred';
} catch (Exception $e) {
    error_log('Delete Post Error: ' . $e->getMessage());
    $_SESSION['error'] = 'An error occurred';
}

// Redirect back to dashboard
redirect('/public/user/dashboard.php');