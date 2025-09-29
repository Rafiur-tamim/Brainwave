<?php
session_start();
$conn = new mysqli("localhost", "root", "", "brainwave");
if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['admin', 'tutor'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_test'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $unit = $_POST['unit'];
        $subject = $_POST['subject'];
        $difficulty = $_POST['difficulty'];
        $duration = (int)$_POST['duration'];
        $sql = "INSERT INTO tests (title, unit, subject, difficulty, duration, created_by) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssii", $title, $unit, $subject, $difficulty, $duration, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['add_question'])) {
        $test_id = (int)$_POST['test_id'];
        $question_text = $conn->real_escape_string($_POST['question_text']);
        $option_a = $conn->real_escape_string($_POST['option_a']);
        $option_b = $conn->real_escape_string($_POST['option_b']);
        $option_c = $conn->real_escape_string($_POST['option_c']);
        $option_d = $conn->real_escape_string($_POST['option_d']);
        $correct_answer = $_POST['correct_answer'];
        $explanation = $conn->real_escape_string($_POST['explanation']);
        $sql = "INSERT INTO test_questions (test_id, question_text, option_a, option_b, option_c, option_d, correct_answer, explanation) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isssssss", $test_id, $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $explanation);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit_material'])) {
        $material_id = (int)$_POST['material_id'];
        $title = $conn->real_escape_string($_POST['title']);
        $type = $_POST['type'];
        $unit = $_POST['unit'];
        $subject = $_POST['subject'];
        $difficulty = $_POST['difficulty'];
        $sql = "UPDATE study_materials SET title = ?, type = ?, unit = ?, subject = ?, difficulty = ? WHERE id = ? AND uploaded_by = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssii", $title, $type, $unit, $subject, $difficulty, $material_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    } elseif (isset($_POST['edit_question'])) {
        $question_id = (int)$_POST['question_id'];
        $test_id = (int)$_POST['test_id'];
        $question_text = $conn->real_escape_string($_POST['question_text']);
        $option_a = $conn->real_escape_string($_POST['option_a']);
        $option_b = $conn->real_escape_string($_POST['option_b']);
        $option_c = $conn->real_escape_string($_POST['option_c']);
        $option_d = $conn->real_escape_string($_POST['option_d']);
        $correct_answer = $_POST['correct_answer'];
        $explanation = $conn->real_escape_string($_POST['explanation']);
        $sql = "UPDATE test_questions SET question_text = ?, option_a = ?, option_b = ?, option_c = ?, option_d = ?, correct_answer = ?, explanation = ? WHERE id = ? AND test_id IN (SELECT id FROM tests WHERE created_by = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $question_text, $option_a, $option_b, $option_c, $option_d, $correct_answer, $explanation, $question_id, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();
    }
}

// Use prepared statements for fetching data
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Fetch tests
$sql = $role == 'tutor' ? "SELECT * FROM tests" : "SELECT * FROM tests WHERE created_by = ?";
$stmt = $conn->prepare($sql);
if ($role != 'tutor') {
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$tests = $stmt->get_result();
$stmt->close();

// Fetch study materials
$sql = $role == 'tutor' ? "SELECT * FROM study_materials" : "SELECT * FROM study_materials WHERE uploaded_by = ?";
$stmt = $conn->prepare($sql);
if ($role != 'tutor') {
    $stmt->bind_param("i", $user_id);
}
$stmt->execute();
$materials = $stmt->get_result();
$stmt->close();

// Fetch test questions
$sql = $role == 'tutor' ? 
    "SELECT q.*, t.title FROM test_questions q JOIN tests t ON q.test_id = t.id" : 
    "SELECT q.*, t.title FROM test_questions q JOIN tests t ON q.test_id = t.id WHERE t.created_by = ?";
$stmt = $conn->prepare($sql);
if ($role != 'tutor') {
    $stmt->bind_param("i", );
}
$stmt->execute();
$questions = $stmt->get_result();
$stmt->close();

// Fetch forum posts (admin only)
$posts = $role == 'tutor' ? $conn->query("SELECT p.*, u.username FROM forum_posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC") : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content - BrainWave</title>
    <link rel="icon" href="img/logo.png" type="image/png" >
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .body{
            background-image: url('img/background.svg');
            background-size: cover;
            background-position: center;
        }

    </style>
</head>
<body class="body">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="img/logo.png" alt="BrainWave Logo" class="logo"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container py-5">
        <h2>Manage Content</h2>
        <h3>Add Test</h3>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="test_title" class="form-label">Test Title</label>
                <input type="text" class="form-control" id="test_title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="test_unit" class="form-label">Unit</label>
                <select class="form-control" id="test_unit" name="unit" required>
                    <option value="Unit A">Unit A (Science)</option>
                    <option value="Unit B">Unit B (Science, Business Studies)</option>
                    <option value="Unit C">Unit C (Science, Business Studies, Arts)</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="test_subject" class="form-label">Subject</label>
                <select class="form-control" id="test_subject" name="subject" required>
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Biology">Biology</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Business Studies">Business Studies</option>
                    <option value="Accounting">Accounting</option>
                    <option value="Economics">Economics</option>
                    <option value="History">History</option>
                    <option value="Literature">Literature</option>
                    <option value="Sociology">Sociology</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="test_difficulty" class="form-label">Difficulty</label>
                <select class="form-control" id="test_difficulty" name="difficulty" required>
                    <option value="easy">Easy</option>
                    <option value="medium">Medium</option>
                    <option value="hard">Hard</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="duration" class="form-label">Duration (minutes)</label>
                <input type="number" class="form-control" id="duration" name="duration" required>
            </div>
            <button type="submit" name="add_test" class="btn btn-primary">Add Test</button>
        </form>
        <h3>Add Question to Test</h3>
        <form method="POST" class="mt-4">
            <div class="mb-3">
                <label for="test_id" class="form-label">Select Test</label>
                <select class="form-control" id="test_id" name="test_id" required>
                    <?php while ($test = $tests->fetch_assoc()): ?>
                        <option value="<?php echo $test['id']; ?>"><?php echo htmlspecialchars($test['title']); ?> (<?php echo $test['unit']; ?> - <?php echo $test['subject']; ?>)</option>
                    <?php endwhile; $tests->data_seek(0); ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="question_text" class="form-label">Question Text</label>
                <textarea class="form-control" id="question_text" name="question_text" required></textarea>
            </div>
            <div class="mb-3">
                <label for="option_a" class="form-label">Option A</label>
                <input type="text" class="form-control" id="option_a" name="option_a" required>
            </div>
            <div class="mb-3">
                <label for="option_b" class="form-label">Option B</label>
                <input type="text" class="form-control" id="option_b" name="option_b" required>
            </div>
            <div class="mb-3">
                <label for="option_c" class="form-label">Option C</label>
                <input type="text" class="form-control" id="option_c" name="option_c" required>
            </div>
            <div class="mb-3">
                <label for="option_d" class="form-label">Option D</label>
                <input type="text" class="form-control" id="option_d" name="option_d" required>
            </div>
            <div class="mb-3">
                <label for="correct_answer" class="form-label">Correct Answer</label>
                <select class="form-control" id="correct_answer" name="correct_answer" required>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="explanation" class="form-label">Explanation</label>
                <textarea class="form-control" id="explanation" name="explanation" required></textarea>
            </div>
            <button type="submit" name="add_question" class="btn btn-primary">Add Question</button>
        </form>
        <h3>Manage Study Materials</h3>
        <div class="row">
            <?php while ($material = $materials->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($material['title']); ?></h5>
                            <p class="card-text">
                                Unit: <?php echo htmlspecialchars($material['unit']); ?><br>
                                Subject: <?php echo htmlspecialchars($material['subject']); ?><br>
                                Type: <?php echo $material['type']; ?><br>
                                Difficulty: <?php echo $material['difficulty']; ?>
                            </p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editMaterialModal<?php echo $material['id']; ?>">Edit</button>
                            <a href="delete_material.php?id=<?php echo $material['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this material?');">Delete</a>
                        </div>
                    </div>
                    <!-- Edit Material Modal -->
                    <div class="modal fade" id="editMaterialModal<?php echo $material['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Material</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="material_id" value="<?php echo $material['id']; ?>">
                                        <div class="mb-3">
                                            <label for="title_<?php echo $material['id']; ?>" class="form-label">Title</label>
                                            <input type="text" class="form-control" id="title_<?php echo $material['id']; ?>" name="title" value="<?php echo htmlspecialchars($material['title']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="type_<?php echo $material['id']; ?>" class="form-label">Type</label>
                                            <select class="form-control" id="type_<?php echo $material['id']; ?>" name="type" required>
                                                <option value="ebook" <?php echo $material['type'] == 'ebook' ? 'selected' : ''; ?>>eBook (PDF)</option>
                                                <option value="video" <?php echo $material['type'] == 'video' ? 'selected' : ''; ?>>Video (MP4, AVI, MOV)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="unit_<?php echo $material['id']; ?>" class="form-label">Unit</label>
                                            <select class="form-control" id="unit_<?php echo $material['id']; ?>" name="unit" required>
                                                <option value="Unit A" <?php echo $material['unit'] == 'Unit A' ? 'selected' : ''; ?>>Unit A (Science)</option>
                                                <option value="Unit B" <?php echo $material['unit'] == 'Unit B' ? 'selected' : ''; ?>>Unit B (Science, Business Studies)</option>
                                                <option value="Unit C" <?php echo $material['unit'] == 'Unit C' ? 'selected' : ''; ?>>Unit C (Science, Business Studies, Arts)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="subject_<?php echo $material['id']; ?>" class="form-label">Subject</label>
                                            <select class="form-control" id="subject_<?php echo $material['id']; ?>" name="subject" required>
                                                <option value="Physics" <?php echo $material['subject'] == 'Physics' ? 'selected' : ''; ?>>Physics</option>
                                                <option value="Chemistry" <?php echo $material['subject'] == 'Chemistry' ? 'selected' : ''; ?>>Chemistry</option>
                                                <option value="Biology" <?php echo $material['subject'] == 'Biology' ? 'selected' : ''; ?>>Biology</option>
                                                <option value="Mathematics" <?php echo $material['subject'] == 'Mathematics' ? 'selected' : ''; ?>>Mathematics</option>
                                                <option value="Business Studies" <?php echo $material['subject'] == 'Business Studies' ? 'selected' : ''; ?>>Business Studies</option>
                                                <option value="Accounting" <?php echo $material['subject'] == 'Accounting' ? 'selected' : ''; ?>>Accounting</option>
                                                <option value="Economics" <?php echo $material['subject'] == 'Economics' ? 'selected' : ''; ?>>Economics</option>
                                                <option value="History" <?php echo $material['subject'] == 'History' ? 'selected' : ''; ?>>History</option>
                                                <option value="Literature" <?php echo $material['subject'] == 'Literature' ? 'selected' : ''; ?>>Literature</option>
                                                <option value="Sociology" <?php echo $material['subject'] == 'Sociology' ? 'selected' : ''; ?>>Sociology</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="difficulty_<?php echo $material['id']; ?>" class="form-label">Difficulty</label>
                                            <select class="form-control" id="difficulty_<?php echo $material['id']; ?>" name="difficulty" required>
                                                <option value="easy" <?php echo $material['difficulty'] == 'easy' ? 'selected' : ''; ?>>Easy</option>
                                                <option value="medium" <?php echo $material['difficulty'] == 'medium' ? 'selected' : ''; ?>>Medium</option>
                                                <option value="hard" <?php echo $material['difficulty'] == 'hard' ? 'selected' : ''; ?>>Hard</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit_material" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <h3>Manage Test Questions</h3>
        <div class="row">
            <?php while ($question = $questions->fetch_assoc()): ?>
                <div class="col-md-6 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($question['title']); ?> - Question</h5>
                            <p class="card-text"><?php echo htmlspecialchars($question['question_text']); ?></p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editQuestionModal<?php echo $question['id']; ?>">Edit</button>
                            <a href="delete_question.php?id=<?php echo $question['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this question?');">Delete</a>
                        </div>
                    </div>
                    <!-- Edit Question Modal -->
                    <div class="modal fade" id="editQuestionModal<?php echo $question['id']; ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Question</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <input type="hidden" name="question_id" value="<?php echo $question['id']; ?>">
                                        <input type="hidden" name="test_id" value="<?php echo $question['test_id']; ?>">
                                        <div class="mb-3">
                                            <label for="question_text_<?php echo $question['id']; ?>" class="form-label">Question Text</label>
                                            <textarea class="form-control" id="question_text_<?php echo $question['id']; ?>" name="question_text" required><?php echo htmlspecialchars($question['question_text']); ?></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <label for="option_a_<?php echo $question['id']; ?>" class="form-label">Option A</label>
                                            <input type="text" class="form-control" id="option_a_<?php echo $question['id']; ?>" name="option_a" value="<?php echo htmlspecialchars($question['option_a']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="option_b_<?php echo $question['id']; ?>" class="form-label">Option B</label>
                                            <input type="text" class="form-control" id="option_b_<?php echo $question['id']; ?>" name="option_b" value="<?php echo htmlspecialchars($question['option_b']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="option_c_<?php echo $question['id']; ?>" class="form-label">Option C</label>
                                            <input type="text" class="form-control" id="option_c_<?php echo $question['id']; ?>" name="option_c" value="<?php echo htmlspecialchars($question['option_c']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="option_d_<?php echo $question['id']; ?>" class="form-label">Option D</label>
                                            <input type="text" class="form-control" id="option_d_<?php echo $question['id']; ?>" name="option_d" value="<?php echo htmlspecialchars($question['option_d']); ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="correct_answer_<?php echo $question['id']; ?>" class="form-label">Correct Answer</label>
                                            <select class="form-control" id="correct_answer_<?php echo $question['id']; ?>" name="correct_answer" required>
                                                <option value="A" <?php echo $question['correct_answer'] == 'A' ? 'selected' : ''; ?>>A</option>
                                                <option value="B" <?php echo $question['correct_answer'] == 'B' ? 'selected' : ''; ?>>B</option>
                                                <option value="C" <?php echo $question['correct_answer'] == 'C' ? 'selected' : ''; ?>>C</option>
                                                <option value="D" <?php echo $question['correct_answer'] == 'D' ? 'selected' : ''; ?>>D</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="explanation_<?php echo $question['id']; ?>" class="form-label">Explanation</label>
                                            <textarea class="form-control" id="explanation_<?php echo $question['id']; ?>" name="explanation" required><?php echo htmlspecialchars($question['explanation']); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" name="edit_question" class="btn btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        <?php if ($_SESSION['role'] == 'admin'): ?>
            <h3>Manage Forum Posts</h3>
            <div class="row">
                <?php while ($post = $posts->fetch_assoc()): ?>
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($post['title']); ?></h5>
                                <p class="card-text">
                                    Unit: <?php echo htmlspecialchars($post['unit']); ?><br>
                                    Subject: <?php echo htmlspecialchars($post['subject']); ?><br>
                                    Posted by: <?php echo htmlspecialchars($post['username']); ?><br>
                                    Posted on: <?php echo $post['created_at']; ?>
                                </p>
                                <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">View Post</a>
                                <a href="delete_post.php?id=<?php echo $post['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this post?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>

    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <footer class="bg-dark text-white text-center py-3">
        <p>&copy; 2025 BrainWave. All rights reserved.</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>