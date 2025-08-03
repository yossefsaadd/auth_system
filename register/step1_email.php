<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <link rel="stylesheet" href="../css/register.css">
  <title>Zamzor Register</title>
</head>
<body>
  <div class="con">
    <h1>Zamzor</h1>
    <div class="form-box" id="form-register">
      <form action="./register.php" method="POST">
        <h2>Register</h2>
         <input type="text" name="name" placeholder="Your Name" required />
          <input type="email" name="email" placeholder="Email Address" required />
          <button type="submit">Send Verification Code</button>
 
      </form>
    </div>
  </div>
</body>

