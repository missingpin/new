<?php
include 'connect.php';
include 'sidebar.php';
$errorMessages = [];


if (!empty($errorMessages)) {
    file_put_contents('error_log.txt', implode("\n", $errorMessages), FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="table.css">
    <title>Table</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
</head>

<body>
    <!-- Add Product Modal -->
    <div class="modal fade" id="productmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="productname">Product Name</label>
                        <input type="text" class="form-control" id="productname" placeholder="Insert Product Name">
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="number" class="form-control" id="quantity" placeholder="Insert the Amount of Product" min="1">
                    </div>
                    <div class="form-group">
                        <label for="exp">Expiration</label>
                        <input type="date" class="form-control" id="exp" placeholder="Insert Expiration Date" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" class="form-control" id="image" placeholder="Insert Product Image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="addproduct()">Insert</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="editproductname">Product Name</label>
                        <input type="text" class="form-control" id="editproductname" placeholder="Insert Product Name">
                    </div>
                    <div class="form-group">
                        <label for="editquantity">Quantity</label>
                        <input type="number" class="form-control" id="editquantity" placeholder="Insert the Amount of Product" min="1">
                    </div>
                    <div class="form-group">
                        <label for="editexp">Expiration</label>
                        <input type="date" class="form-control" id="editexp" placeholder="Insert Expiration Date" min="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label for="editimage">Image</label>
                        <input type="file" class="form-control" id="editimage" placeholder="Insert Product Image">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" onclick="showUpdateConfirmation()">Update</button>
                    <input type="hidden" id="hiddendata">
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Update Modal -->
    <div class="modal fade" id="confirmUpdateModal" tabindex="-1" role="dialog" aria-labelledby="confirmUpdateTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmUpdateTitle">Confirm Update</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to update this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-warning" id="confirmUpdateButton">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Delete Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteTitle">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container my-3">
    <h1 class="text-center"> Inventory </h1>
    <div class="d-flex justify-content-between align-items-center my-4">
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#productmodal"> Add Product </button>
        <input type="text" id="searchInput" class="search-input" placeholder="Search..." onkeyup="filterTable()">
    </div>
    <div id="displaytable"></div>
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function () {
            displayData();
        });

        // Displays data
        function displayData() {
    var display = "true";
    $.ajax({
        url: "display.php",
        type: 'post',
        data: {
            displaySend: display
        },
        success: function (data, status) {
            $('#displaytable').html(data);

            // After updating the table, handle images
            $('#displaytable img').each(function() {
                if ($(this).attr('src') === '') {
                    $(this).attr('src', 'C:/xampp/htdocs/no-image.jpg'); // Replace with your image path
                    $(this).attr('alt', 'No image available');
                }
            });
        }
    });
}


        function isNonNegative(number) {
            return number >= 1;
        }

        function isFutureDate(date) {
            var today = new Date().toISOString().split('T')[0];
            return date >= today;
        }

        function addproduct() {
    var productname = $('#productname').val();
    var quantity = $('#quantity').val();
    var expiration = $('#exp').val();

    if (!isNonNegative(quantity)) {
        alert("Quantity cannot be negative or zero!");
        return;
    }
    if (!isFutureDate(expiration)) {
        alert("Expiration date cannot be in the past!");
        return;
    }

    var formData = new FormData();
    formData.append('productSend', productname);
    formData.append('quantitySend', quantity);
    formData.append('expirationSend', expiration);

    var fileInput = $('#image')[0];
    if (fileInput.files.length > 0) {
        var file = fileInput.files[0];
        formData.append('imageSend', file, file.name);
    } else {
        console.error("No file selected.");
        return;
    }

    $.ajax({
    url: "insert.php",
    type: 'post',
    data: formData,
    processData: false,
    contentType: false,
    success: function (data, status) {
        displayData(); // Refresh data after successful insert
    },
    error: function (xhr, status, error) {
        console.error("Error inserting product:", error);
        alert("Failed to add product. Please try again.");
    },
    complete: function () {
        $('#productmodal').modal('hide'); // Hide the modal after the AJAX call completes
        $('.modal-backdrop').remove(); // Remove backdrop manually if still present
    }
});

}


        let itemToDelete = null; // Variable to hold the item ID to delete

        function deleteline(deleteval) {
            itemToDelete = deleteval; // Store the item ID
            $('#confirmDeleteModal').modal('show'); // Show the confirmation modal
        }

        $('#confirmDeleteButton').on('click', function () {
            if (itemToDelete) {
                $.ajax({
                    url: "delete.php",
                    type: 'post',
                    data: {
                        deletedata: itemToDelete
                    },
                    success: function (data, status) {
                        displayData(); // Refresh the data after deletion
                        $('#confirmDeleteModal').modal('hide'); // Hide the modal
                    }
                });
            }
        });

        // Edit function
        function editline(editval) {
            $('#hiddendata').val(editval);
            $.post("edit.php", { editval: editval }, function (data, status) {
                var userid = JSON.parse(data);
                $('#editproductname').val(userid.productname);
                $('#editquantity').val(userid.quantity);
                $('#editexp').val(userid.exp);
                $('#editimage').val(userid.image);
            });
            $('#editmodal').modal("show");
        }

        // Trigger update confirmation
        function showUpdateConfirmation() {
            $('#confirmUpdateModal').modal('show');
        }

        // Handle the update confirmation
        $('#confirmUpdateButton').on('click', function () {
            updateproduct(); // Call the update function
            $('#confirmUpdateModal').modal('hide'); // Hide the confirmation modal
        });

        // Update function modified to show confirmation
        function updateproduct() {
            var editproductname = $('#editproductname').val();
            var editquantity = $('#editquantity').val();
            var editexp = $('#editexp').val();
            var hiddendata = $('#hiddendata').val();

            if (!isNonNegative(editquantity)) {
                alert("Quantity cannot be negative or zero!");
                return;
            }
            if (!isFutureDate(editexp)) {
                alert("Expiration date cannot be in the past!");
                return;
            }

            $.post("edit.php", {
                editproductname: editproductname,
                editquantity: editquantity,
                editexp: editexp,
                hiddendata: hiddendata
            }, function (data, status) {
                $('#editmodal').modal("hide");
                displayData();
            });
        }
    </script>
</body>

</html>
