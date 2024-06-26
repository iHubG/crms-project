<?php
session_start();
$errorMessage = '';
$successMessage = '';

// Retrieve the username from the session
if(isset($_SESSION['first_name']) && isset($_SESSION['last_name'])) {
    $name = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_picture'])) {
    $studentId = $_POST['student_id'];

    // Handle file upload
    $uploadDirectory = __DIR__ . '/../uploads-students/'; // Adjust the path as necessary.
    $fileName = uniqid() . '_' . basename($_FILES["profile_picture"]["name"]);
    $targetFilePath = $uploadDirectory . $fileName;

    // Check if file is a valid image
    $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($imageFileType, $allowedExtensions)) {
        $errorMessage = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
    }

    // Check file size
    $maxFileSize = 25 * 1024 * 1024; // 25MB
    if ($_FILES['profile_picture']['size'] > $maxFileSize) {
        $errorMessage = 'File is too large. File must be less than 25 megabytes.';
    }

    // Move uploaded file to target directory
    if (empty($errorMessage) && move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
        // File uploaded successfully, now update the database with the file name
        // Assuming you have a database connection
        $sql = 'UPDATE student SET profile_picture_filename = ? WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$fileName, $studentId]);

        // Log the logout event
        $logData = "Student $name updated profile."; // Customize as needed

        // Assuming $pdo is your PDO object initialized previously
        $stmt = $pdo->prepare("INSERT INTO activity_logs (log_data) VALUES (?)");
        $stmt->execute([$logData]);

        $successMessage = 'Profile picture updated successfully.';
    } elseif (empty($errorMessage) && empty($successMessage)) {
        // Only set error message if no other error occurred and no success message is set
        //$errorMessage = 'Failed to upload profile picture.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Profile Picture</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .center {
            margin-top: 5rem; /* Add space from the top */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 d-flex flex-column">
                <?php if (!empty($successMessage)) : ?>
                    <div class="alert alert-success center text-center" role="alert">
                        <?php echo $successMessage; ?>
                    </div>
                    <!-- Redirect message -->
                    <div class="m-auto mt-5">
                        <p>You will be redirected to the dashboard in 3 seconds. If not, click <a href="/crms-project/student-dashboard">here</a>.</p>
                    </div>
                    <meta http-equiv="refresh" content="3;url=/crms-project/student-dashboard">
                <?php endif; ?>

               <!-- Display error message using JavaScript -->
                <script>
                    <?php if (!empty($errorMessage)) : ?>
                        let confirmRedirect = confirm("<?php echo $errorMessage; ?> Click OK to go to the dashboard.");
                        if (confirmRedirect) {
                            window.location.href = '/crms-project/student-dashboard'; // Redirect to the dashboard
                        }
                    <?php endif; ?>
                </script>
            </div>        
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
