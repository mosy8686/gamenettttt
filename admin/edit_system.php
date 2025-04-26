<?php
// اتصال به دیتابیس
include '../db/connect.php';

// گرفتن id سیستم از URL
$system_id = intval($_GET['id']); // تبدیل به عدد صحیح برای امنیت

// دریافت اطلاعات سیستم از دیتابیس
$query = "SELECT * FROM systems WHERE id = $system_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// بررسی وجود سیستم
if (!$row) {
    echo "<p>سیستم پیدا نشد!</p>";
    exit;
}

// بررسی ارسال فرم
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // دریافت داده‌ها از فرم
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $cost_per_second = floatval($_POST['cost_per_second']);
    $last_service = mysqli_real_escape_string($conn, $_POST['last_service']);

    // بروزرسانی داده‌ها در دیتابیس
    $update_query = "UPDATE systems SET name='$name', cost_per_second='$cost_per_second', last_service='$last_service' WHERE id=$system_id";

    if (mysqli_query($conn, $update_query)) {
        echo "<p>سیستم با موفقیت ویرایش شد!</p>";
    } else {
        echo "<p>خطا در ویرایش سیستم.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>ویرایش سیستم</title>

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
    <h1>ویرایش سیستم</h1>

    <form method="POST">
        <label for="name">نام سیستم:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br><br>

        <label for="cost_per_second">هزینه هر ثانیه:</label><br>
        <input type="number" id="cost_per_second" name="cost_per_second" value="<?php echo $row['cost_per_second']; ?>" required><br><br>

        <label for="last_service">تاریخ آخرین سرویس:</label><br>
        <input type="date" id="last_service" name="last_service" value="<?php echo $row['last_service']; ?>" required><br><br>

        <button type="submit">ویرایش سیستم</button>
    </form>

</body>

</html>