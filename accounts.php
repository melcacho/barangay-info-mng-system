<?php
    // Include config file
    require_once "config.php";

    // Define variables and initialize with empty values
    $first_name = $middle_name = $last_name = $res_id = $username = $password = $admin_id = $committee = $position = "";
    $modal_create = $modal_delete = $edit = 0;

    $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
    $brgy_name = fgets($myfile);
    $brgy_address = fgets($myfile);
    fclose($myfile);

    if(isset($_GET["id"]) && !empty($_GET["id"])) {
        $admin_id = trim($_GET["id"]);
    } elseif(isset($_GET["id"])) {
        $modal_create = 1;
    }

    if(isset($_GET["del"]) && $_GET["del"]) {
        $modal_delete = 1;
    }

    if(isset($_GET["ed"]) && $_GET["ed"]) {
        $modal_create = 1;
        $edit = 1;

        $sql = "SELECT * FROM admins WHERE ADMIN_ID = ".$admin_id."";
        if ($result = $mysqli->query($sql)) {
            if($result->num_rows > 0) {
                if($row = $result->fetch_array()) {
                    $res_id = $row["RESIDENT_ID"];
                    $username = $row["USERNAME"];
                    $committee = $row["COMMITTEE"];
                    $position = $row["POSITION"];
                }
            } else {
                $res_id_err = "Nonexistent Resident ID";
            }
        }
    }
    
    if(isset($_POST["delete"])) {
        $sql = "DELETE FROM admins WHERE ADMIN_ID = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $param_id);

            $param_id = trim($_POST["id"]);

            if ($stmt->execute()) {
                echo '<script>
                alert("Record Deleted");
                </script>';
                $modal_delete = 0;
            } else {
                echo '<script>
                alert("Delete Sequence Error: Database Access Error");
                </script>';
            }
        }
    }

    if(isset($_POST["create"]) || isset($_POST["edit"])) {
        $sql = "SELECT * FROM admins WHERE USERNAME = '".strtoupper(trim($_POST["username"]))."'";
        if(!preg_match('/^[a-zA-Z ]+$/', trim($_POST["username"]))) {
            $username_err = "Must only contain letters";
        } elseif ($result = $mysqli->query($sql)) {
            if($result->num_rows > 0) {
                if($row = $result->fetch_array()) {
                    if($_POST["id"] != $row["ADMIN_ID"]) {
                        $username_err = "Username Already Taken";
                    } else {
                        $username = trim($_POST["username"]);
                    }
                }
            } else {
                $username = trim($_POST["username"]);
            }
        } else {
            $username = trim($_POST["username"]);
        }

        if(isset($_POST["create"])) {
            $sql = "SELECT * FROM admins WHERE RESIDENT_ID = ?";
            if($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("i", $param_id);
                $param_id = trim($_POST["res-id"]);

                if($stmt->execute()) {
                    $stmt->store_result();
                    if($stmt->num_rows > 0) {
                        $res_id_err = "Already an Admin";
                    } else {
                        $sql = "SELECT * FROM residents WHERE RESIDENT_ID = ".$param_id."";
                        if ($result = $mysqli->query($sql)) {
                            if($result->num_rows > 0) {
                                if($row = $result->fetch_array()) {
                                    $res_id = trim($_POST["res-id"]);
                                    $last_name = $row["LNAME"];
                                    $first_name = $row["FNAME"];
                                    $middle_name = $row["MNAME"];
                                }
                            } else {
                                $res_id_err = "Nonexistent Resident ID";
                            }
                        }
                    }
                }
            }
        }

        $committee = trim($_POST["committee"]);
        $position = trim($_POST["position"]);
        if(empty($username_err) && empty($res_id_err)) {
            $password = trim($_POST["password"]);

            if(isset($admin_id)) {
                $sql = "UPDATE admins SET COMMITTEE=?, POSITION=?, USERNAME=?, PASSWORD=? WHERE ADMIN_ID=?";
            } else {
                $sql = "INSERT INTO admins (LNAME, FNAME, MNAME, COMMITTEE, POSITION, USERNAME, PASSWORD, RESIDENT_ID) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            }


            if ($stmt = $mysqli->prepare($sql)) {
                if(isset($admin_id)) {
                    $stmt->bind_param("ssssi", $param_committee, $param_position, $param_username, 
                    $param_password, $_POST["id"]);
                } else {
                    $stmt->bind_param("ssssssss", $param_last_name, $param_first_name, $param_middle_name, 
                    $param_committee, $param_position, $param_username, $param_password, $param_id);
                    $param_last_name = strtoupper($last_name);
                    $param_first_name = strtoupper($first_name);
                    $param_middle_name = strtoupper($middle_name);
                }

                $param_committee = $committee;
                $param_position = $position;
                $param_username = strtoupper($username);
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                if ($stmt->execute()) {
                    $first_name = $middle_name = $last_name = $res_id = $username = $password = $committee = 
                    $position = $admin_id = "";
                    $modal_create = 0;
                    echo '<script>
                    alert("Admin Added");
                    </script>';
                } else {
                    echo '<script>
                    alert("Push Sequence Error: Database Access Error");
                    </script>';
                }
            } else {
                echo '<script>
                alert("Push Sequence Error: Database Access Error");
                </script>';
            }
        } else {
            $modal_create = 1;
        }
    }

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Barangay Management</title>

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous"></script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous"></script>
    
</head>

<body>

    <div class="wrapper">
        <!-----------------------------------------------------
            Sidebar
        ------------------------------------------------------->

        <nav id="sidebar">
            <button type="button" id="sidebarCollapse1" class="btn btn-info">
                <i class="fas fa-times"></i>
            </button>

            <div class="sidebar-header d-flex justify-content-center">
                <img src="assets/barangay-config/logo.png" alt="Logo" class="logo">
            </div>

            <ul class="list-unstyled components">
                <h4>Brgy. <?php echo $brgy_name;?></h4>
                <li>
                    <a href="index.php">
                        <span class="icon"><i  class="fas fa-home"></i></span>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="resident-information.php">
                        <span class="icon"><i  class="fas fa-users"></i></span>
                        Resident Information
                    </a>
                </li>
                <li>
                    <a href="cert-issuance.php">
                        <span class="icon"><i  class="fas fa-certificate"></i></span>
                        Certificatie Issuance
                    </a>
                </li>
                <li class="active">
                    <a>
                        <span class="icon"><i  class="fas fa-user-cog"></i></span>
                        Accounts
                    </a>
                </li>
                <li>
                    <a href="barangay-config.php">
                        <span class="icon"><i  class="fas fa-cog"></i></span>
                        Barangay Config
                    </a>
                </li>
            </ul>
        </nav>

        <!-----------------------------------------------------
            Content
        ------------------------------------------------------->

        <div id="content">

            <nav id="topbar" class="navbar navbar-expand-lg">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse2" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <h3 class="nav-item ml-auto mr-0 my-auto">ACCOUNTS</h3>
                </div>
            </nav>

            <div class="content">
                <div class="container-xl">
                    <div class="mt-5 mb-3 clearfix d-flex">
                        <h2 class="my-auto">Admin Account Details</h2>

                        <a class="btn btn-success ml-auto mr-0 my-auto" 
                        data-toggle="tooltip"
                        href="?id=">
                            <i class="fa fa-plus"></i> 
                            Add New Admin
                        </a>
                    </div>

                    <div class="overflow-auto">
                        <?php
                            $myfile = fopen("assets/barangay-config/committee.txt", "r") or die("Unable to open file!");
                            $a_committee = [];
                            while(!feof($myfile)) {
                                array_push($a_committee, fgets($myfile));
                            }
                            fclose($myfile);

                            $myfile = fopen("assets/barangay-config/position.txt", "r") or die("Unable to open file!");
                            $a_position = [];
                            while(!feof($myfile)) {
                                array_push($a_position, fgets($myfile));
                            }
                            fclose($myfile);

                            // Include config file
                            require_once "config.php";

                            // Attempt select query execution
                            $sql = "SELECT * FROM admins";
                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped text-center">';
                                    echo "<thead>";
                                    echo "<tr class=\"bg-dark\">";
                                    echo "<th>Full Name</th>";
                                    echo "<th>Username</th>";
                                    echo "<th>Committee</th>";
                                    echo "<th>Position</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['LNAME'] . ', ' . $row['FNAME'] . ' ' . $row['MNAME'][0] . ".</td>";
                                        echo "<td>" . $row['USERNAME'] . "</td>";
                                        echo "<td>" . $a_committee[$row['COMMITTEE']] . "</td>";
                                        echo "<td>" . $a_position[$row['POSITION']] . "</td>";
                                        echo "<td>";
                                        echo '<a href="?id=' . $row['ADMIN_ID'] . '&ed=1" class="mr-3 action" title="Update Record" data-toggle="tooltip"><span class="fas fa-pencil-alt"></span></a>';
                                        echo '<a href="?id=' . $row['ADMIN_ID'] . '&del=1" class="action" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    echo "</tbody>";
                                    echo "</table>";
                                    // Free result set
                                    $result->free();
                                } else {
                                    echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                }
                            } else {
                                echo '<script>
                                alert("Oops! Something went wrong. Please try again later.");
                                </script>';
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-----------------------------------------------------
        Modal
    ------------------------------------------------------->
    
    <div class="modal fade" id="create_modal" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Create Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group row">
                            <!-- resident id -->
                            <div class="input-group col-xl-4">
                                <span class="mb-0 mt-auto mx-1">Resident ID: </span>
                                <input type="text"
                                    class="form-control <?php echo (!empty($res_id_err)) ? 'invalid' : ''; ?>"
                                    name="res-id"
                                    placeholder="<?php echo (!empty($res_id_err)) ? $res_id_err : ''; ?>"
                                    maxlength="6"
                                    onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;"
                                    value="<?php echo $res_id; ?>"
                                    <?php echo ($edit) ? "disabled" : ""?>
                                    required>
                            </div>
                            <!-- middle-name -->
                            <div class="input-group col-xl-4">
                                <span class="mb-0 mt-auto mx-1">Username: </span>
                                <input type="text"
                                    class="form-control <?php echo (!empty($username_err)) ? 'invalid' : ''; ?>"
                                    name="username"
                                    placeholder="<?php echo (!empty($username_err)) ? $username_err : ''; ?>"
                                    value="<?php echo $username; ?>"
                                    required>
                            </div>
                            <!-- password -->
                            <div class="input-group col-md-4">
                                <span class="mb-0 mt-auto mx-1">Password: </span>
                                <input type="password"
                                    class="form-control <?php echo (!empty($password_err)) ? 'invalid' : ''; ?>" 
                                    name="password"
                                    placeholder="<?php echo (!empty($password_err)) ? $password_err : ''; ?>"
                                    required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <!-- Committee -->
                            <div class="input-group col-md-6">
                                <span class="mb-0 mt-auto mx-1">Committee: </span>
                                <select class="form-control" 
                                    aria-label="Default select example" 
                                    name="committee"
                                    required>
                                    <option value='' hidden selected>Select</option>
                                    <?php
                                        $myfile = fopen("assets/barangay-config/committee.txt", "r") or die("Unable to open file!");
                                        $i = 0;
                                        // Output one character until end-of-file
                                        while(!feof($myfile)) {
                                            echo '<option value="'.$i.'" '.(($committee == $i++) ? 'selected': '').'>'.fgets($myfile).'</option>';
                                        }
                                        fclose($myfile);
                                    ?>
                                </select>
                            </div>
                            <!-- Position -->
                            <div class="input-group col-md-6">
                                <span class="mb-0 mt-auto mx-1">Position: </span>
                                <select class="form-control" 
                                    aria-label="Default select example" 
                                    name="position"
                                    required>
                                    <option value='' hidden selected>Select</option>
                                    <?php
                                        $myfile = fopen("assets/barangay-config/position.txt", "r") or die("Unable to open file!");
                                        $i = 0;
                                        // Output one character until end-of-file
                                        while(!feof($myfile)) {
                                            echo '<option value="'.$i.'" '.(($position == $i++) ? 'selected': '').'>'.fgets($myfile).'</option>';
                                        }
                                        fclose($myfile);
                                    ?>
                                    </select>
                            </div>
                        </div>

                        <input type="hidden" name="id" value="<?php echo (isset($admin_id)) ? $admin_id : '';?>" />
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button name="<?php echo ($edit) ? "edit" : "create"?>"
                                type="submit" class="btn btn-primary">
                                <?php echo ($edit) ? "Update" : "Add"?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md " role="document">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="exampleModalLongTitle">WARNING!</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-auto">
                    <h4>Delete data of</h4>
                    <?php 
                        if($modal_delete) {
                            require_once "config.php";

                            // Prepare a select statement
                            $sql = "SELECT FNAME, MNAME, LNAME FROM admins WHERE ADMIN_ID = ?";
                            if ($stmt = $mysqli->prepare($sql)) {

                                // Bind variables to the prepared statement as parameters
                                $stmt->bind_param("i", $param_id);
                                // Set parameters
                                $param_id = $admin_id;

                                // Attempt to execute the prepared statement
                                if ($stmt->execute()) {
                                    $result = $stmt->get_result();

                                    if ($result->num_rows == 1) {
                                        /* Fetch result row as an associative array. Since the result set
                                        contains only one row, we don't need to use while loop */
                                        $row = $result->fetch_array(MYSQLI_ASSOC);

                                        echo '<h4>'.$row['LNAME'].', '.$row['FNAME'].' '.$row['MNAME'][0].'. ?</h4>';
                                        
                                    } else {
                                        echo '<script>
                                        alert("Error123");
                                        </script>';
                                    }
                                } else {
                                    echo '<script>
                                    alert("Oops! Something went wrong. Please try again later.");
                                    </script>';
                                }
                            }   
                        }
                        // Close connection
                        $mysqli->close();
                    ?>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <input type="hidden" name="id" value="<?php echo $admin_id;?>" />
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" title="Close">Close</button>
                        <button name="delete" type="submit" class="btn btn-danger" title="Delete Record">Confirm</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- jQuery Custom Scroller CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse1').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });

            $('#sidebarCollapse2').on('click', function () {
                $('#sidebar, #content').toggleClass('active');
                $('.collapse.in').toggleClass('in');
                $('a[aria-expanded=true]').attr('aria-expanded', 'false');
            });
        });

        <?php echo ($modal_create) ? '$(document).ready(function(){
            $(\'#create_modal\').modal(\'show\');});' : ''; ?>
        <?php echo ($modal_delete) ? '$(document).ready(function(){
            $(\'#delete_modal\').modal(\'show\');});' : ''; ?>
    </script>
</body>

</html>