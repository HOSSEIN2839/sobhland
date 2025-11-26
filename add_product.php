<?php
require "db.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("روش ارسال نامعتبر است");
}

$title       = $_POST['title'] ?? '';
$description = $_POST['description'] ?? '';
$price       = $_POST['price'] ?? '';
$category_id = $_POST['category_id'] ?? '';

if (empty($title) || empty($price) || empty($category_id)) {
    die("فیلدها را کامل پر کنید");
}

if (!isset($_FILES['image'])) {
    die("تصویر ارسال نشده است");
}

$allowed = ['jpg','jpeg','png','gif'];
$ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    die("فرمت تصویر مجاز نیست");
}

$imageName = time() . "_" . uniqid() . "." . $ext;
$target = "images/" . $imageName;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
    die("خطا در آپلود تصویر");
}

try {

    $stmt = $conn->prepare("
        INSERT INTO products (category_id, title, description, price, image)
        VALUES (:category_id, :title, :description, :price, :image)
    ");

    $stmt->execute([
        ':category_id' => $category_id,
        ':title'       => $title,
        ':description' => $description,
        ':price'       => $price,
        ':image'       => $imageName
    ]);

    // انتقال به لیست محصولات
    header("Location: /sobhland/products.html");
    exit;

} catch(PDOException $e) {
    echo "خطا در ثبت محصول: " . $e->getMessage();
}
?>
