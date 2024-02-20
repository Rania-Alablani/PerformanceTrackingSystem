<?php
@include 'connect.php';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve form data
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $Username = mysqli_real_escape_string($conn, $_POST['Username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $userType = mysqli_real_escape_string($conn, $_POST['user_type']);
    $Gender = isset($_POST['Gender']) ? mysqli_real_escape_string($conn, $_POST['Gender']) : '';

    // Validate the form data (you may want to add more validation)
    $error = [];
    if (empty($name) || empty($Username) || empty($password) || empty($cpassword) || empty($userType) || empty($Gender)) {
        $error[] = "يرجى ملء جميع الحقول.";
    } else {
        // Password validation
        if ($password != $cpassword) {
            $error[] = '<span style="color: red;">كلمة المرور وتأكيد كلمة المرور غير متطابقين.</span>';
        }
    }

    // If no validation errors, proceed with registration
    if (empty($error)) {
        // Set isManager based on user_type
        $isManager = ($userType == 'engineer') ? 0 : 1; // Assuming 0 for 'فني' and 1 for 'مسؤول'

        // Insert user data into the database
        $query = "INSERT INTO users (FullName, Username, Password, Gender, isManager)
                  VALUES ('$name', '$Username', '$password', '$Gender', '$isManager')";

        if (mysqli_query($conn, $query)) {
            // Registration successful, you can redirect the user to a success page or login page
            header("Location: login.php");
            exit();
        } else {
            $error[] = "حدثت مشكلة أثناء التسجيل. يرجى المحاولة مرة أخرى.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>register form</title>

   <!-- custom css file link  -->
   <link rel="stylesheet" href="style.css">

</head>
<body>

<div class="form-container">

   <form action="" method="post">
      <h3>مستخدم جديد</h3>
      <?php
      if(isset($error)){
         foreach($error as $error){
            echo '<span class="error-msg">'.$error.'</span>';
         };
      };
      ?>
      <input type="text" name="name" required placeholder="الاسم كامل" dir="rtl">
      <input type="text" name="Username" required placeholder="اسم المستخدم" dir="rtl">
      <input type="password" name="password" required placeholder="كلمة المرور" dir="rtl">
      <input type="password" name="cpassword" required placeholder="تأكيد كلمة المرور" dir="rtl">
      <select name="user_type" dir="rtl">
         <option value="engineer">فني</option>
         <option value="manager">مسؤول</option>
      </select>
      <select name="Gender" dir="rtl">
         <option>القسم النسائي</option>
         <option>القسم الرجالي</option>
      </select>
      <input type="submit" name="submit" value="تسجيل" class="form-btn">
      <p> سجلت معلوماتك من قبل؟ <a href="login.php">تسجيل الدخول</a></p>
   </form>

</div>

</body>
</html>
