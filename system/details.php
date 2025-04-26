<?php
// اتصال به فایل دیتابیس
include '../db/connect.php';

// دریافت شناسه سیستم از URL و تبدیل به عدد صحیح برای امنیت
$system_id = intval($_GET['id']);

// کوئری برای دریافت اطلاعات پایه سیستم (نام و هزینه هر ثانیه)
$query = "SELECT name, cost_per_second FROM systems WHERE id = $system_id";
$result = mysqli_query($conn, $query);
$system = mysqli_fetch_assoc($result);

// بررسی وجود سیستم در دیتابیس
if (!$system) {
    echo "<p>سیستم پیدا نشد!</p>";
    exit;
}

// کوئری برای دریافت تمام پرداخت‌های این سیستم به همراه محاسبه مدت زمان
$payments_query = "SELECT *, 
                    TIMESTAMPDIFF(SECOND, start_time, end_time) as duration 
                  FROM payments 
                  WHERE system_id = $system_id 
                  ORDER BY start_time DESC";
$payments_result = mysqli_query($conn, $payments_query);
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>جزئیات سیستم</title>
    <style>
        /* استایل کلی بدنه صفحه */
        body {
            background-color: #000000; /* پس زمینه مشکی */
            color: #ffa500;           /* رنگ متن نارنجی */
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding-top: 50px;
        }

        /* استایل باکس نتایج */
        .result-box {
            background-color: #222;   /* پس زمینه خاکستری تیره */
            padding: 20px;
            border-radius: 12px;
            margin: 20px auto;
            max-width: 800px;
            border: 2px solid #ffa500; /* حاشیه نارنجی */
        }

        /* استایل جدول */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        /* استایل سلول‌های جدول */
        th, td {
            padding: 12px;
            border: 1px solid #ffa500;
            text-align: center;
        }

        /* استایل سرستون‌ها */
        th {
            background-color: #333;
        }

        /* افکت هاور روی ردیف‌های جدول */
        tr:hover {
            background-color: #2a2a2a;
        }

        /* استایل دکمه برگشت */
        .back-btn {
            position: fixed;          /* ثابت در پایین صفحه */
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

        /* افکت هاور دکمه برگشت */
        .back-btn:hover {
            background-color: #e67e22;
            color: #ffffff;
            box-shadow: 0 0 25px #ffa500, 0 0 25px #ff0000;
            transform: scale(1.1);    /* بزرگنمایی دکمه */
        }
    </style>
</head>
<body>
    <!-- نمایش نام سیستم -->
    <h1>جزئیات سیستم <?php echo htmlspecialchars($system['name']); ?></h1>

    <div class="result-box">
        <h2>تاریخچه استفاده</h2>
        <?php if (mysqli_num_rows($payments_result) > 0): ?>
            <!-- جدول نمایش تاریخچه -->
            <table>
                <tr>
                    <th>تاریخ شروع</th>
                    <th>تاریخ پایان</th>
                    <th>مدت استفاده</th>
                    <th>مبلغ (تومان)</th>
                </tr>
                <?php
                // متغیرهای جمع کل
                $total_amount = 0;
                $total_duration = 0;

                // حلقه نمایش اطلاعات هر پرداخت
                while ($row = mysqli_fetch_assoc($payments_result)):
                    // تبدیل تاریخ‌ها به شیء DateTime
                    $start = new DateTime($row['start_time']);
                    $end = new DateTime($row['end_time']);
                    $duration = $row['duration'];
                    // محاسبه جمع کل
                    $total_amount += $row['amount'];
                    $total_duration += $duration;
                ?>
                    <tr>
                        <td><?php echo $start->format('Y/m/d H:i:s'); ?></td>
                        <td><?php echo $end->format('Y/m/d H:i:s'); ?></td>
                        <td><?php echo floor($duration/60) . ' دقیقه و ' . ($duration%60) . ' ثانیه'; ?></td>
                        <td><?php echo number_format($row['amount']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>

            <!-- نمایش خلاصه و جمع کل -->
            <div class="summary">
                <h3>جمع کل</h3>
                <p>کل زمان استفاده: <?php echo floor($total_duration/60) . ' دقیقه و ' . ($total_duration%60) . ' ثانیه'; ?></p>
                <p>مجموع مبلغ: <?php echo number_format($total_amount); ?> تومان</p>
            </div>
        <?php else: ?>
            <p>تاریخچه‌ای برای این سیستم ثبت نشده است.</p>
        <?php endif; ?>
    </div>

    <!-- دکمه برگشت به صفحه اصلی -->
    <a href="../index.php" class="back-btn">برگشت به صفحه اصلی</a>
</body>
</html>