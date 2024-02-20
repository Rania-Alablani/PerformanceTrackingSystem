<?php
session_start();
include("connect.php");

if (!isset($_SESSION['FullName'])) {
    header("Location: login.php");
    exit();
}

$engineer = $_GET['engineer'];
$query = "SELECT * FROM tasks WHERE Engineer = ?";

$dateFrom = isset($_POST['date_from']) ? $_POST['date_from'] : '';
$dateTo = isset($_POST['date_to']) ? $_POST['date_to'] : '';

if (!empty($dateFrom) && !empty($dateTo)) {
    $query .= " AND Date BETWEEN ? AND ?";
} elseif (!empty($dateFrom)) {
    $query .= " AND Date >= ?";
} elseif (!empty($dateTo)) {
    $query .= " AND Date <= ?";
}

$query .= " ORDER BY Date DESC";

$stmt = mysqli_prepare($conn, $query);

if (!empty($dateFrom) && !empty($dateTo)) {
    mysqli_stmt_bind_param($stmt, "sss", $engineer, $dateFrom, $dateTo);
} elseif (!empty($dateFrom)) {
    mysqli_stmt_bind_param($stmt, "ss", $engineer, $dateFrom);
} elseif (!empty($dateTo)) {
    mysqli_stmt_bind_param($stmt, "ss", $engineer, $dateTo);
} else {
    mysqli_stmt_bind_param($stmt, "s", $engineer);
}

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $query1 = "DELETE FROM tasks WHERE TaskNo = ? AND Engineer = ?";
    $stmt1 = mysqli_prepare($conn, $query1);
    mysqli_stmt_bind_param($stmt1, "ss", $delete_id, $engineer); // Bind the parameter
    mysqli_stmt_execute($stmt1);
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Engineer Tasks</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 20px; 
            padding-top: 60px;
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
            width: 100vw;
            left: 0;
            top: 0;
            z-index: 1000;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
            border: 2px solid #4CAF50;
        }
        button.cancelButton {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: transparent;
            color: #4CAF50;
            border: 2px solid #4CAF50;
            border-radius: 4px;
        }
        header button {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 4px;
        }

        main {
                   max-width: 800px;
                   margin: 20px auto;
                   padding: 20px;
                   background-color: #fff;
                   border-radius: 8px;
                   box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
               }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 80px;
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
        form {
            margin-bottom: 20px;
        }

        form label {
            margin-right: 10px;
        }

        form select, form input[type="date"] {
            padding: 8px;
            font-size: 16px;
        }
    </style>
</head>
<body>
<header>
    <button onclick="goBack()">الرجوع إلى الصفحة الرئيسية</button>
</header>

<main>
    <form method="post">
        <label for="date_from">From:</label>
        <input type="date" id="date_from" name="date_from" value="<?php echo isset($_POST['date_from']) ? $_POST['date_from'] : ''; ?>">

        <label for="date_to">To:</label>
        <input type="date" id="date_to" name="date_to" value="<?php echo isset($_POST['date_to']) ? $_POST['date_to'] : ''; ?>">

        <button type="submit">تطبيق</button>
        <button type="button" onclick="resetFilters()" class="cancelButton">إعادة تعيين</button>
    </form>
<table class="table" id="ticketTable">
    <thead>
    <tr>
        <th>التاريخ</th>
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
            <td><?= $row['Date'] ?></td>
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
</main>
<script>
function goBack() {
   window.location.href = "admin.php";
}
    function resetFilters() {
       document.getElementById('date_from').value = '';
       document.getElementById('date_to').value = '';
   }
</script>
</body>
</html>
