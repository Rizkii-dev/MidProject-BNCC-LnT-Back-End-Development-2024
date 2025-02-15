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

// Fetch the current book data
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
$error = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $name = trim($_POST['name']);
  $author = trim($_POST['author']);
  $publisher = trim($_POST['publisher']);
  $number_of_page = trim($_POST['number_of_page']);
  
  if(empty($name) || empty($author) || empty($publisher) || empty($number_of_page)){
    $error = "All fields are required.";
  } else {
    // Preserve current photo if no new file is uploaded
    $photo_path = $book['photo'];
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
      $target_dir = "uploads/";
      if(!is_dir($target_dir)){
          mkdir($target_dir, 0777, true);
      }
      $target_file = $target_dir . basename($_FILES['photo']['name']);
      $check = getimagesize($_FILES['photo']['tmp_name']);
      if($check !== false){
        if(move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)){
          $photo_path = $target_file;
        } else {
          $error = "Error uploading file.";
        }
      } else {
        $error = "File is not an image.";
      }
    }
    if($error == ""){
      $sql = "UPDATE books SET name=?, author=?, publisher=?, number_of_page=?, photo=? WHERE id=? AND user_id=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssisii", $name, $author, $publisher, $number_of_page, $photo_path, $book_id, $user_id);
      if($stmt->execute()){
        header("Location: dashboard.php");
        exit();
      } else {
        $error = "Failed to update book.";
      }
    }
  }
}
?>
<?php include('header.php'); ?>
<h2>Edit Book</h2>
<?php if($error != ""): ?>
  <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post" action="" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Book Title</label>
    <input type="text" name="name" class="form-control" value="<?php echo $book['name']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Author</label>
    <input type="text" name="author" class="form-control" value="<?php echo $book['author']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Publisher</label>
    <input type="text" name="publisher" class="form-control" value="<?php echo $book['publisher']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Number of Page</label>
    <input type="number" name="number_of_page" class="form-control" value="<?php echo $book['number_of_page']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Book Photo (optional)</label>
    <input type="file" name="photo" class="form-control">
    <?php if(!empty($book['photo'])): ?>
      <img src="<?php echo $book['photo']; ?>" alt="Current Photo" width="100" class="mt-2">
    <?php endif; ?>
  </div>
  <button type="submit" class="btn btn-primary">Update Book</button>
  <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include('footer.php'); ?>
