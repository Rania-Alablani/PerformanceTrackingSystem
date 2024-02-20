<?php
include("connect.php");

$success_message = ''; 

if (isset($_POST['update_task'])) {
    $update_task_no = $_POST['update_task_no'];
    $update_task_title = $_POST['update_task_title'];
    $update_task_dep = $_POST['update_task_dep'];
    $update_task_floor = $_POST['update_task_floor'];
    $update_task_phone = $_POST['update_task_phone'];
    $update_task_User = $_POST['update_task_User'];

    $sql = "UPDATE tasks SET Title = '$update_task_title', Department = '$update_task_dep', Floor = '$update_task_floor', Phone = '$update_task_phone', User = '$update_task_User' WHERE TaskNo = '$update_task_no'";
    $update_query = mysqli_query($conn, $sql);

    if ($update_query) {
        // Update successful, set the success message
        $success_message = '<p style="color: green; font-size: 18px;">تم تحديث الطلب بنجاح!</p>';
    } else {
        // Update failed
        $success_message = '<p style="color: red; font-size: 18px;">حدثت مشكلة أثناء تحديث الطلب. يرجى المحاولة مرة أخرى.</p>';
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>تعديل الطلب</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            text-align: center;
            margin: 50px;
        }

        form {
            max-width: 400px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h3 {
            color: #4CAF50;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        select {
            direction: rtl;
        }

        input[type="number"] {
            direction: ltr;
        }

        input[type="submit"], a.btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
        }

        input[type="submit"]:hover, a.btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <?php echo $success_message; // Display the success message ?>

    <?php
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $edit_query = mysqli_query($conn, "select * from tasks where TaskNo = $edit_id");

        if (mysqli_num_rows($edit_query) > 0) {
            $fetch_edit = mysqli_fetch_assoc($edit_query);
        }
    }
    ?>
    <form class="form" action="" method="post" enctype="multipart/form-data">
        <h3>تعديل الطلب</h3>
        <input type="hidden" name="update_task_no" value="<?php echo $fetch_edit['TaskNo']; ?>">

        نوع العطل: <input type="text"  name="update_task_title" value="<?php echo $fetch_edit['Title']; ?>" required >

        <br>

        صاحب الطلب: <input type="text"  name="update_task_User" value="<?php echo $fetch_edit['User']; ?>"  >

        <br>

        <label>القسم:
            <select name="update_task_dep" dir="rtl">
                <option>الأمن</option>
                <option>الإعلام</option>
                <option>تقنية المعلومات</option>
                <option>المالية</option>
                <option>المعارض والمؤتمرات</option>
                <option>الموارد البشرية</option>
                <option>وكالة المشروعات</option>
                <option>أخرى</option>
            </select>
        </label>

        <br>

        رقم الطابق: <input type="number"  name="update_task_floor" value="<?php echo $fetch_edit['Floor']; ?>"  >

        <br>

        رقم التحويلة: <input type="text"  name="update_task_phone" value="<?php echo $fetch_edit['Phone']; ?>"  >

        <br>

        <input type="submit" value="حفظ التغيرات" name="update_task" >
        <a href="index.php" class="btn">العودة</a>
    </form>
</body>
</html>
