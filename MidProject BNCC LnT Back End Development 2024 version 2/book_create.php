<?php
include('config.php');
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit();
}
$error = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $name = trim($_POST['name']);
  $author = trim($_POST['author']);
  $publisher = trim($_POST['publisher']);
  $number_of_page = trim($_POST['number_of_page']);
  
  if(empty($name) || empty($author) || empty($publisher) || empty($number_of_page)){
    $error = "All fields are required.";
  } else {
    // Handle optional file upload for book photo
    $photo_path = "";
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
      $user_id = $_SESSION['user_id'];
      $sql = "INSERT INTO books (name, author, publisher, number_of_page, user_id, photo) VALUES (?, ?, ?, ?, ?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("sssiis", $name, $author, $publisher, $number_of_page, $user_id, $photo_path);
      if($stmt->execute()){
        header("Location: dashboard.php");
        exit();
      } else {
        $error = "Failed to add book.";
      }
    }
  }
}
?>
<?php include('header.php'); ?>
<h2>Add New Book</h2>
<?php if($error != ""): ?>
  <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<form method="post" action="" enctype="multipart/form-data">
  <div class="mb-3">
    <label>Book Title</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Author</label>
    <input type="text" name="author" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Publisher</label>
    <input type="text" name="publisher" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Number of Page</label>
    <input type="number" name="number_of_page" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Book Photo (optional)</label>
    <input type="file" name="photo" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Add Book</button>
  <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
</form>
<?php include('footer.php'); ?>
