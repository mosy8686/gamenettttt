<?php
include '../db/connect.php';

$system_id = intval($_GET['id']);
$query = "SELECT * FROM systems WHERE id = $system_id";
$result = mysqli_query($conn, $query);
$system = mysqli_fetch_assoc($result);

if (!$system) {
    die("سیستم یافت نشد");
}

// بررسی تایمر فعال
$timeKey = "timeRemaining-" . $system_id;
$initialTimeKey = "initialTime-" . $system_id;

?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>پایان کار سیستم</title>
    <style>
        /* تنظیمات کلی صفحه */
        body {
            background-color: #000000;
            color: #ffa500;
            font-family: Tahoma, sans-serif;
            text-align: center;
            padding: 50px 20px;
            margin: 0;
        }

        /* عنوان اصلی */
        h1 {
            color: #ffa500;
            margin-bottom: 30px;
            font-size: 28px;
            text-shadow: 0 0 10px rgba(255, 165, 0, 0.5);
        }

        h3 {
            color: #ffa500;
            margin: 20px 0;
            font-size: 22px;
        }

        /* باکس نتیجه */
        .result-box {
            background-color: #222;
            padding: 30px;
            border-radius: 12px;
            max-width: 500px;
            margin: 30px auto;
            border: 2px solid #ffa500;
            box-shadow: 0 0 20px rgba(255, 165, 0, 0.2);
        }

        /* متن هزینه */
        .total-cost {
            font-size: 24px;
            color: #ffa500;
            margin: 20px 0;
            font-weight: bold;
            text-shadow: 0 0 10px rgba(255, 165, 0, 0.3);
            padding: 15px;
            background-color: #333;
            border-radius: 8px;
            display: inline-block;
        }

        /* پاراگراف‌ها */
        p {
            margin: 15px 0;
            font-size: 18px;
            line-height: 1.6;
        }

        /* دکمه برگشت */
        .back-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #ffa500;
            color: #000000;
            padding: 12px 24px;
            border-radius: 12px;
            font-size: 16px;
            text-decoration: none;
            box-shadow: 0 0 15px rgba(255, 165, 0, 0.6);
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .back-btn:hover {
            background-color: #e67e22;
            color: #ffffff;
            box-shadow: 0 0 25px #ffa500, 0 0 25px #ff0000;
            transform: scale(1.1);
        }

        /* انیمیشن برای نمایش نتیجه */
        #finalResult {
            opacity: 0;
            animation: fadeIn 0.5s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* استایل برای اعداد */
        .numbers {
            font-family: 'Arial', sans-serif;
            direction: ltr;
            display: inline-block;
            color: #ffa500;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <h1>پایان کار سیستم <?php echo htmlspecialchars($system['name']); ?></h1>

    <div class="result-box">
        <div id="finalResult">
            <script>
                const timeRemaining = parseInt(localStorage.getItem('<?php echo $timeKey; ?>'));
                const initialTime = parseInt(localStorage.getItem('<?php echo $initialTimeKey; ?>'));

                if (initialTime) {
                    const usedTime = initialTime - (timeRemaining || 0);
                    const totalCost = <?php echo $system['cost_per_second']; ?> * usedTime;

                    document.write(`
                        <h3>خلاصه استفاده از سیستم</h3>
                        <p>مدت زمان استفاده شده: ${Math.floor(usedTime/60)} دقیقه و ${usedTime%60} ثانیه</p>
                        <p class="total-cost">مبلغ قابل پرداخت: ${totalCost.toLocaleString()} تومان</p>
                    `);

                    // ذخیره در دیتابیس
                    fetch('save_usage.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            system_id: <?php echo $system_id; ?>,
                            duration: usedTime,
                            amount: totalCost
                        })
                    });

                    // پاک کردن تایمر
                    localStorage.removeItem('<?php echo $timeKey; ?>');
                    localStorage.removeItem('<?php echo $initialTimeKey; ?>');
                } else {
                    document.write('<p>هیچ سیستم فعالی یافت نشد.</p>');
                }
            </script>
        </div>
    </div>

    <a href="../index.php" class="back-btn">برگشت به صفحه اصلی</a>
</body>

</html>