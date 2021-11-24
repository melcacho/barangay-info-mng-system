<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$first_name = $middle_name = $last_name = $contact_number = $email = $username = $password = "";
$name_err = $contact_number_err = $email_err = $password_err = $id = "";

$modal_view = $commitee = $position = $delete_modal_view = 0;

if (isset($_GET["id"]) && !empty(trim($_GET["id"])) && trim($_GET["id"]) != '') {
    // Get URL parameter
    $id = trim($_GET["id"]);
}

//Delete Record
if (isset($_GET["delete"]) && trim($_GET["delete"]) != "" && isset($_GET["id"])) {
    $delete = trim($_GET["delete"]);
    if($delete) {
        // Prepare a delete statement
        $sql = "DELETE FROM accounts WHERE id = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = trim($_GET["id"]);

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $delete_modal_view = 0;
            } else {
                echo '<script>
                alert("Oops! Something went wrong. Please try again later.");
                </script>';
            }
        }
    } else {
        $delete_modal_view = 1;
    }
} elseif (isset($_GET["id"]) && !empty(trim($_GET["id"])) && trim($_GET["id"]) != '') {
    // Prepare a select statement
    $sql = "SELECT * FROM accounts WHERE ID = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = $id;

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $first_name = $row["FNAME"];
                $middle_name = $row["MNAME"];
                $last_name = $row["LNAME"];
                $contact_number = $row["CONTACT"];
                $email = $row["EMAIL"];
                $commitee = $row["COMMITEE"];
                $position = $row["POSITION"];
                
                $modal_view = 1;
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
} elseif(isset($_GET["id"]) && empty(trim($_GET["id"]))) {
    $modal_view = 1;
}

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST["id"]) && !empty($_POST["id"]) && $_POST["id"] != '') {
        // Get hidden input value
        $id = $_POST["id"];
    }

    // Validate name
    if (empty(trim($_POST["first-name"])) || empty(trim($_POST["last-name"]))) {
        $name_err = "Must fill";
    } elseif (!preg_match('/^[a-zA-Z ]+$/', trim($_POST["first-name"])) ||
    !preg_match('/^[a-zA-Z ]+$/', trim($_POST["last-name"])) ||
    (!empty(trim($_POST["middle-name"])) && !preg_match('/^[a-zA-Z ]+$/', trim($_POST["middle-name"])))) {
        $name_err = "Must only contain letters";
    } else {
        $first_name = trim($_POST["first-name"]);
        $middle_name = trim($_POST["middle-name"]);
        $last_name = trim($_POST["last-name"]);
    }

    // Validate contact number
    if(empty(trim($_POST["contact-number"]))) {
        $contact_number_err = "Must fill";
    } elseif(strlen(trim($_POST["contact-number"])) < 11) {
        $contact_number_err = "Must be 11 digits";
    } elseif(substr($_POST["contact-number"], 0, 2) != "09") {
        $contact_number_err = "Must start with 09";
    } else {
        $contact_number = trim($_POST["contact-number"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))) {
        $email_err = "Must fill";
    } elseif(!empty($id)) {
        $email = trim($_POST["email"]);
    } else {
        $sql = "SELECT ID FROM accounts WHERE EMAIL = ?";

        if($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_email);
            $param_email = trim($_POST["email"]);

            if($stmt->execute()) {
                $stmt->store_result();

                if($stmt->num_rows > 0) {
                    $email_err = "Already";
                } else {
                    $email = trim($_POST["email"]);
                }
            }
        } else {
            echo '<script>
            alert("Oops! Something went wrong. Please try again later.");
            </script>';
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))) {
        $password_err = "Must Fill";
    } else {
        $password = trim($_POST["password"]);
    }
    
    $words = explode(" ", $first_name);
    $acronym = "";
    
    foreach ($words as $w) {
      $acronym .= $w[0];
    }

    $username = $last_name . $acronym;
    // Validate username
    $sql = "SELECT ID FROM accounts WHERE USERNAME = ?";
    if(empty($id) && $stmt = $mysqli->prepare($sql)) {
        $stmt->bind_param("s", $param_username);
        $param_username = $username;

        if($stmt->execute()) {
            $stmt->store_result();

            if($stmt->num_rows > 0) {
                $name_err = "Name already registered";
                $first_name = $middle_name = $last_name = "";
            }
        }
    } else {
    }

    $commitee = trim($_POST["commitee"]);
    $position = trim($_POST["position"]);

    // Check input errors before inserting in database
    if (empty($name_err) && empty($contact_number_err) && empty($email_err) && empty($password_err)) {

        if(!empty($id)) {
            // Prepare an update statement
            $sql = "UPDATE accounts SET FNAME=?, MNAME=?, LNAME=?, CONTACT=?, EMAIL=?, COMMITEE=?, POSITION=?, 
            USERNAME=?, PASSWORD=? WHERE ID=?";
        } else {
            // Prepare an insert statement
            $sql = "INSERT INTO accounts (FNAME, MNAME, LNAME, CONTACT, EMAIL, COMMITEE, POSITION, USERNAME, PASSWORD) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        }

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            if(!empty($id)) {
                $stmt->bind_param("sssssssssi", $param_first_name, $param_middle_name, $param_last_name,
                $param_contact_number, $param_email, $param_commitee, $param_position, $param_username, 
                $param_password, $param_id);

                $param_id = $id;
            } else {
                $stmt->bind_param("sssssssss", $param_first_name, $param_middle_name, $param_last_name,
                $param_contact_number, $param_email, $param_commitee, $param_position, $param_username, $param_password);

            }
            // Set parameters
            $param_first_name = strtoupper($first_name);
            $param_middle_name = strtoupper($middle_name);
            $param_last_name = strtoupper($last_name);
            $param_contact_number = $contact_number;
            $param_email = strtoupper($email);
            $param_username = strtolower($username); 
            $param_password = password_hash($password, PASSWORD_DEFAULT);
            $param_commitee = $commitee;
            $param_position = $position;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $first_name = $middle_name = $last_name = $contact_number = $email = $username = $password = "";
                $name_err = $contact_number_err = $email_err= $password_err = "";
                                
                $modal_view = $commitee = $position = 0;
            } else {
                echo '<script>
                alert("Oops! Something went wrong. Please try again later.");
                </script>';
            }
        } else {
            echo '<script>
            alert("Oops! Something went wrong. Please try again later.");
            </script>';
        }
    } else {
        $modal_view = 1;
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
                <p>*Barangay Name*</p>
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
                    <a href="#">
                        <span class="icon"><i  class="fas fa-archive"></i></span>
                        Blotter Records
                    </a>
                </li>
                <li>
                    <a href="#">
                        <span class="icon"><i  class="fas fa-calendar"></i></span>
                        Settlement Schedules
                    </a>
                </li>
                <li>
                    <a href="#">
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
                    <a href="#">
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
                    <?php
                    $myfile = fopen("assets/barangay-config/commitee.txt", "r") or die("Unable to open file!");
                    $a_commitee = [];
                    while(!feof($myfile)) {
                        array_push($a_commitee, fgets($myfile));
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
                    $sql = "SELECT * FROM accounts";
                    if ($result = $mysqli->query($sql)) {
                        if ($result->num_rows > 0) {
                            echo '<table class="table table-bordered table-striped text-center">';
                            echo "<thead>";
                            echo "<tr class=\"bg-dark\">";
                            echo "<th>#</th>";
                            echo "<th>Full Name</th>";
                            echo "<th>Commitee</th>";
                            echo "<th>Position</th>";
                            echo "<th>Email</th>";
                            echo "<th>Contact</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = $result->fetch_array()) {
                                echo "<tr>";
                                echo "<td>" . $row['ID'] . "</td>";
                                echo "<td>" . $row['LNAME'] . ', ' . $row['FNAME'] . ' ' . $row['MNAME'][0] . ".</td>";
                                echo "<td>" . $a_commitee[$row['COMMITEE']] . "</td>";
                                echo "<td>" . $a_position[$row['POSITION']] . "</td>";
                                echo "<td>" . $row['EMAIL'] . "</td>";
                                echo "<td>" . $row['CONTACT'] . "</td>";
                                echo "<td>";
                                echo '<a href="?id=' . $row['ID'] . '" class="mr-3 action" title="Update Record" data-toggle="tooltip"><span class="fas fa-pencil-alt"></span></a>';
                                echo '<a href="?id=' . $row['ID'] . '&delete=0" class="action" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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

                    // Close connection
                    $mysqli->close();
                    ?>
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
                            <!-- first-name -->
                            <div class="input-group col-xl-6">
                                <span class="mb-0 mt-auto mx-1">First Name: </span>
                                <input
                                    type="text"
                                    class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                    name="first-name"
                                    placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                    value="<?php echo $first_name; ?>"
                                    >
                            </div>
                            <!-- middle-name -->
                            <div class="input-group col-xl-3">
                                <span class="mb-0 mt-auto mx-1">Middle Name: </span>
                                <input
                                    type="text"
                                    class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                    name="middle-name"
                                    placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                    value="<?php echo $middle_name; ?>"
                                    >
                            </div>
                            <!-- last-name -->
                            <div class="input-group col-xl-3">
                                <span class="mb-0 mt-auto mx-1">Last Name: </span>
                                <input
                                    type="text"
                                    class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                    name="last-name"
                                    placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                    value="<?php echo $last_name; ?>"
                                    >
                            </div>
                        </div>
                        
                        <div class="form-group row">
                        <!-- contact-number -->
                            <div class="input-group col-md-4">
                                <span class="mb-0 mt-auto mx-1">Contact Number: </span>
                                <input
                                type="text"
                                class="form-control <?php echo (!empty($contact_number_err)) ? 'invalid' : ''; ?>"
                                name="contact-number"
                                placeholder="<?php echo (!empty($contact_number_err)) ? $contact_number_err : ''; ?>"
                                maxlength="11"
                                onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;"
                                value="<?php echo $contact_number; ?>">
                            </div>
                        <!-- email -->
                            <div class="input-group col-md-4">
                                <span class="mb-0 mt-auto mx-1">Email: </span>
                                <input 
                                type="email"
                                class="form-control <?php echo (!empty($email_err)) ? 'invalid' : ''; ?>"
                                name="email"
                                placeholder="<?php echo (!empty($email_err)) ? $email_err : ''; ?>"
                                value="<?php echo $email; ?>">
                            </div>
                        <!-- password -->
                            <div class="input-group col-md-4">
                                <span class="mb-0 mt-auto mx-1">Password: </span>
                                <input 
                                type="password"
                                class="form-control <?php echo (!empty($password_err)) ? 'invalid' : ''; ?>" 
                                name="password"
                                placeholder="<?php echo (!empty($password_err)) ? $password_err : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                        <!-- Commitee -->
                            <div class="input-group col-md-6">
                                <span class="mb-0 mt-auto mx-1">Commitee: </span>
                                <select class="form-control" 
                                aria-label="Default select example" 
                                name="commitee"
                                required="required">
                                <?php
                                    $myfile = fopen("assets/barangay-config/commitee.txt", "r") or die("Unable to open file!");
                                    $i = 0;
                                    // Output one character until end-of-file
                                    while(!feof($myfile)) {
                                        echo '<option value="'.$i.'" '.(($commitee == $i++) ? 'selected': '').'>'.fgets($myfile).'</option>';
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
                                required="required">
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

                        <input type="hidden" name="id" value="<?php echo $id; ?>" />
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <div class="modal fade" id="delete_modal" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirm Delete?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body mx-auto">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a href="?id=<?php echo $_GET['id']?>&delete=1" class="btn btn-danger" title="Delete Record" data-toggle="tooltip">Confirm</a>
                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
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

        <?php echo ($modal_view) ? '$(document).ready(function(){
            $(\'#create_modal\').modal(\'show\');});' : ''; ?>
        <?php echo ($delete_modal_view) ? '$(document).ready(function(){
            $(\'#delete_modal\').modal(\'show\');});' : ''; ?>
    </script>
</body>

</html>