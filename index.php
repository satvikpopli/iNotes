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
        <h2 class="mb-4 d-flex justify-content-center">Add New Note</h2>
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
            if (isset($_POST["snoEdit"]) && isset($_POST['update'])) {
                $title = $_POST["titleEdit"];
                $description = $_POST["descEdit"];
                $snum = $_POST["snoEdit"];
                $sql_update = "UPDATE `crud`.`notes` SET `title` = '$title', `description` = '$description' WHERE (`sno` = $snum)";
                $update = $conn->query($sql_update);
            } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
                $snum = $_POST["snoDelete"];
                $sql_delete = "DELETE FROM `crud`.`notes` WHERE (`sno` = $snum)";
                $delete = $conn->query($sql_delete);
            } else {
                echo "here";
                $title = $_POST["title"];
                $description = $_POST["desc"];
                $sql_insert = "INSERT INTO `crud`.`notes` (`title`, `description`) VALUES ('$title', '$description')";
                $insert = $conn->query($sql_insert);
                if ($insert) {
                    echo
                    "<div class='alert alert-success alert-dismissible fade show my-4' role='alert'>
                Note added successfully.
                <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
                </button>
                </div>";
                }
            }
        }

        echo '<br> <hr> <h2 class="mb-2 d-flex justify-content-center my-5">Saved Notes</h2>';

        $sql = "SELECT * FROM crud.notes";

        ?>
        <div class="table-responsive">
            <table class="table my-4 table-hover" id="myTable">
                <thead class="thead-dark text-center">
                    <tr>
                        <th scope="col" class="text-center col-1">#</th>
                        <th scope="col" class="text-center col-2">Title</th>
                        <th scope="col" class="text-center col-7">Description</th>
                        <th scope="col" class="text-center col-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $item = 1;
                    foreach ($conn->query($sql) as $row) {
                        echo "<tr>
                        <th scope='row' class='text-center'> $item </th>
                        <td class='text-center'>" . $row['title'] . "</td>
                        <td class='d-flex justify-content-around'>" . $row['description'] . "</td>
                        <td class='text-center'>" .
                            "<button type='button' class='edit mx-1 my-1 btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#editModal' id=" . $row['sno'] . ">Edit</button> 
                            <button type='button' class='delete mx-1 my-1 btn btn-outline-primary btn-sm' data-toggle='modal' data-target='#deleteModal' id=" . $row['sno'] . ">Delete</button> " . "</td>
                        </tr>";
                        $item++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class=" modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModal">Edit Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="index.php" method="POST">
                        <input type="hidden" name="snoEdit" id="snoEdit">
                        <div class="mb-3">
                            <label for="titleEdit" class="form-label">Title</label>
                            <input type="text" class="form-control" id="titleEdit" name="titleEdit" required>
                        </div>
                        <div class="mb-3">
                            <label for="descEdit" class="form-label">Description (optional)</label>
                            <textarea class="form-control" id="descEdit" rows="5" name="descEdit" style="height:100%;" maxlength="150"></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" name="update" value="true">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="font-weight-bold">Are you sure you want to delete this note?</p>
                    <p>This note will be deleted immediately. This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <form action="index.php" method="post">
                        <input type="hidden" name="snoDelete" id="snoDelete">
                        <button type="submit" class="btn btn-primary" name="delete" value="true">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
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
    <script>
        edits = document.getElementsByClassName('edit');
        Array.from(edits).forEach((element) => {
            element.addEventListener("click", (e) => {
                tr = e.target.parentNode.parentNode;
                title = tr.getElementsByTagName("td")[0].innerText;
                description = tr.getElementsByTagName("td")[1].innerText;
                titleEdit.value = title;
                descEdit.value = description;
                snoEdit.value = e.target.id;
            })
        })

        deletes = document.getElementsByClassName('delete');
        Array.from(deletes).forEach((element) => {
            element.addEventListener("click", (e) => {
                tr = e.target.parentNode.parentNode;
                console.log(e.target.id);
                snoDelete.value = e.target.id;
            })
        })
    </script>
</body>



</html>