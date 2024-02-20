<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    if (!empty($Username) && !empty($Password)) {
        // Using prepared statement to prevent SQL injection
        $query = "SELECT * FROM users WHERE Username=? AND Password=? LIMIT 1";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ss", $Username, $Password);
        mysqli_stmt_execute($stmt);

        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) > 0) {
            $user_data = mysqli_fetch_assoc($result);
            $_SESSION['FullName'] = $user_data['FullName']; // Store full name in session

            if ($user_data['IsManager']) {
                header("location: Admin.php"); // Redirect to admin page if user is a manager
            } else {
                header("location: index.php"); // Redirect to index page if user is not a manager
            }
            die;
        }
    }

    echo "<p style='color:red; font-size:20px; background:#fff;padding:15px; text-align:center;'>خطأ في اسم المستخدم أو كلمة المرور</p>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>تسجيل دخول - منصة الدعم الفني </title>
 <link rel="stylesheet" href="style.css">
 <style>
        .form-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .form-btn:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
  <div class="form-container">
        <form class="modal-content" action="" method="post">
            <h2>تسجيل الدخول</h2>
            <label><b>اسم المستخدم</b></label>
            <input type="text" placeholder="اسم المستخدم" name="Username" required dir="rtl">
            <br><br>
            <label for="Password"><b>كلمة المرور</b></label>
            <input type="password" placeholder="كلمة المرور" id="Password" name="Password" required dir="rtl">
            <button type="submit" class="form-btn">تسجيل الدخول</button>
            <p>لم تسجل معلوماتك من قبل؟ <a href="register.php">تسجيل</a></p>
        </form>
    </div>
</body>
</html>
