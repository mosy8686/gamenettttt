<?php
// اتصال به دیتابیس
include '../db/connect.php';

// گرفتن id پرداخت از URL
$payment_id = intval($_GET['id']); // تبدیل به عدد صحیح برای امنیت

// گرفتن جزئیات پرداخت از دیتابیس
$query = "SELECT * FROM payments WHERE id = $payment_id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// بررسی وجود پرداخت
if (!$row) {
    echo "<p>پرداخت یافت نشد!</p>";
    exit;
}

// متغیرها
$amount = $row['amount']; // مبلغ پرداخت
$system_id = $row['system_id']; // id سیستم
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>پرداخت</title>

    <!-- استایل داخلی مخصوص این صفحه -->
    <style>
        body {
            background-color: #000000;
            /* پس‌زمینه سیاه */
            color: #ffa500;
            /* رنگ نارنجی */
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding-top: 50px;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        h1 {
            font-size: 36px;
            color: #ffa500;
            text-shadow: 4px 4px 8px #000000, 0 0 25px #ffa500;
            /* سایه جذاب */
        }

        .total-cost {
            font-size: 36px;
            color: #e67e22;
            /* نارنجی تیره */
            text-shadow: 2px 2px 5px #000000;
            /* سایه سیاه */
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            font-size: 18px;
            background-color: #ffa500;
            /* نارنجی */
            color: #000000;
            /* متن سیاه */
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s ease;
            box-shadow: 0 0 10px rgba(255, 165, 0, 0.6);
            /* سایه نارنجی */
        }

        .btn:hover {
            background-color: #e67e22;
            /* نارنجی تیره‌تر */
            box-shadow: 0 0 15px #ffa500;
            transform: scale(1.1);
            /* بزرگ شدن دکمه هنگام hover */
        }
    </style>
</head>

<body>

    <h1>پرداخت مبلغ</h1>

    <!-- نمایش مبلغ پرداختی -->
    <div class="total-cost">
        مبلغ قابل پرداخت: <?php echo number_format($amount); ?> تومان
    </div>

    <!-- فرم پرداخت -->
    <form method="post">
        <button class="btn" type="submit">پرداخت</button>
    </form>

    <?php
    // بررسی ارسال فرم
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // فرض کنیم پرداخت با موفقیت انجام می‌شود
        // ثبت زمان پرداخت در دیتابیس
        $payment_time = date('Y-m-d H:i:s');
        $update_query = "UPDATE payments SET status = 'paid', payment_time = '$payment_time' WHERE id = $payment_id";
        $update_result = mysqli_query($conn, $update_query);

        if ($update_result) {
            // موفقیت در پرداخت
            echo "<p>پرداخت با موفقیت انجام شد!</p>";
        } else {
            // خطا در پرداخت
            echo "<p>متاسفانه پرداخت با مشکل مواجه شد. لطفاً دوباره تلاش کنید.</p>";
        }
    }
    ?>

</body>

</html>