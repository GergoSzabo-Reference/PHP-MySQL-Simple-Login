<?php
session_start(); // elindítja a session kezelést
if (isset($_SESSION["user"])) { // ha a felhasználó már be van jelentkezve, átirányítja az index.php oldalra
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) { // ellenőrzi, hogy az űrlapot benyújtották-e
           $fullName = $_POST["fullname"];
           $email = $_POST["email"];
           $password = $_POST["password"];
           $passwordRepeat = $_POST["repeat_password"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT); // jelszó titkosítása

           $errors = array(); // hibatömb inicializálása

           if (empty($fullName) OR empty($email) OR empty($password) OR empty($passwordRepeat)) {
            array_push($errors, "all fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "email is not valid");
           }
           if (strlen($password) < 8) {
            array_push($errors, "password must be at least 8 characters long");
           }
           if ($password !== $passwordRepeat) {
            array_push($errors, "password does not match"); // hiba, ha a két jelszó nem egyezik meg
           }

           require_once "database.php"; // adatbázis kapcsolat betöltése
           $sql = "SELECT * FROM users WHERE email = '$email'";
           $result = mysqli_query($conn, $sql); // lekérdezi, hogy az email már létezik-e az adatbázisban
           $rowCount = mysqli_num_rows($result);
           if ($rowCount > 0) {
            array_push($errors, "email already exists!");
           }

           if (count($errors) > 0) { // ha van hiba, megjeleníti azokat
            foreach ($errors as $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           } else {
            $sql = "INSERT INTO users (full_name, email, password) VALUES ( ?, ?, ? )";
            $stmt = mysqli_stmt_init($conn);
            $prepareStmt = mysqli_stmt_prepare($stmt, $sql);
            if ($prepareStmt) { // sikeres regisztráció üzenet
                mysqli_stmt_bind_param($stmt, "sss", $fullName, $email, $passwordHash); // adatokat köt a lekérdezéshez
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>you are registered successfully</div>";
            } else {
                die("something went wrong");
            }
           }
        }
        ?>
        <form action="registration.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Full Name:">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password:">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="repeat_password" placeholder="Repeat Password:">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
            <p>Already Registered <a href="login.php">Login Here</a></p>
        </div>
    </div>
</body>
</html>
