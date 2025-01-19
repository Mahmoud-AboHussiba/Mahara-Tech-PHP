<?php

$error_fields = array();
if($_SERVER['REQUEST_METHOD']=='POST'){
    if(! (isset($_POST['name']) && !empty($_POST['name'])) )
    {
        $error_fields[] = 'name';
    }
    
    if(! (isset($_POST['email']) && filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)) )
    {
        $error_fields[] = 'email';
    }
    
    if(! (isset($_POST['password']) && strlen($_POST['password'] > 5) ))
    {
        $error_fields[] = 'password';
    } 
    
    if(!$error_fields)
    {
         // Open the connection 
        $conn = mysqli_connect('localhost', 'root','','fundamentals');
        if(!$conn){
            echo mysqli_connect_error();
            exit;
        }

        // Escape any special characters to avoid SQL Injection
        $name = mysqli_escape_string($conn, $_POST['name']);
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = sha1($_POST['password']);
        $admin = (isset($_POST['admin'])) ? 1 : 0;


        // Insert the data
        $query = "insert into `users` 
        (`name`,`email`,`password`,`is_admin`) values
        ('" . $name . "','" . $email . "','" . $password ."','" . $admin . "')";

        if(mysqli_query($conn,$query)){
            header("Location: list.php");
            exit;
        }else{
            // echo $query;
            echo mysqli_error($conn);
        }

        // Close the connection
        mysqli_close($conn);
    }
   
    
}
?>

<html>
    <head>
        <title>Admin :: Add User</title>
    </head>
    <body>
    <form method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value=<?= isset($_POST['name']) ? $_POST['name']:'' ?> >
            <?php if (in_array("name", $error_fields))
                echo "* Please enter your name"; ?>
            <br/>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value=<?= isset($_POST['email']) ? $_POST['email']:'' ?> >
            <?php if (in_array("email", $error_fields))
                echo "* Please enter a valid email"; ?>
            <br/>
            <label for="password">Password</label>
            <input type="password" name="password"/>
            <?php if (in_array("password", $error_fields))
                echo "* Please enter a password not less than 6 characters"; ?>
            <br/>
            <input type="checkbox" name="admin" <?= isset($_POST['admin']) ? 'checked':'' ?>/>Admin
            <br/>
            <input type="submit" name="submit" value="Add User"/>
        </form>
    </body>
</html>
