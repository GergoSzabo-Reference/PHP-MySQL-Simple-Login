<?php
session_start(); // elindítja a session kezelést, amely szükséges az adatcseréhez a felhasználói munkamenet során
if (isset($_SESSION["user"])) { // ha a felhasználó már be van jelentkezve (session-ben tárolt "user" kulccsal), átirányítja az index.php oldalra
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["login"])) { // ellenőrzi, hogy a felhasználó elküldte-e a bejelentkezési űrlapot
           $email = $_POST["email"];
           $password = $_POST["password"]; // email, pw beolvasás
            require_once "database.php"; // adatbázist kezelő php betöltése
            $sql = "SELECT * FROM users WHERE email = '$email'"; // megkeresi az adatbázisban az adott email címet.
            $result = mysqli_query($conn, $sql);
            $user = mysqli_fetch_array($result, MYSQLI_ASSOC); // tömbelemként visszaadja a felhasználói adatokat, ha talál egyezést
            if ($user) { // Ha talál egyezést az email alapján:
                if (password_verify($password, $user["password"])) { // Ellenőrzi, hogy a megadott jelszó megegyezik-e az adatbázisban tárolttal (jelszó titkosítva van).
                    session_start(); // session restart (biztonság kedvéért)
                    $_SESSION["user"] = "yes"; // felhasználó bejelentkezettként nyilvánítása
                    header("Location: index.php"); // átirányítás index.php-ra
                    die(); // megállítja a további futtatást
                }else{
                    echo "<div class='alert alert-danger'>Password does not match</div>"; // Hibaüzenetet jelenít meg, ha a jelszó helytelen.
                }
            }else{
                echo "<div class='alert alert-danger'>Email does not match</div>"; // Hibaüzenet, ha az email cím nem található az adatbázisban.
            }
        }
        ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <input type="email" placeholder="Enter Email:" name="email" class="form-control">
            </div>
            <div class="form-group">
                <input type="password" placeholder="Enter Password:" name="password" class="form-control">
            </div>
            <div class="form-btn">
                <input type="submit" value="Login" name="login" class="btn btn-primary">
            </div>
        </form>
        <div><p>Not registered yet <a href="registration.php">Register Here</a></p></div>
    </div>
</body>
</html>
