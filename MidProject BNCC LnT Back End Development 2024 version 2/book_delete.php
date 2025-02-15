<?php
include('config.php');
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit();
}
if(!isset($_GET['id'])){
  header("Location: dashboard.php");
  exit();
}
$book_id = $_GET['id'];
$user_id = $_SESSION['user_id'];
$sql = "DELETE FROM books WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $book_id, $user_id);
if($stmt->execute()){
  header("Location: dashboard.php");
  exit();
} else {
  echo "Error deleting book.";
}
?>
