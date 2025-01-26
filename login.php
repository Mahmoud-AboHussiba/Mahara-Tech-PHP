<?php
    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Open the connection
        $conn = mysqli_connect('localhost', 'root', '', 'fundamentals');
        if (!$conn) {
            echo mysqli_connect_error();
            exit;
        }
        // Escape any special characters to avoid SQL Injection
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = sha1($_POST['password']);
        // Select the user
        $select = "select * from `users` where `email` = '" . $email . "' and `password` = '" . $password . "' limit 1";
        $result = mysqli_query($conn, $select);
        $row = mysqli_fetch_assoc($result);
        if ($row) {
            $_SESSION['id'] = $row['id'];
            $_SESSION['email'] = $row['email'];
            header('Location: admin/users/list.php');
            exit;
        }
        else {
            $error = "Invalid email or password";
        }
        // Close the connection
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
    </head>
    <body>
        <?php if (isset($error)) echo $error; ?>
        <form  method="post">
            <label for="email">Email</label>
            <input type="text" name="email" id="email"/>
            <br/>
            <label for="password">Password</label>
            <input type="password" name="password" id="password"/>
            <br/>
            <input type="submit" name="submit" value="Login"/>
        </form>
    </body>
</html>