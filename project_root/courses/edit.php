<?php
// courses/edit.php
// This page shows a form to edit an existing course and handles the update.

// Include Database class
require_once __DIR__ . '/../classes/Database.php';

// Get course ID from query string
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

// If the ID is invalid, redirect back to list
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$db     = Database::getInstance();
$errors = [];

// 1. Fetch existing course data
try {
    $course = $db->fetch('SELECT * FROM courses WHERE id = ?', [$id]);

    if (!$course) {
        // No course found with this ID
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    die('Cannot load course data.');
}

// Pre-fill form fields with current data
$title       = $course['title'];
$description = $course['description'];

// 2. If form submitted, validate and update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($title === '') {
        $errors['title'] = 'Title is required.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Title must be at least 3 characters long.';
    }

    if (empty($errors)) {
        try {
            // Update record in DB
            $db->update('courses', [
                'title'       => $title,
                'description' => $description,
            ], 'id = ?', [$id]);

            // Redirect back to list with updated flag
            header('Location: index.php?updated=1');
            exit;
            
        } catch (Exception $e) {
            $errors['general'] = 'An error occurred while updating. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
</head>
<body>
<h1>Edit Course</h1>

<?php if (!empty($errors['general'])): ?>
    <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post">
    <div>
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">
        <?php if (!empty($errors['title'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['title']) ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label>Description:</label><br>
        <textarea name="description" rows="4" cols="30"><?= htmlspecialchars($description) ?></textarea>
    </div>

    <br>
    <button type="submit">Update</button>
    <a href="index.php">Cancel</a>
</form>

</body>
</html>