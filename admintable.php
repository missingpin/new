<?php
    include 'connect.php';
    if(isset($_POST['displaySend'])){
        $table='
        <style>
    .table tbody {
        background-color: white;
    }
</style>
<table class="table table-striped table-bordered">
        <thead class="thead-dark">
          <tr>
            <th scope="col">ID</th>
            <th scope="col">Email</th>
            <th scope="col">Username</th>
            <th scope="col">Password</th>
            <th scope="col"> </th>
          </tr>
        </thead>';
        $sql="SELECT * FROM form";
        $result=mysqli_query($con,$sql);
        $number=1;
        while($row=mysqli_fetch_assoc($result)){
            $id=$row['id'];
            $email=$row['email'];
            $username=$row['username'];
            $password=$row['password'];
            $table.='<tr>
            <td scope="row">'.$number.'</th>
            <td>'.$email.'</td>
            <td>'.$username.'</td>
            <td>'.$password.'</td>
            <td style="width: 150px;">
                <button class="btn btn-primary btn-sm" onclick="edituser('.$id.')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteuser('.$id.')">Delete</button>
            </td>
        </tr>';
        $number++;
        }
        $table.='</table>';
        echo $table;
    }
    ?>