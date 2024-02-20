<?php
session_start();
include("connect.php");

if (!isset($_SESSION['FullName'])) {
    header("Location: login.php");
    exit();
}

$FullName = $_SESSION['FullName'];

$query = "SELECT * FROM tasks WHERE Engineer = ? ORDER BY Date DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $FullName);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $query1 = "DELETE FROM tasks WHERE TaskNo = ? AND Engineer = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt1, "ss", $delete_id, $FullName);
    mysqli_stmt_execute($stmt1);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Your Page Title</title>
    <style>
        .today-task {
            background-color: #ADD8E6; /* Light red, you can adjust the color as needed */
        }

        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px; /* Add margin to the edges of the page */
            padding-top: 60px; /* Add padding-top to accommodate the fixed header */
        }

          header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: left;
            font-size: 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100vw; /* Set width to 100% of the viewport width */
            left: 0;
            top: 0;
            z-index: 1000;
    }

        header button {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            background-color: #C30000;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        button.new-ticket {
            padding: 12px 24px; /* Adjusted padding for a larger button */
            font-size: 18px; /* Increased font size */
            cursor: pointer;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            border: 2px solid #4CAF50;
            display: block; /* Ensures the button is a block element */
            margin: 20px auto; /* Center the button and add margin */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 80px; /* Adjust margin to ensure content below header */
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: teal;
            color: white;
        }
   </style>
</head>
<body>
  <header>
      <button type="button" onclick="logout()">تسجيل خروج</button>

  </header>
     <button type="button" class="new-ticket" onclick="newTicket()">إضافة طلب</button>

    <table class="table" id="ticketTable">
        <thead>
            <tr>
                <th>حذف</th>
                <th>تعديل</th>
                <th>التاريخ</th>
                <th>الفني</th>
                <th>رقم التحويلة</th>
                <th>رقم الطابق</th>
                <th>القسم</th>
                <th>صاحب الطلب</th>
                <th>نوع العطل</th>
                <th>الرقم التسلسلي</th>
            </tr>
        </thead>
        <tbody id="ticketTableBody">
            <?php
            $serialNumber = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $todayClass = (date('Y-m-d') === $row['Date']) ? 'today-task' : '';
                ?>
                <tr class="<?= $todayClass ?>">
                    <td><a href="index.php?delete=<?= $row['TaskNo'] ?>" class="delete"> حذف </a></td>
                    <td><a href="Edit_Task.php?edit=<?= $row['TaskNo'] ?>" class="edit"> تعديل </a></td>
                    <td><?= $row['Date'] ?></td>
                    <td><?= $row['Engineer'] ?></td>
                    <td><?= $row['Phone'] ?></td>
                    <td><?= $row['Floor'] ?></td>
                    <td><?= $row['Department'] ?></td>
                    <td><?= $row['User'] ?></td>
                    <td><?= $row['Title'] ?></td>
                    <td><?= $serialNumber ?></td>
                </tr>
                <?php
                $serialNumber++;
            }
            ?>
        </tbody>
    </table>

    <script>
        function logout() {
            window.location.href = "login.php";
        }

        function newTicket() {
            window.location.href = "Add_Task.php";
        }
    </script>
</body>
</html>
