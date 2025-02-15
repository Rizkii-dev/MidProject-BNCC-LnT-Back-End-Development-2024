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
  } elseif(strlen($password) < 8){
    $error = "Password must be at least 8 characters.";
  } else {
    // Check if username exists
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows > 0){
      $error = "Username already exists. Please choose another.";
    } else {
      $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ss", $username, $password); // In production, hash the password
      if($stmt->execute()){
        $_SESSION['user_id'] = $stmt->insert_id;
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
      } else {
        $error = "Registration failed. Please try again.";
      }
    }
  }
}
?>
<?php include('header.php'); ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Register</h2>
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
      <button type="submit" class="btn btn-primary">Register</button>
    </form>
    <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
  </div>
</div>
<?php include('footer.php'); ?>
