<?php

$error_fields = array();

 // Open the connection 
 $conn = mysqli_connect('localhost', 'root','','fundamentals');
 if(!$conn){
     echo mysqli_connect_error();
     exit;
 }

 // Select the user
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$select = "select * from `users` where `users`.`id`=" . $id . " limit 1";
$result = mysqli_query($conn, $select);
$row = mysqli_fetch_assoc($result);


if($_SERVER['REQUEST_METHOD']=='POST'){
    if(! (isset($_POST['name']) && !empty($_POST['name'])) )
    {
        $error_fields[] = 'name';
    }
    
    if(! (isset($_POST['email']) && filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)) )
    {
        $error_fields[] = 'email';
    }
    
    // if(! (isset($_POST['password']) && strlen($_POST['password'] > 5) ))
    // {
    //     $error_fields[] = 'password';
    // } 
    
    if(!$error_fields)
    {
        // Escape any special characters to avoid SQL Injection
        $name = mysqli_escape_string($conn, $_POST['name']);
        $email = mysqli_escape_string($conn, $_POST['email']);
        $password = (!empty($_POST['password'])) ?  sha1($_POST['password']) : $row['password'];
        $admin = (isset($_POST['admin'])) ? 1 : 0;


        // update the user
        $query = "update `users` set 
                    `name` = '".$name."', `email` = '".$email."', `password` = '".$password."', `is_admin` = '".$admin."' where `users`.`id` = ".$id;

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
        <title>Admin :: Edit User</title>
    </head>
    <body>
    <form method="post">
            <input type="hidden" name="id" id="id" value="<?= isset($row['id']) ? $row['id'] : '' ?>">
            <label for="name">Name</label>
            <input type="text" name="name" id="name" value=<?= isset($row['name']) ? $row['name']:'' ?> >
            <?php if (in_array("name", $error_fields))
                echo "* Please enter your name"; ?>
            <br/>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value=<?= isset($row['email']) ? $row['email']:'' ?> >
            <?php if (in_array("email", $error_fields))
                echo "* Please enter a valid email"; ?>
            <br/>
            <label for="password">Password</label>
            <input type="password" name="password"/>
            <?php if (in_array("password", $error_fields))
                echo "* Please enter a password not less than 6 characters"; ?>
            <br/>
            <input type="checkbox" name="admin" <?= isset($row['is_admin']) ? 'checked':'' ?>/>Admin
            <br/>
            <input type="submit" name="submit" value="Add User"/>
        </form>
    </body>
</html>
