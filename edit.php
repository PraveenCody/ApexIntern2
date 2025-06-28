<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);
$post = $stmt->fetch();

if (!$post) {
    header("Location: index.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    if (empty($title) || empty($content)) {
        $error = "Please fill all fields";
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $content, $post['id'], $_SESSION['user_id']]);
            $pdo->commit();
            header("Location: index.php");
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = "Error updating post: " . $e->getMessage();
        }
    }
}

renderHeader('Edit Post');
?>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-body">
                <h2 class="card-title mb-4">Edit Post</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="post">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo htmlspecialchars($post['title']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" 
                                  rows="8" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="index.php" class="btn btn-outline-secondary me-md-2">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php renderFooter(); ?>