<?php
    require_once "config.php";
    
    $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
    $brgy_name = fgets($myfile);
    $brgy_address = fgets($myfile);
    fclose($myfile);

    $a_civil_status = ["Single", "Married", "Separated", "Widowed"];
    $a_cs_value = ['SG', 'MR', 'SP', 'WD'];
    $a_sector= ["Private", "Public", "Government", "Unemployed", "Out of School Youth (OSY)", "Out of School Children (OSC)", "Person With Disability (PWD)",
    "Senior Citizen (SC)", "Overseas Filipino Worker (OFW)", "Solo Parent", "Indigenous People (IP)", "Others"];
    $a_st_value = ['PRV', 'PUB', 'GOV', 'UEP', 'OSY', 'OSC', 'PWD', 'SEN', 'OFW', 'SPA', 'IDP', 'OTH'];

    $category = $search = $res_id = "";
    $modal_delete = 0;

    if(isset($_POST["search"])) {
        $search = trim($_POST["search"]);
        $category = trim($_POST["category"]);
    }

    if(isset($_POST["reset"])) {
        $category = $search = "";
    }

    if(isset($_GET["id"])) {
        $res_id = trim($_GET["id"]);
    }

    if(!empty($res_id) && !isset($_GET["del"])) {
        if(isset($_GET["view"])) {
            $type = "view=";
        } elseif(isset($_GET["edit"])) {
            $type = "edit=";
        }

        echo "<script>
            var width = window.outerWidth*(4/5);
            var height = window.outerHeight*(3/4);
            var left = (screen.width/2)-(width/2);
            var top = (screen.height/2)-(height/2);
            var features =' width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', resizable=false';
            var addNewResident = window.open('popup-buffer.php?id=".$res_id."&".$type."&loggedin=1', 'window', features);
        </script>";
    } elseif(isset($_GET["del"])) {
        $modal_delete = 1;
    }

    if(isset($_POST["delete"])) {
        $sql = "DELETE FROM residents WHERE RESIDENT_ID = ?";

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
                    <a href="dashboard.php">
                        <span class="icon"><i  class="fas fa-home"></i></span>
                        Dashboard
                    </a>
                </li>
                <li class="active">
                    <a>
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
                <li>
                    <a href="accounts.php">
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

                    <h3 class="nav-item ml-auto mr-0 my-auto">RESIDENT INFORMATION</h3>
                </div>
            </nav>

            <div class="content">
                <div class="container-xl">
                    <div class="mt-5 mb-3 clearfix d-flex">
                        <h2 class="my-auto">Resident Information Management</h2>
                    </div>

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="db-header"
                        class="py-2 m-0">
                        <div class="input-group">
                            <!-- search -->
                            <div class="input-group col-sm-3 mb-2">
                                <span class="mb-0 mt-auto mx-1">Search: </span>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="search"
                                    maxlength="50"
                                    title="Search"
                                    value="<?php echo $search?>">
                            </div>
                            <!-- category -->
                            <div class="input-group col-sm-3 mb-2">
                                <select class="form-control" 
                                    name="category"
                                    required>
                                    <option value="0" <?php echo ($category == "0") ? 'selected' : ''?>>Last Name</option>
                                    <option value="1" <?php echo ($category == "1") ? 'selected' : ''?>>First Name</option>
                                    <option value="2" <?php echo ($category == "2") ? 'selected' : ''?>>Alias</option>
                                </select>
                            </div>

                            <div class="col-sm-2">
                                <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i></button>
                                <button name="reset" class="btn btn-secondary"
                                    <?php echo (empty($search)) ? "disabled" : ""?>><i class="fas fa-sync"></i></button>
                            </div>

                            <div class="col-sm-4 text-right">
                                <a class="btn btn-success" 
                                    data-toggle="tooltip"
                                    onclick="popupOpen()">
                                    <i class="fa fa-plus"></i> 
                                    New Resident
                                </a>
                            </div>
                        </div>
                    </form>

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

                            if(!empty($search)) {
                                switch($category) {
                                    case 0: 
                                        $sql = "SELECT * FROM residents WHERE LNAME = '".$search."'";
                                        break;
                                    case 1: 
                                        $sql = "SELECT * FROM residents WHERE FNAME = '".$search."'";
                                        break;
                                    case 2: 
                                        $sql = "SELECT * FROM residents WHERE ALIAS = '".$search."'";
                                        break;
                                }
                            } else {
                                $sql = "SELECT * FROM residents";
                            }

                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped text-center bg-white">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>Action</th>";
                                    echo "<th>Resident ID</th>";
                                    echo "<th>Family Name</th>";
                                    echo "<th>First Name</th>";
                                    echo "<th>Middle Name</th>";
                                    echo "<th>Alias</th>";
                                    echo "<th>Sex</th>";
                                    echo "<th>Birth Date (YYYY-MM-DD)</th>";
                                    echo "<th>Birth Place</th>";
                                    echo "<th>Civil Status</th>";
                                    echo "<th>Voter Status</th>";
                                    echo "<th>Active Voter</th>";
                                    echo "<th>Face Marks</th>";
                                    echo "<th>Nationality</th>";
                                    echo "<th>Religion / Belief</th>";
                                    echo "<th>Occupation</th>";
                                    echo "<th>Sector</th>";
                                    echo "<th>Spouse's Name</th>";
                                    echo "<th>Spouse's Occupation</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo '<a href="?id=' . $row['RESIDENT_ID'] . '&view=" class="mr-3 action" title="View Record" data-toggle="tooltip"><span class="fas fa-eye"><span class="fa fa-eye"></span></a>';
                                        echo '<a href="?id=' . $row['RESIDENT_ID'] . '&edit=" class="mr-3 action" title="Update Record" data-toggle="tooltip"><span class="fas fa-pencil-alt"></span></a>';
                                        echo '<a href="?id=' . $row['RESIDENT_ID'] . '&del=" class="action" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</td>";
                                        echo "<td>" . $row['RESIDENT_ID'] . "</td>";
                                        echo "<td>" . $row['LNAME'] . "</td>";
                                        echo "<td>" . $row['FNAME'] . "</td>";
                                        echo "<td>" . $row['MNAME'] . "</td>";
                                        echo "<td>" . $row['ALIAS'] . "</td>";
                                        echo "<td>" . $row['SEX'] . "</td>";
                                        echo "<td>" . $row['BIRTH_DATE'] . "</td>";
                                        echo "<td>" . $row['BIRTH_PLACE'] . "</td>";
                                        echo "<td>" . $a_civil_status[array_search($row['CIVIL_STATUS'], $a_cs_value)]. "</td>";
                                        echo "<td>" . (($row['VOTER_STATUS']) ? "Yes" : "No"). "</td>";
                                        echo "<td>" . (($row['VOTER_ACTIVE']) ? "Yes" : "No"). "</td>";
                                        echo "<td>" . $row['FACE_MARKS'] . "</td>";
                                        echo "<td>" . $row['NATIONALITY'] . "</td>";
                                        echo "<td>" . $row['BELIEF'] . "</td>";
                                        echo "<td>" . $row['OCCUPATION'] . "</td>";
                                        echo "<td>" . $a_sector[array_search($row['SECTOR'], $a_st_value)]. "</td>";
                                        echo "<td>" . $row['SPOUSE_NAME'] . "</td>";
                                        echo "<td>" . $row['SPOUSE_OCCUPATION'] . "</td>";
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
                            $sql = "SELECT FNAME, MNAME, LNAME FROM residents WHERE RESIDENT_ID = ?";
                            if ($stmt = $mysqli->prepare($sql)) {

                                // Bind variables to the prepared statement as parameters
                                $stmt->bind_param("i", $param_id);
                                // Set parameters
                                $param_id = $res_id;

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
                                        alert("ID Error");
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
                        <input type="hidden" name="id" value="<?php echo $res_id;?>" />
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
            
            <?php echo ($modal_delete) ? "$('#delete_modal').modal('show');" : ""?>
        });

        function popupOpen() {
            var width = window.outerWidth*(4/5);
            var height = window.outerHeight*(3/4);
            var left = (screen.width/2)-(width/2);
            var top = (screen.height/2)-(height/2);
            var features =' width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', resizable=false';
            var addNewResident = window.open('popup-buffer.php?loggedin=1', "window", features);
        }
        
        <?php echo ($modal_delete) ? '$(document).ready(function(){
            $(\'#delete_modal\').modal(\'show\');});' : ''; ?>
        
        if(typeof window.history.pushState == 'function') {
            window.history.pushState({}, "Hide", "resident-information.php");
        }
    </script>
</body>

</html>