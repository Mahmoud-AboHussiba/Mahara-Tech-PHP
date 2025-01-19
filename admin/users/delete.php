<?php 
// open connection 
$conn = mysqli_connect('localhost', 'root', '', 'fundamentals');
if(!$conn){
    echo mysqli_error($conn);
    exit;
}

// delete the user
$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
$query = "delete from `users` where `users`.`id` = " . $id . " limit  1";
if(mysqli_query($conn,$query)){
    header("Location: list.php");
    exit;
}else{
    echo mysqli_error($conn);
}

// Close the connection
mysqli_close($conn);
?>