<?php
// اتصال به دیتابیس
include '../db/connect.php';

// گرفتن همه سیستم‌ها از دیتابیس
$query = "SELECT * FROM systems";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>مدیریت سیستم‌ها</title>

    <!-- استایل داخلی مخصوص این صفحه -->
    <style>
        body {
            background-color: #000000;
            /* پس‌زمینه سیاه */
            color: #ffa500;
            /* رنگ نارنجی */
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 36px;
            color: #ffa500;
            text-shadow: 4px 4px 8px #000000, 0 0 25px #ffa500;
            margin-bottom: 20px;
        }

        table {
            margin: 20px auto;
            border-collapse: collapse;
            width: 90%;
            background-color: #1a1a1a;
            box-shadow: 0 0 20px #ffa500;
            border-radius: 12px;
        }

        th,
        td {
            border: 1px solid #ffa500;
            padding: 14px;
            font-size: 18px;
            color: #ffa500;
            text-shadow: 1px 1px 5px #000000;
        }

        th {
            background-color: #000000;
        }

        a.btn {
            background-color: #ffa500;
            color: #000000;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            margin: 5px;
            display: inline-block;
            font-weight: bold;
            transition: 0.3s;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.6);
        }

        a.btn:hover {
            background-color: #e67e22;
            color: #ffffff;
            box-shadow: 0 0 15px #ffa500;
            transform: scale(1.05);
        }

        .add-btn {
            margin: 20px;
            display: inline-block;
        }

        .back-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #ffa500;
            color: #000000;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 18px;
            text-decoration: none;
            box-shadow: 0 0 15px rgba(255, 165, 0, 0.6);
            transition: 0.3s ease;
            z-index: 1000;
        }

        .back-btn:hover {
            background-color: #e67e22;
            color: #ffffff;
            box-shadow: 0 0 25px #ffa500, 0 0 25px #ff0000;
            transform: scale(1.1);
        }
    </style>
</head>

<body>
    <a href="javascript:history.back()" class="back-btn">برگشت</a>
    <h1>مدیریت سیستم‌های گیم‌نت</h1>

    <!-- دکمه افزودن سیستم جدید -->
    <div class="add-btn">
        <a class="btn" href="add_system.php">افزودن سیستم جدید</a>
    </div>

    <?php
    // بررسی وجود سیستم‌ها
    if (mysqli_num_rows($result) > 0) {
        echo '<table>';
        echo '<tr><th>نام سیستم</th><th>تاریخ آخرین سرویس</th><th>هزینه هر ثانیه</th><th>عملیات</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . ($row['name']) . '</td>';
            echo '<td>' . ($row['last_service']) . '</td>';
            echo '<td>' . ($row['cost_per_second']) . ' تومان</td>';
            echo '<td>';
            echo '<a class="btn" href="edit_system.php?id=' . $row['id'] . '">ویرایش</a>';
            echo '<a class="btn" href="delete_system.php?id=' . $row['id'] . '" onclick="return confirm(\'آیا مطمئن هستید می‌خواهید حذف کنید؟\')">حذف</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        echo '<p>هیچ سیستمی ثبت نشده است.</p>';
    }
    ?>

</body>

</html>