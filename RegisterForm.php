<?php
$errors_arr = array();
if(isset($_GET['error_fields']))
{
    $errors_arr = explode(",", $_GET['error_fields']);
}
?>
<html>
    <body>
        <form action="process_db.php" method="post">
            <label for="name">Name</label>
            <input type="text" name="name" id="name"/>
            <?php if (in_array("name", $errors_arr))
                echo "* Please enter your name"; ?>
            <br/>
            <label for="email">Email</label>
            <input type="email" name="email" id="email"/>
            <?php if (in_array("email", $errors_arr))
                echo "* Please enter a valid email"; ?>
            <br/>
            <label for="password">Password</label>
            <input type="password" name="password"/>
            <?php if (in_array("password", $errors_arr))
                echo "* Please enter a password not less than 6 characters"; ?>
            <br/>
            <label for="gender">Gender</label>
            <input type="radio" name="gender" value="male"/>Male
            <input type="radio" name="gender" value="female"/>Female
            <br/>
            <input type="submit" name="submit" value="Register"/>
        </form>
    </body>
</html>