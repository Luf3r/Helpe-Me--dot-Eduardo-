<?php

require_once __DIR__ . '/../../app/Config/config.php';
require_once __DIR__ . '/../../app/Models/BlogPost.php';
require_once __DIR__ . '/../../app/Helpers/functions.php';
require_once __DIR__ . '/../../app/Helpers/sanitize.php';

session_start();

// Redirect if not logged in
if (!isLoggedIn()) {
    redirect('/public/index.php');
}

$error = '';
$success = '';
$title = '';
$content = '';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $title = Sanitizer::sanitizeString($_POST['title']);
    $content = Sanitizer::sanitizeString($_POST['content']);
    $authorId = $_SESSION['user_id'];

    // Validate required fields
    if (empty($title) || empty($content)) {
        $error = 'Please fill in all required fields';
    } else {
        try {
            $blogPost = new BlogPost();
            if ($blogPost->createPost($title, $content, $authorId)) {
                redirect('/public/user/dashboard.php?success=post_created');
            } else {
                $error = 'Failed to create post. Please try again.';
            }
        } catch (Exception $e) {
            $error = 'An error occurred: ' . $e->getMessage();
        }
    }
}

// Include header and navigation
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../includes/navigation.php';
?>

<div class="container mt-5">
    <h2 class="mb-4">Create New Post</h2>
    
    <?php if ($error): ?>
    <div class="alert alert-danger"><?php echo Sanitizer::output($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <div class="mb-3">
            <label for="title" class="form-label">Post Title</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="<?php echo Sanitizer::output($title); ?>" required>
        </div>
        
        <div class="mb-3">
            <label for="content" class="form-label">Post Content</label>
            <textarea class="form-control" id="content" name="content" 
                      rows="6" required><?php echo Sanitizer::output($content); ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Create Post</button>
        <a href="/public/user/dashboard.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
// Include footer
include __DIR__ . '/../../includes/footer.php';
?>