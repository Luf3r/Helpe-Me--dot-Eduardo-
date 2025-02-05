<?php
// public/user/edit-post.php

require_once __DIR__ . '/../../app/Config/config.php';
require_once __DIR__ . '/../../app/Models/BlogPost.php';
require_once __DIR__ . '/../../app/Helpers/functions.php';
require_once __DIR__ . '/../../app/Helpers/sanitize.php';

session_start();

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('/public/auth/login.php');
}

$error = '';
$success = '';
$postData = [];

try {
    // Get post ID from URL
    if (!isset($_GET['id'])) {
        throw new Exception('Invalid post ID');
    }
    
    $postId = Sanitizer::sanitizeInt($_GET['id']);
    $userId = $_SESSION['user_id'];

    // Get post and verify ownership
    $blogPost = new BlogPost();
    $postData = $blogPost->getPostById($postId);

    if (!$postData || $postData['author_id'] != $userId) {
        $_SESSION['error'] = 'You are not authorized to edit this post';
        redirect('/public/user/dashboard.php');
    }

    // Process form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize inputs
        $title = Sanitizer::sanitizeString($_POST['title']);
        $content = Sanitizer::sanitizeString($_POST['content']);
        
        // Validate inputs
        if (empty($title) || empty($content)) {
            $error = 'Title and content are required';
        } else {
            // Update post
            if ($blogPost->updatePost($postId, $title, $content, $userId)) {
                $_SESSION['success'] = 'Post updated successfully';
                redirect('/public/user/dashboard.php');
            } else {
                $error = 'Failed to update post';
            }
        }
        
        // Keep form values on error
        $postData['title'] = $title;
        $postData['content'] = $content;
    }

} catch (PDOException $e) {
    error_log('Edit Post Error: ' . $e->getMessage());
    $error = 'A database error occurred';
} catch (Exception $e) {
    error_log('Edit Post Error: ' . $e->getMessage());
    $error = $e->getMessage();
}

// Include header and navigation
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navigation.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Edit Post</h2>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo Sanitizer::output($error); ?></div>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?php echo Sanitizer::output($_SESSION['error']); unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
        
        <div class="mb-3">
            <label for="title" class="form-label">Post Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="<?php echo Sanitizer::output($postData['title'] ?? ''); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="content" class="form-label">Post Content</label>
            <textarea class="form-control" id="content" name="content" 
                      rows="8" required><?php echo Sanitizer::output($postData['content'] ?? ''); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Update Post</button>
        <a href="/public/user/dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
// Include footer
include __DIR__ . '/../../includes/footer.php';
?>