<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Tasks Dashboard</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
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
        }

        header button {
            padding: 8px 16px;
            font-size: 16px;
            cursor: pointer;
            /* Updated button color to a comfortable darker red */
            background-color: #C30000;
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
            background-color: transparent; /* Set background to transparent */
            color: #4CAF50; /* Set text color to green */
            border: 2px solid #4CAF50; /* Set border to green */
            border-radius: 4px;
        }
        button.downloadButton {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            background-color: #808080; /* Gray background color */
            color: #fff;
            border: none;
            border-radius: 4px;
            border: 2px solid #808080; /* Border color matching the background */
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: teal;
            color: #fff;
        }

        canvas {
            margin-top: 20px;
            max-width: 100%;
            /* Added background color and border-radius to the chart */
            background-color: #f9f9f9;
            border-radius: 8px;
        }

        #chartTitle {
            text-align: center;
            font-size: 18px;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
  <?php
  session_start(); // Start session to access user data
  include("connect.php");

  // Check if the user is logged in
  if (!isset($_SESSION['FullName'])) {
      header("Location: login.php"); // Redirect to login page if not logged in
      exit();
  }

  $FullName = $_SESSION['FullName'];

  // Fetch gender options from the database
  $queryGenderOptions = "SELECT DISTINCT gender FROM users";
  $resultGenderOptions = mysqli_query($conn, $queryGenderOptions);

  // Check for query success
  if ($resultGenderOptions) {
      $genderOptions = [];
      while ($row = mysqli_fetch_assoc($resultGenderOptions)) {
          $genderOptions[] = $row;
      }
  } else {
      // Handle query error (you can modify this part based on your error handling preferences)
      echo "Error fetching gender options: " . mysqli_error($conn);
      exit();
  }

  // Build the query based on the filters
  $query = "SELECT Engineer, COUNT(*) AS TotalTasks FROM tasks WHERE 1=1";

  // Check if the form is submitted with date filters
  $dateFrom = isset($_POST['date_from']) ? $_POST['date_from'] : '';
  $dateTo = isset($_POST['date_to']) ? $_POST['date_to'] : '';
  if (!empty($dateFrom)) {
      $query .= " AND Date >= '$dateFrom'";
  }
  if (!empty($dateTo)) {
      $query .= " AND Date <= '$dateTo'";
  }

  // Check if the form is submitted with a gender filter
  $genderFilter = isset($_POST['gender']) ? $_POST['gender'] : '';
  if (!empty($genderFilter)) {
      $query .= " AND Engineer IN (SELECT FullName FROM users WHERE gender = '$genderFilter')";
  }

  $query .= " GROUP BY Engineer ORDER BY TotalTasks DESC";

  $result = mysqli_query($conn, $query);

  $engineerData = [];
  while ($row = mysqli_fetch_assoc($result)) {
      $engineerData[] = $row;
  }

  ?>


    <header>
        <button type="button" onclick="logout()">تسجيل خروج</button>
        متابعة الدعم الفني
    </header>

    <main>
        <form method="post">
            <label for="gender">Department:</label>
            <select id="gender" name="gender">
                <option value="">الكل</option>
                <?php foreach ($genderOptions as $option) : ?>
                    <option value="<?php echo $option['gender']; ?>" <?php echo ($genderFilter == $option['gender']) ? 'selected' : ''; ?>>
                        <?php echo $option['gender']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <br><br>
            <label for="date_from">From:</label>
            <input type="date" id="date_from" name="date_from" value="<?php echo $dateFrom; ?>">

            <label for="date_to">To:</label>
            <input type="date" id="date_to" name="date_to" value="<?php echo $dateTo; ?>">

            <button type="submit">تطبيق</button>
             <button type="button" onclick="cancelFilters()" class="cancelButton">إلغاء الفلتر</button>
        </form>
<br><br><br>
        <?php if (!empty($engineerData)) : ?>
            <table class="table" id="ticketTable">
                <thead>
                    <tr>
                        <th>عدد المهام</th>
                        <th>الفني</th>
                    </tr>
                </thead>
                <tbody id="ticketTableBody">
                    <?php
                    foreach ($engineerData as $row) {
                        ?>
                        <tr>
                            <td><?php echo $row['TotalTasks'] ?></td>
                            <!---  navigating to the engineer detailed task ----------->
                            <td><a href="detailedTask.php?engineer=<?php echo urlencode($row['Engineer']); ?>"><?php echo $row['Engineer'] ?></a></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        <?php else : ?>
            <p>No data available.</p>
        <?php endif; ?>

        <!-- Canvas for the bar chart with background and title -->
        <br><br><br><br>
        <div id="chartTitle"><b>ملخص المهام</b></div>
        <canvas id="barChart" width="400" height="200"></canvas>
    </main>

    <script>
        var ctx = document.getElementById('barChart').getContext('2d');
        var data = <?php echo json_encode($engineerData); ?>;

        var chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(item => item.Engineer),
                datasets: [{
                    label: 'عدد المهام',
                    data: data.map(item => item.TotalTasks),
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        stepSize: 1
                    }
                }
            }
        });

        function logout() {
            window.location.href = "login.php";
        }

        function cancelFilters() {
            document.getElementById('gender').value = ''; // Reset gender filter
            document.getElementById('date_from').value = ''; // Reset date_from input
            document.getElementById('date_to').value = '';   // Reset date_to input
        }
    </script>
</body>
</html>
