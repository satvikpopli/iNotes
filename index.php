<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>iNOTES</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand mx-auto" href="#">NOTE TAKING APP</a>
        </div>
    </nav>
    <div class="my-5 w-75 mx-auto">
        <h2 class="mb-4 d-flex justify-content-center">Add a Note</h2>
        <form action="index.php" method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="desc" class="form-label">Description (optional)</label>
                <textarea class="form-control" id="desc" rows="5" name="desc" style="height:100%;" maxlength="150"></textarea>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary">Add Note</button>
            </div>
        </form>


        <?php
        require 'dbconnect.php';
        try {
            $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
            // set the PDO error mode to exception
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = $_POST["title"];
            $description = $_POST["desc"];
            $sql_insert = "INSERT INTO `crud`.`notes` (`title`, `description`) VALUES ('$title', '$description')";
            $conn->query($sql_insert);
            echo
            "<div class='alert alert-success alert-dismissible fade show my-4' role='alert'>
            Note added successfully.
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>&times;</span>
            </button>
            </div>";
        }

        echo '<br> <hr> <h2 class="mb-2 d-flex justify-content-center my-5">Notes</h2>';

        $sql = "SELECT * FROM crud.notes";

        ?>
        <table class="table my-4" id="myTable">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Time</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $item = 1;
                foreach ($conn->query($sql) as $row) {
                    echo "<tr>
                    <th scope='row'> $item </th>
                    <td>" . $row['title'] . "</td>
                    <td>" . $row['description'] . "</td>
                    <td>" . $row['tstamp'] . "</td>
                    </tr>";
                    $item++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable();
        });
    </script>
</body>

</html>