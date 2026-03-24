<?php
// enrollments/index.php

require_once __DIR__ . '/../classes/Database.php';
$db = Database::getInstance();

// --- 1. Pagination Setup ---
$limit = 10;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- 2. Filter Setup ---
$course_id_filter = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;

// Base query parts
$whereClause = '';
$params = [];

if ($course_id_filter > 0) {
    $whereClause = 'WHERE e.course_id = ?';
    $params[] = $course_id_filter;
}

// --- 3. Get Total Records (for pagination math) ---
$countSql = "SELECT COUNT(*) as total FROM enrollments e $whereClause";
$totalResult = $db->fetch($countSql, $params);
$totalRecords = $totalResult['total'];
$totalPages = ceil($totalRecords / $limit);

// --- 4. Fetch Enrollments with JOINs ---
// We append LIMIT and OFFSET to our query. We add these directly to the string 
// because PDO sometimes struggles with binding integers to LIMIT clauses depending on emulation settings.
// Cập nhật câu SQL (thêm s.email)
$sql = "
    SELECT 
        e.id, 
        s.name AS student_name, 
        s.email,
        c.title AS course_title,
        e.enrolled_at
    FROM enrollments e
    JOIN students s ON e.student_id = s.id
    JOIN courses c ON e.course_id = c.id
    $whereClause
    ORDER BY e.enrolled_at DESC
    LIMIT $limit OFFSET $offset
";

$enrollments = $db->fetchAll($sql, $params);

// Fetch all courses for the filter dropdown
$allCourses = $db->fetchAll('SELECT id, title FROM courses ORDER BY title ASC');

$successMessage = '';
if (isset($_GET['success'])) $successMessage = 'Student enrolled successfully.';
if (isset($_GET['deleted'])) $successMessage = 'Enrollment removed.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Enrollments</title>
    <style>
        table { border-collapse: collapse; width: 100%; margin-top: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #4CAF50; color: #fff; }
        .btn { padding: 4px 8px; text-decoration: none; border-radius: 3px; display: inline-block;}
        .btn-add { background: #4CAF50; color: #fff; }
        .btn-delete { background: #f44336; color: #fff; }
        .pagination { margin-top: 15px; }
        .pagination a { padding: 5px 10px; border: 1px solid #ddd; text-decoration: none; margin-right: 5px;}
        .pagination a.active { background: #4CAF50; color: white; border-color: #4CAF50;}
    </style>
</head>
<body>
<h1>Enrollment Management</h1>

<?php if ($successMessage): ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<form method="get" style="margin-bottom: 15px; background: #f9f9f9; padding: 10px; border: 1px solid #ddd;">
    <label>Filter by Course:</label>
    <select name="course_id">
        <option value="0">-- All Courses --</option>
        <?php foreach ($allCourses as $c): ?>
            <option value="<?= $c['id'] ?>" <?= $c['id'] == $course_id_filter ? 'selected' : '' ?>>
                <?= htmlspecialchars($c['title']) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
    <a href="index.php">Clear</a>
</form>

<a href="create.php" class="btn btn-add">+ New Enrollment</a>

<table>
    <tr>
    <th>ID</th>
    <th>Student Name</th>
    <th>Email</th>
    <th>Course Title</th>
    <th>Enrolled At</th>
    <th>Actions</th>
</tr>
    <?php if (empty($enrollments)): ?>
        <tr><td colspan="5">No enrollments found.</td></tr>
    <?php else: ?>
        <?php foreach ($enrollments as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['student_name']) ?></td>
                <td><?= htmlspecialchars($e['email']) ?></td>
                <td><?= htmlspecialchars($e['course_title']) ?></td>
                <td><?= $e['enrolled_at'] ?></td>
                <td>
                    <a href="delete.php?id=<?= $e['id'] ?>" class="btn btn-delete"
                       onclick="return confirm('Remove this enrollment?');">Drop Course</a>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</table>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?><?= $course_id_filter > 0 ? '&course_id='.$course_id_filter : '' ?>" 
               class="<?= $i == $page ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>
<?php endif; ?>

</body>
</html>