<?php
header("Content-Type: application/json; charset=UTF-8");
require "db.php";

$stmt = $conn->prepare("SELECT title, price, image FROM products");
$stmt->execute();

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    "status" => true,
    "products" => $products
], JSON_UNESCAPED_UNICODE);

?>
