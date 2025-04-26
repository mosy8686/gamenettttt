<?php
include '../db/connect.php';

$system_id = intval($_GET['id']);
$query = "SELECT * FROM systems WHERE id = $system_id";
$result = mysqli_query($conn, $query);
$system = mysqli_fetch_assoc($result);

if (!$system) {
    die("سیستم پیدا نشد");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $minutes = intval($_POST['minutes']);
    if ($minutes > 0) {
        $start_time = date('Y-m-d H:i:s');
        $end_time = date('Y-m-d H:i:s', strtotime("+$minutes minutes"));
        $amount = $minutes * 60 * $system['cost_per_second'];

        $insert = "INSERT INTO payments (system_id, start_time, end_time, amount, status) 
                  VALUES ($system_id, '$start_time', '$end_time', $amount, 'active')";

        if (mysqli_query($conn, $insert)) {
            // ذخیره در localStorage و ریدایرکت
            echo "<script>
                localStorage.setItem('timeRemaining-" . $system_id . "', '" . ($minutes * 60) . "');
                localStorage.setItem('initialTime-" . $system_id . "', '" . ($minutes * 60) . "');
                window.location.href = '../index.php';
            </script>";
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>شروع کار سیستم</title>
    <style>
        body {
            background-color: #000000;
            color: #ffa500;
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding: 50px 20px;
        }

        .start-form {
            background-color: #222;
            padding: 30px;
            border-radius: 12px;
            max-width: 400px;
            margin: 0 auto;
            border: 2px solid #ffa500;
        }

        input[type="number"] {
            width: 200px;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ffa500;
            background-color: #333;
            color: #ffa500;
            border-radius: 6px;
            text-align: center;
        }

        button {
            background-color: #ffa500;
            color: #000;
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        button:hover {
            background-color: #e67e22;
            transform: scale(1.05);
        }
    </style>
</head>

<body>
    <h1>شروع کار سیستم <?php echo htmlspecialchars($system['name']); ?></h1>

    <div class="start-form">
        <form method="POST">
            <label for="minutes">مدت زمان (دقیقه)</label>
            <input type="number" id="minutes" name="minutes" min="1" required>
            <br>
            <button type="submit">شروع</button>
        </form>
    </div>
</body>

</html>