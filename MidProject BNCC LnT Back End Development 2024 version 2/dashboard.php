<?php
include('config.php');
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
  exit();
}

// Fetch books for the logged-in user
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM books WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<?php include('header.php'); ?>
<h2>Dashboard</h2>
<a href="book_create.php" class="btn btn-success mb-3">Add New Book</a>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>ID Buku</th>
      <th>Foto</th>
      <th>Judul Buku</th>
      <th>Author</th>
      <th>Publisher</th>
      <th>Number of Page</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php while($row = $result->fetch_assoc()): ?>
    <tr>
      <td><?php echo $row['id']; ?></td>
      <td>
        <?php if(isset($row['photo']) && !empty($row['photo'])): ?>
          <img src="<?php echo $row['photo']; ?>" alt="Book Photo" width="50">
        <?php else: ?>
          No Photo
        <?php endif; ?>
      </td>
      <td><?php echo $row['name']; ?></td>
      <td><?php echo $row['author']; ?></td>
      <td><?php echo $row['publisher']; ?></td>
      <td><?php echo $row['number_of_page']; ?></td>
      <td>
        <a href="book_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View</a>
        <a href="book_update.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
        <a href="book_delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
<?php include('footer.php'); ?>
