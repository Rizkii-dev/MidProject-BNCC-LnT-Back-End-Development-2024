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
$sql = "SELECT * FROM books WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $book_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows != 1){
  echo "Book not found.";
  exit();
}
$book = $result->fetch_assoc();
?>
<?php include('header.php'); ?>
<h2>Book Details</h2>
<div class="card">
  <div class="card-body">
    <h5 class="card-title"><?php echo $book['name']; ?></h5>
    <p class="card-text"><strong>Author:</strong> <?php echo $book['author']; ?></p>
    <p class="card-text"><strong>Publisher:</strong> <?php echo $book['publisher']; ?></p>
    <p class="card-text"><strong>Number of Page:</strong> <?php echo $book['number_of_page']; ?></p>
    <?php if(isset($book['photo']) && !empty($book['photo'])): ?>
      <p><img src="<?php echo $book['photo']; ?>" alt="Book Photo" width="100"></p>
    <?php endif; ?>
    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
  </div>
</div>
<?php include('footer.php'); ?>
