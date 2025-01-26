<?php
require '../../models/User.php';

session_start();
if(isset($_SESSION['id'])){
    echo '<p>Welcome '.$_SESSION['email'].' <a href="../../logout.php">Logout</a></p>';
}else{
    header("Location: ../../login.php"); // redirect to login page which is not in the same folder as the current file
    exit;
}

// Open the connection 
//$conn = mysqli_connect('localhost', 'root','','fundamentals');
//if(!$conn){
//    echo mysqli_connect_error();
//    exit;
//}

// Do the operation
//$query = "select * from `users`";

$user = new User();
$users = $user->getUsers();
echo "suers: ". $users;
// Search by the name or the email
if(isset($_GET['search'])){
//    $search = mysqli_escape_string($conn, $_GET['search']);
//    $query .= " where `users`.`name` like '%" . $search . "%' or `users`.`email` like '%" . $search . "%'";
    $users = $user->searchUsers($_GET['search']);
}

//$result = mysqli_query($conn,$query);
?>

<html>
    <head>
        <title>Admin :: List Users</title>
    </head>
    <body>
        <h1>List Users</h1>
        <form method="GET">
            <input type="text" name="search" placeholder="Enter {Name} or {Eami} to search"
              value=<?= isset($_GET['search']) ? $_GET['search']:''?> >
            <input type="submit" value="search">
        </form>
        <table>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Avatar</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Admin</th>
                    <th>Actions</th>  
                </tr>
            </thead>
            <tbody>
            <?php foreach($users as $row){ ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td> 
                        <img  style="width:50px;height:50px; border-radius: 50%;" alt="Avatar"
                         src="/Mahara-Tech-PHP/uploads/<?= $row['name'] . "." . $row['avatar'] ?>" >
                    </td>
                    <td><?php echo $row['name'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= ($row['is_admin'])? 'Yes':'No' ?></td>
                    <td><a href="edit.php?id=<?=$row['id']?>">Edit</a> |
                     <a href="delete.php?id=<?=$row['id']?>">Delete</a></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="text-align: center"><?= count($users) ?> users </td>
                    <td colspan="3" style="text-align: center"><a href="add.php">Add User</a></td>
                </tr>
            </tfoot>
        </table>
    </body>

</html>

<?php
// Close the connection
//mysqli_free_result($result);
//mysqli_close($conn);
?>