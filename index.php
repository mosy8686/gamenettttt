<?php
// اتصال به دیتابیس
include 'db/connect.php';
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <title>مدیریت گیم‌نت</title>

    <!-- استایل داخلی مخصوص این صفحه -->
    <style>
        /* تنظیمات کلی صفحه */
        body {
            background-color: #000000;
            /* پس‌زمینه سیاه */
            color: #ffa500;
            /* رنگ نارنجی مشابه */
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding: 20px;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        /* عنوان صفحه */
        h1 {
            margin-bottom: 30px;
            font-size: 36px;
            color: #ffa500;
            /* رنگ نارنجی */
            text-shadow: 4px 4px 8px #000000, 0 0 25px #ffa500;
            /* سایه جذاب با دو رنگ */
            letter-spacing: 2px;
            /* افزایش فاصله بین حروف */
        }

        /* جدول اصلی */
        table {
            margin: 0 auto;
            border-collapse: collapse;
            width: 90%;
            background-color: #1a1a1a;
            /* خاکستری تیره */
            box-shadow: 0 0 20px #ffa500, 0 0 20px #ff0000;
            /* سایه نارنجی و قرمز */
            border-radius: 12px;
            overflow: hidden;
            transform: scale(1);
            transition: transform 0.3s ease;
        }

        table:hover {
            transform: scale(1.05);
            /* بزرگ شدن جدول هنگام hover */
        }

        /* ستون‌های جدول */
        th,
        td {
            border: 1px solid #ffa500;
            /* خطوط نارنجی */
            padding: 14px;
            font-size: 18px;
            color: #ffa500;
            /* رنگ متن نارنجی */
            text-shadow: 1px 1px 5px #000000;
            /* سایه سیاه برای متن */
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        th {
            background-color: #000000;
            /* پس‌زمینه سیاه برای هدر */
            text-shadow: 1px 1px 3px #000000;
            /* سایه سیاه برای هدر */
        }

        td a {
            background-color: #ffa500;
            /* دکمه‌ها با رنگ نارنجی */
            color: #000000;
            /* متن سیاه */
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            margin: 10px;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(255, 165, 0, 0.6);
            /* سایه نارنجی برای دکمه */
            transition: 0.3s ease;
        }

        td a:hover {
            background-color: #e67e22;
            /* تغییر رنگ دکمه به نارنجی تیره‌تر */
            color: #ffffff;
            /* متن سفید */
            box-shadow: 0 0 25px #ffa500, 0 0 25px #ff0000;
            /* سایه نارنجی و قرمز */
            transform: scale(1.1);
            /* بزرگ شدن دکمه هنگام hover */
        }

        .manage-btn {
            margin-top: 30px;
            display: inline-block;
            background-color: #ffa500;
            /* دکمه مدیریت نارنجی */
            color: #000000;
            /* متن سیاه */
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 18px;
            text-decoration: none;
            box-shadow: 0 0 15px rgba(255, 165, 0, 0.6);
            /* سایه نارنجی */
            transition: 0.3s ease;
        }

        .manage-btn:hover {
            background-color: #e67e22;
            /* تغییر رنگ دکمه نارنجی به تیره‌تر */
            color: #ffffff;
            /* متن سفید */
            box-shadow: 0 0 25px #ffa500, 0 0 25px #ff0000;
            /* سایه نارنجی و قرمز */
            transform: scale(1.1);
            /* بزرگ شدن دکمه هنگام hover */
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
    <h1>لیست سیستم‌های گیم‌نت</h1>


    <?php
    // گرفتن لیست سیستم‌ها از دیتابیس
    $query = "SELECT * FROM systems";
    $result = mysqli_query($conn, $query);

    // بررسی وجود سیستم
    if (mysqli_num_rows($result) > 0) {
        // اگر سیستم وجود داشت، جدول نمایش داده شود
        echo '<table>';

        // در بخش حلقه while که سیستم‌ها را نمایش می‌دهد، یک ستون جدید اضافه می‌کنیم
        echo '<tr><th>نام سیستم</th><th>تاریخ آخرین سرویس</th><th>هزینه هر ثانیه</th><th>زمان باقی‌مانده</th><th>عملیات</th></tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['last_service']) . '</td>';
            echo '<td>' . htmlspecialchars($row['cost_per_second']) . ' تومان</td>';
            // ستون جدید برای نمایش تایمر
            echo '<td><div id="timer-' . $row['id'] . '">-</div></td>';
            echo '<td>';
            echo '<a href="system/start.php?id=' . $row['id'] . '">شروع</a>';
            echo '<a href="system/stop.php?id=' . $row['id'] . '">پایان</a>';
            echo '<a href="system/details.php?id=' . $row['id'] . '">جزئیات</a>';
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';
    } else {
        // اگر هیچ سیستمی ثبت نشده بود
        echo '<p>هیچ سیستمی موجود نیست.</p>';
    }
    ?>

    <!-- دکمه رفتن به صفحه مدیریت سیستم‌ها -->
    <p>
        <a class="manage-btn" href="admin/systems.php">مدیریت سیستم‌ها</a>
    </p>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            function updateAllTimers() {
                const systems = document.querySelectorAll('[id^="timer-"]');
                systems.forEach(timerElement => {
                    const systemId = timerElement.id.split('-')[1];
                    const timeKey = `timeRemaining-${systemId}`;
                    let timeRemaining = parseInt(localStorage.getItem(timeKey));

                    if (!timeRemaining || timeRemaining <= 0) {
                        timerElement.innerHTML = "غیرفعال";
                        if (timeRemaining === 0) { // فقط یکبار نمایش داده شود
                            const systemName = timerElement.closest('tr').querySelector('td:first-child').textContent;
                            const costPerSecond = parseFloat(timerElement.closest('tr').querySelector('td:nth-child(3)').textContent);
                            const initialTime = parseInt(localStorage.getItem(`initialTime-${systemId}`));
                            const totalCost = costPerSecond * initialTime;

                            alert(`زمان سیستم ${systemName} به پایان رسید!\nمبلغ قابل پرداخت: ${totalCost.toLocaleString()} تومان`);
                            localStorage.removeItem(timeKey);
                            localStorage.removeItem(`initialTime-${systemId}`);
                        }
                    } else {
                        const minutes = Math.floor(timeRemaining / 60);
                        const seconds = timeRemaining % 60;
                        timerElement.innerHTML = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
                        timeRemaining--;
                        localStorage.setItem(timeKey, timeRemaining);
                    }
                });
            }

            setInterval(updateAllTimers, 1000);
            updateAllTimers();
        });
    </script>

    <div id="timer"></div>
    <div id="cost"></div>
</body>

</html>