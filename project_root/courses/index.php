<?php
// courses/index.php
// This page shows a table of all courses with links to create, edit, and delete.

// Include the Database class
require_once __DIR__ . '/../classes/Database.php';

// Get the shared Database instance
$db = Database::getInstance();

// Fetch all courses from the database, newest first
$courses = $db->fetchAll('SELECT * FROM courses ORDER BY created_at DESC');

// Read simple success messages from query string (optional UX)
$successMessage = '';
if (isset($_GET['success'])) {
    $successMessage = 'Course created successfully.';
} elseif (isset($_GET['updated'])) {
    $successMessage = 'Course updated successfully.';
} elseif (isset($_GET['deleted'])) {
    $successMessage = 'Course deleted successfully.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Management</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #4CAF50; color: #fff; }
        .btn { padding: 4px 8px; text-decoration: none; border-radius: 3px; }
        .btn-add { background: #4CAF50; color: #fff; }
        .btn-edit { background: #2196F3; color: #fff; }
        .btn-delete { background: #f44336; color: #fff; }
    </style>
</head>
<body>
<h1>Course Management</h1>

<?php if ($successMessage): ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<p>
    <a href="create.php" class="btn btn-add">+ Add Course</a>
</p>

<table>
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Created At</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= htmlspecialchars($course['title']) ?></td>
            <td><?= htmlspecialchars($course['description']) ?></td>
            <td><?= $course['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $course['id'] ?>" class="btn btn-edit">Edit</a>
                <a href="delete.php?id=<?= $course['id'] ?>" class="btn btn-delete"
                   onclick="return confirm('Are you sure you want to delete this course?');">
                    Delete
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>