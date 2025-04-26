<?php
// اتصال به دیتابیس
include '../db/connect.php';

// گرفتن تعداد سیستم‌ها
$systems_query = "SELECT COUNT(*) as total_systems FROM systems";
$systems_result = mysqli_query($conn, $systems_query);
$systems_data = mysqli_fetch_assoc($systems_result);

// گرفتن تعداد پرداخت‌های انجام شده
$payments_query = "SELECT COUNT(*) as total_payments, SUM(amount) as total_amount FROM payments WHERE status='paid'";
$payments_result = mysqli_query($conn, $payments_query);
$payments_data = mysqli_fetch_assoc($payments_result);
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>داشبورد مدیریت</title>

    <style>
        body {
            background-color: #000000;
            color: #ffa500;
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 36px;
            color: #ffa500;
            text-shadow: 4px 4px 8px #000000, 0 0 25px #ffa500;
            margin-bottom: 40px;
        }

        .dashboard {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .card {
            background-color: #1a1a1a;
            padding: 30px;
            border-radius: 15px;
            width: 250px;
            box-shadow: 0 0 20px #ffa500;
            transition: 0.3s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 30px #ffa500;
        }

        .card h2 {
            font-size: 24px;
            margin-bottom: 15px;
        }

        .card p {
            font-size: 20px;
        }

        a.btn {
            margin-top: 30px;
            display: inline-block;
            background-color: #ffa500;
            color: #000000;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.6);
            transition: 0.3s;
        }

        a.btn:hover {
            background-color: #e67e22;
            color: #ffffff;
            box-shadow: 0 0 20px #ffa500;
            transform: scale(1.1);
        }
    </style>
</head>

<body>

    <h1>داشبورد مدیریت گیم‌نت</h1>

    <div class="dashboard">
        <div class="card">
            <h2>تعداد سیستم‌ها</h2>
            <p><?php echo $systems_data['total_systems']; ?> سیستم</p>
        </div>

        <div class="card">
            <h2>تعداد پرداخت‌ها</h2>
            <p><?php echo $payments_data['total_payments']; ?> پرداخت</p>
        </div>

        <div class="card">
            <h2>مجموع درآمد</h2>
            <p><?php echo number_format($payments_data['total_amount']); ?> تومان</p>
        </div>
    </div>

    <a class="btn" href="systems.php">مدیریت سیستم‌ها</a>

</body>

</html>