<?php
// اتصال به دیتابیس
include '../db/connect.php';

// گرفتن id سیستم از URL
$system_id = intval($_GET['id']); // تبدیل به عدد صحیح برای امنیت

// حذف سیستم از دیتابیس
$query = "DELETE FROM systems WHERE id = $system_id";

if (mysqli_query($conn, $query)) {
    echo "<p>سیستم با موفقیت حذف شد!</p>";
} else {
    echo "<p>خطا در حذف سیستم.</p>";
}

echo "<p><a href='systems.php'>بازگشت به لیست سیستم‌ها</a></p>";
