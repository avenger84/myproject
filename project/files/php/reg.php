<?php
// تنظیمات اتصال به دیتابیس
$servername = "localhost";
$username = "root"; // نام کاربری دیتابیس (در XAMPP معمولاً root)
$password = ""; // رمز عبور دیتابیس (در XAMPP معمولاً خالی)
$dbname = "dokhaniat_abasi";

// ایجاد اتصال
$conn = new mysqli($servername, $username, $password, $dbname);

// بررسی اتصال
if ($conn->connect_error) {
    die("اتصال به دیتابیس ناموفق بود: " . $conn->connect_error);
}

// بررسی ارسال فرم
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // اعتبارسنجی ساده
    if (empty($name) || empty($email) || empty($password)) {
        echo "لطفاً تمام فیلدها را پر کنید.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "ایمیل نامعتبر است.";
    } else {
        // رمزنگاری رمز عبور
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // آماده‌سازی و اجرای کوئری با جلوگیری از SQL Injection
        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "ثبت‌نام با موفقیت انجام شد!";
        } else {
            echo "خطا در ثبت‌نام: " . $stmt->error;
        }

        $stmt->close();
    }
}

// بستن اتصال
$conn->close();
?>