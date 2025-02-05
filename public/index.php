<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container">
        <div class="content-wrapper">
            <?php include '../includes/navigation.php'; ?>
            <div class="main-content">
                <div class="news-header">LATEST NEWS!</div>
                <div class="post">
                    <div class="post-title">First Blog Post</div>
                    <div class="post-content">
                        This is the content of my first blog post. It's a placeholder for now.
                    </div>
                </div>
                <div class="post">
                    <div class="post-title">Second Blog Post</div>
                    <div class="post-content">
                        Here's another blog post. In a real blog, this would contain actual content.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'; ?>
</body>
</html>