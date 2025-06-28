<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$posts = $stmt->fetchAll();

renderHeader('My Posts');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>My Posts</h1>
    <a href="create.php" class="btn btn-success">
        <i class="bi bi-plus-lg"></i> New Post
    </a>
</div>

<?php if (empty($posts)): ?>
    <div class="alert alert-info">No posts found. Create your first post!</div>
<?php else: ?>
    <div class="row">
        <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow">
                    <div class="card-body">
                        <h3 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h3>
                        <div class="card-text post-content mb-3">
                            <?php echo nl2br(htmlspecialchars($post['content'])); ?>
                        </div>
                        <small class="text-muted">
                            Posted on: <?php echo date('M j, Y g:i a', strtotime($post['created_at'])); ?>
                        </small>
                    </div>
                    <div class="card-footer bg-transparent">
                        <div class="d-flex justify-content-between">
                            <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $post['id']; ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Are you sure you want to delete this post?')">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php renderFooter(); ?>