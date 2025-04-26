<?php
// اتصال به دیتابیس
include '../db/connect.php';

// بررسی ارسال فرم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت داده‌ها از فرم
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $cost_per_second = floatval($_POST['cost_per_second']);
    $last_service = mysqli_real_escape_string($conn, $_POST['last_service']);

    // وارد کردن داده‌ها به دیتابیس
    $query = "INSERT INTO systems (name, cost_per_second, last_service) 
              VALUES ('$name', '$cost_per_second', '$last_service')";

    if (mysqli_query($conn, $query)) {
        echo "<p>سیستم با موفقیت اضافه شد!</p>";
    } else {
        echo "<p>خطا در افزودن سیستم.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>افزودن سیستم</title>

    <style>
        /* استایل مخصوص */
        body {
            background-color: #000000;
            color: #ffa500;
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding-top: 50px;
        }

        input,
        button {
            padding: 12px;
            margin: 10px;
            font-size: 16px;
            border-radius: 8px;
            border: none;
        }

        input {
            width: 200px;
            background-color: #333333;
            color: #ffa500;
        }

        button {
            background-color: #ffa500;
            color: #000;
        }

        button:hover {
            background-color: #e67e22;
        }
    </style>
</head>

<body>

    <h1>افزودن سیستم جدید</h1>

    <form method="POST">
        <label for="name">نام سیستم:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="cost_per_second">هزینه هر ثانیه:</label><br>
        <input type="number" id="cost_per_second" name="cost_per_second" required><br><br>

        <label for="last_service">تاریخ آخرین سرویس:</label><br>
        <input type="date" id="last_service" name="last_service" required><br><br>

        <button type="submit">افزودن سیستم</button>
    </form>

</body>

</html>