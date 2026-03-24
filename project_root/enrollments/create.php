<?php
// enrollments/create.php

require_once __DIR__ . '/../classes/Database.php';
$db = Database::getInstance();

$errors = [];
$student_id = '';
$course_id  = '';

// Fetch data for dropdowns
$students = $db->fetchAll('SELECT id, name FROM students ORDER BY name ASC');
$courses  = $db->fetchAll('SELECT id, title FROM courses ORDER BY title ASC');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int)($_POST['student_id'] ?? 0);
    $course_id  = (int)($_POST['course_id'] ?? 0);

    if ($student_id <= 0) $errors['student'] = 'Please select a student.';
    if ($course_id <= 0)  $errors['course']  = 'Please select a course.';

    if (empty($errors)) {
        try {
            // Check for duplicate enrollment
            $existing = $db->fetch(
                'SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?', 
                [$student_id, $course_id]
            );

            if ($existing) {
                $errors['general'] = 'This student is already enrolled in this course.';
            } else {
                $db->insert('enrollments', [
                    'student_id' => $student_id,
                    'course_id'  => $course_id
                ]);
                header('Location: index.php?success=1');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'An error occurred. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Enrollment</title>
</head>
<body>
<h1>Enroll Student in Course</h1>

<?php if (!empty($errors['general'])): ?>
    <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post">
    <div>
        <label>Student:</label><br>
        <select name="student_id">
            <option value="0">-- Select Student --</option>
            <?php foreach ($students as $s): ?>
                <option value="<?= $s['id'] ?>" <?= $s['id'] == $student_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['student'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['student']) ?></span>
        <?php endif; ?>
    </div>
    <br>
    <div>
        <label>Course:</label><br>
        <select name="course_id">
            <option value="0">-- Select Course --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id'] == $course_id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['course'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['course']) ?></span>
        <?php endif; ?>
    </div>

    <br>
    <button type="submit">Enroll</button>
    <a href="index.php">Cancel</a>
</form>

</body>
</html>