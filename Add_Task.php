<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
  <meta charset="utf-8">
  <title>Cool Service Request Form</title>
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

    input[type="date"] {
      direction: ltr;
    }

    input[type="submit"] {
      background-color: #4CAF50;
      color: white;
      padding: 10px 15px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      text-decoration: none;
    }

    input[type="submit"]:hover {
      background-color: #45a049;
    }

    a.btn {
      color: #4CAF50;
      border: 2px solid #4CAF50;
      padding: 10px 15px;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      text-decoration: none;
      display: inline-block;
      margin-top: 10px;
    }

    a.btn:hover {
      color: #45a049;
      border-color: #45a049;
    }
  </style>
</head>
<body>
  <?php
    // Start session
    session_start();

    // Check if the user is logged in
    if(isset($_SESSION['FullName'])) {
      $logged_in_user = $_SESSION['FullName'];
    } else {
      // Redirect to login page if not logged in
      header('Location: login.php');
      exit();
    }
  ?>

  <form action="connect.php" method="post">
    <label for="Title">نوع العطل:</label>
    <input type="text" name="Title">

        <label for="User">صاحب الطلب:</label>
        <input type="text" name="User">

    <label for="Department">القسم:</label>
    <select name="Department">
      <option selected disabled hidden>الرجاء الاختيار</option>
      <option>الأمن</option>
      <option>الإعلام</option>
      <option>تقنية المعلومات</option>
      <option>المالية</option>
      <option>المعارض والمؤتمرات</option>
      <option>الموارد البشرية</option>
      <option>وكالة المشروعات</option>
      <option>أخرى</option>
    </select>

    <label for="Floor">رقم الطابق:</label>
    <input type="text" name="Floor">

    <label for="Phone">رقم التحويلة:</label>
    <input type="text" name="Phone">

    <!-- Hidden input field to store the default engineer -->
    <input type="hidden" name="Engineer" value="<?php echo $logged_in_user; ?>">

    <label for="Date">التاريخ:</label>
    <input type="date" name="Date">

    <input type="submit" value="إضافة الطلب">
    <a href="index.php" class="btn">العودة</a>
  </form>
</body>
</html>
