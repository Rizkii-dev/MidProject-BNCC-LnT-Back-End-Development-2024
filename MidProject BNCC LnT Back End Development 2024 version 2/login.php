<?php
include('config.php');
if(isset($_SESSION['user_id'])){
  header("Location: dashboard.php");
  exit();
}

$error = "";
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);
  
  if(empty($username) || empty($password)){
    $error = "Username and Password cannot be empty.";
  } else {
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows == 1){
      $row = $result->fetch_assoc();
      // Note: In production, always use password hashing.
      if($password == $row['password']){
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location: dashboard.php");
        exit();
      } else {
        $error = "Invalid username or password.";
      }
    } else {
      $error = "Invalid username or password.";
    }
  }
}
?>
<?php include('header.php'); ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Login</h2>
    <?php if($error != ""): ?>
      <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="post" action="">
      <div class="mb-3">
        <label>Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
  </div>
</div>
<?php include('footer.php'); ?>
