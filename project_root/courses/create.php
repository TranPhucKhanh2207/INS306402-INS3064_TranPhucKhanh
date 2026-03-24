<?php
// courses/create.php
// This page shows a form to create a new course and handles the POST request.

// Include Database class
require_once __DIR__ . '/../classes/Database.php';

// Initialize variables for form fields and errors
$errors = [];
$title  = '';
$description = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Read form data, trim whitespace
    $title       = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    // 2. Validate data
    if ($title === '') {
        $errors['title'] = 'Title is required.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Title must be at least 3 characters long.';
    }

    // 3. If no validation errors, try to insert into database
    if (empty($errors)) {
        try {
            $db = Database::getInstance();

            // Insert new course record
            $db->insert('courses', [
                'title'       => $title,
                'description' => $description,
            ]);

            // Redirect back to the list with a success flag
            header('Location: index.php?success=1');
            exit;
            
        } catch (Exception $e) {
            // Generic error message displayed to the user
            $errors['general'] = 'An error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Course</title>
</head>
<body>
<h1>Add New Course</h1>

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
    <button type="submit">Save</button>
    <a href="index.php">Cancel</a>
</form>

</body>
</html>