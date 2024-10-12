<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="table.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>
<body>

<?php
include 'connect.php';

if (isset($_POST['displaySend'])) {
    $table = '
    <table class="table table-striped table-bordered table-sortable">
    <thead class="thead-dark">
        <tr>
            <th scope="col" style="text-align: center;">ID <span class="sort-indicator" data-sort="id"></span></th>
            <th scope="col" style="text-align: center;">Product Image</th>
            <th scope="col" style="text-align: center;">Product Name <span class="sort-indicator" data-sort="name"></span></th>
            <th scope="col" style="text-align: center;">Quantity <span class="sort-indicator" data-sort="quantity"></span></th>
            <th scope="col" style="text-align: center;">Expiration <span class="sort-indicator" data-sort="expiration"></span></th>
            <th scope="col" style="text-align: center;">Actions</th>
        </tr>
        </thead>
        <tbody>';

    $sql = "SELECT * FROM product";
    $result = mysqli_query($con, $sql);
    $number = 1;

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $image = $row['image'] ? 'uploads/' . $row['image'] : 'no-image.jpg';
        $productname = htmlspecialchars($row['productname']);
        $quantity = htmlspecialchars($row['quantity']);
        $exp = htmlspecialchars($row['exp']);

        $table .= '<tr>
            <td scope="row">' . $number . '</td>
            <td><img src="' . $image . '" width="100" alt="' . $productname . '" data-toggle="modal" data-target="#imageModal" data-img="' . $image . '"></td>
            <td>' . $productname . '</td>
            <td>' . $quantity . '</td>
            <td>' . $exp . '</td>
            <td style="width: 150px;">
                <button class="btn btn-primary btn-sm" onclick="editline(' . $id . ')">Edit</button>
                <button class="btn btn-danger btn-sm" onclick="deleteline(' . $id . ')">Delete</button>
            </td>
        </tr>';
        $number++;
    }

    $table .= '</tbody></table>';
    echo $table;
}
?>

<!-- Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Product Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body d-flex justify-content-center align-items-center">
                <img id="modalImage" src="" alt="" style="max-width: 100%; max-height: 90vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
    let ascendingId = true;
    let ascendingName = true;
    let ascendingQuantity = true;
    let ascendingExpiration = true;

    $(document).ready(function() {
        $('th').click(function() {
            const index = $(this).index();
            const sortIndicator = $(this).find('.sort-indicator');

            // Clear all sort indicators before updating the clicked one
            $('.sort-indicator').html('');

            if (index === 0) {
                sortTable(index, 'number');
                updateSortIndicator(sortIndicator, ascendingId);
            } else if (index === 2) {
                sortTable(index, 'string');
                updateSortIndicator(sortIndicator, ascendingName);
            } else if (index === 3) {
                sortTable(index, 'number');
                updateSortIndicator(sortIndicator, ascendingQuantity);
            } else if (index === 4) {
                sortTable(index, 'date');
                updateSortIndicator(sortIndicator, ascendingExpiration);
            }
        });
    });

    function updateSortIndicator(indicator, ascending) {
        indicator.html(ascending ? '▲' : '▼');
    }

    function sortTable(columnIndex, type) {
        const table = $('.table tbody');
        const rows = table.find('tr').toArray().sort(compareRows);

        if (type === 'number' && columnIndex === 0) {
            ascendingId = !ascendingId;
        } else if (type === 'string' && columnIndex === 2) {
            ascendingName = !ascendingName;
        } else if (type === 'number' && columnIndex === 3) {
            ascendingQuantity = !ascendingQuantity;
        } else if (type === 'date' && columnIndex === 4) {
            ascendingExpiration = !ascendingExpiration;
        }

        rows.forEach(row => table.append(row));

        function compareRows(a, b) {
            const aValue = type === 'number' 
                ? parseInt($(a).children('td').eq(columnIndex).text()) 
                : type === 'date' 
                    ? new Date($(a).children('td').eq(columnIndex).text()) 
                    : $(a).children('td').eq(columnIndex).text();
                    
            const bValue = type === 'number' 
                ? parseInt($(b).children('td').eq(columnIndex).text()) 
                : type === 'date' 
                    ? new Date($(b).children('td').eq(columnIndex).text()) 
                    : $(b).children('td').eq(columnIndex).text();

            return type === 'number'
                ? columnIndex === 0 ? (ascendingId ? aValue - bValue : bValue - aValue) 
                : (ascendingQuantity ? aValue - bValue : bValue - aValue) 
                : type === 'date'
                    ? (ascendingExpiration ? aValue - bValue : bValue - aValue) 
                    : ascendingName ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
        }
    }

    function filterTable() {
        const input = document.getElementById('searchInput');
        const filter = input.value.toLowerCase();
        const table = document.querySelector('.table tbody');
        const rows = table.getElementsByTagName('tr');

        for (let i = 0; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < cells.length; j++) {
                const cell = cells[j];
                if (cell) {
                    const textValue = cell.textContent || cell.innerText;
                    if (textValue.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
            }
            rows[i].style.display = match ? '' : 'none';
        }
    }

    $(document).on('click', 'img[data-toggle="modal"]', function() {
        var imgSrc = $(this).data('img');
        $('#modalImage').attr('src', imgSrc);
    });
</script>

</body>
</html>