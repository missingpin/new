<?php
session_start();

if (isset($_SESSION['username'])) {
} else {
        header("Location:login.php");
        exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="admindashboard.css">
        <link rel="stylesheet" href="adminheader.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
        <Title> User Management </Title>
        </div>
</head>

<body>
        <form action="logout.php" method="POST">
                <button type="submit" class="btn btn-danger">Log Out</button>
        </form>
        <button type="button" class="btn btn-success" onclick="location.href='maintenance.php'">Maintenance</button>
        <div class="modal fade" id="usermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Add User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <form id="userform">
                                        <div class="modal-body">
                                                <div class="form-group">
                                                        <label for="username">Username</label>
                                                        <input type="text" class="form-control" id="username"
                                                                placeholder="Enter Username" required>
                                                </div>
                                                <div class="form-group">
                                                        <label for="password">Password</label>
                                                        <input type="text" class="form-control" id="password"
                                                                placeholder="Enter Password" required>
                                                </div>
                                                <div class="form-group">
                                                        <label for="email ">Email</label>
                                                        <input type="email" class="form-control" id="email"
                                                                placeholder="Enter Email" required>
                                                </div>
                                </form>
                        </div>
                        <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="button" class="btn btn-warning" onclick="adduser()">Insert</button>
                        </div>
                </div>
        </div>
        </div>
        <!-- edit modal -->
        <div class="modal fade" id="editusermodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                                <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Edit User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                        </button>
                                </div>
                                <div class="modal-body">
                                        <div class="form-group">
                                                <label for="editusername">Username</label>
                                                <input type="text" class="form-control" id="editusername"
                                                        placeholder="Enter Username">
                                        </div>
                                        <div class="form-group">
                                                <label for="editpassword">Password</label>
                                                <input type="text" class="form-control" id="editpassword"
                                                        placeholder="Enter Password">
                                        </div>
                                        <div class="form-group">
                                                <label for="editemail">Email</label>
                                                <input type="email" class="form-control" id="editemail"
                                                        placeholder="Enter Email">
                                        </div>
                                </div>
                                <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-warning"
                                                onclick="updateuser()">Update</button>
                                        <input type="hidden" id="hiddendata">
                                </div>
                        </div>
                </div>
        </div>
        <div class="container my-3">
                <h1 class="text-center"> User List </h1>
                <button type="button" class="btn btn-info my-4" data-toggle="modal" data-target="#usermodal"> Add User
                </button>
                <div id="displayuser"></div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


        <script>
                $(document).ready(function () {
                        displayData();
                });

                //displays data
                function displayData() {
                        var display = "true";
                        $.ajax({
                                url: "admintable.php",
                                type: 'post',
                                data: {
                                        displaySend: display
                                },
                                success: function (data, status) {
                                        $('#displayuser').html(data);
                                }
                        })
                }
                //add user
                function adduser() {
                        if (!document.getElementById('userform').checkValidity()) {
                                alert('Please fill out all required fields.');
                                return;
                        }
                        var formData = new FormData();

                        formData.append('usernameSend', $('#username').val());
                        formData.append('passwordSend', $('#password').val());
                        formData.append('emailSend', $('#email').val());
                        $.ajax({
                                url: "adduser.php",
                                type: 'post',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function (data, status) {
                                        console.log(status);
                                        console.log(data);
                                        displayData();
                                },
                        });
                        $('#usermodal').modal('hide');
                }
                //delete function
                function deleteuser(deletefunc) {
                        $.ajax({
                                url: "delete.php",
                                type: 'post',
                                data: {
                                        deleteform: deletefunc
                                },
                                success: function (data, status) {
                                        console.log(data);
                                        displayData();
                                }
                        });
                }
                //edit user
                function edituser(useredit) {
                        $('#hiddendata').val(useredit);
                        $.post("useredit.php", { useredit: useredit }, function (data) {
                                var userid = JSON.parse(data);
                                $('#editusername').val(userid.username);
                                $('#editpassword').val(userid.password);
                                $('#editemail').val(userid.email);
                        });
                        $('#editusermodal').modal("show");
                }


                //update function
                function updateuser() {
                        var editusername = $('#editusername').val();
                        var editpassword = $('#editpassword').val();
                        var editemail = $('#editemail ').val();
                        var hiddendata = $('#hiddendata').val();

                        $.post("edit.php", {
                                editusername: editusername,
                                editpassword: editpassword,
                                editemail: editemail,
                                hiddendata: hiddendata
                        }, function (data, status) {
                                $('#editusermodal').modal("hide");
                                displayData();
                        });
                }
        </script>
</body>

</html>