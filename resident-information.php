<?php
    $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
    $brgy_name = fgets($myfile);
    $brgy_address = fgets($myfile);
    fclose($myfile);
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
                <li class="active">
                    <a>
                        <span class="icon"><i  class="fas fa-users"></i></span>
                        Resident Information
                    </a>
                </li>
                <li>
                    <a href="blotter-records.php">
                        <span class="icon"><i  class="fas fa-archive"></i></span>
                        Blotter Records
                    </a>
                </li>
                <li>
                    <a href="settlement-schedules.php">
                        <span class="icon"><i  class="fas fa-calendar"></i></span>
                        Settlement Schedules
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

                        <a class="btn btn-success ml-auto mr-0 my-auto" 
                        data-toggle="tooltip"
                        onclick="popupOpen()">
                            <i class="fa fa-plus"></i> 
                            New Resident
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
                            $sql = "SELECT * FROM residents";
                            if ($result = $mysqli->query($sql)) {
                                if ($result->num_rows > 0) {
                                    echo '<table class="table table-bordered table-striped text-center">';
                                    echo "<thead>";
                                    echo "<tr class=\"bg-dark\">";
                                    echo "<th>Family Name</th>";
                                    echo "<th>First Name</th>";
                                    echo "<th>Middle Name</th>";
                                    echo "<th>Alias</th>";
                                    echo "<th>Face Marks</th>";
                                    echo "<th>Birth Date</th>";
                                    echo "<th>Birth Place</th>";
                                    echo "<th>Sex</th>";
                                    echo "<th>Civil Status</th>";
                                    echo "<th>Nationality</th>";
                                    echo "<th>Religion / Belief</th>";
                                    echo "<th>Occupation</th>";
                                    echo "<th>Spouse's Name</th>";
                                    echo "<th>Spouse's Occupation</th>";
                                    echo "<th>Voter Status</th>";
                                    echo "<th>Action</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['LNAME'] . "</td>";
                                        echo "<td>" . $row['FNAME'] . "</td>";
                                        echo "<td>" . $row['MNAME'] . "</td>";
                                        echo "<td>" . $row['ALIAS'] . "</td>";
                                        echo "<td>" . $row['FACE_MARKS'] . "</td>";
                                        echo "<td>" . $row['BIRTH_DATE'] . "</td>";
                                        echo "<td>" . $row['BIRTH_PLACE'] . "</td>";
                                        echo "<td>" . $row['SEX'] . "</td>";
                                        echo "<td>" . $row['CIVIL_STATUS'] . "</td>";
                                        echo "<td>" . $row['NATIONALITY'] . "</td>";
                                        echo "<td>" . $row['RELIGION_BELIEF'] . "</td>";
                                        echo "<td>" . $row['OCCUPATION'] . "</td>";
                                        echo "<td>" . $row['SPOUSE_NAME'] . "</td>";
                                        echo "<td>" . $row['SPOUSE_OCCUPATION'] . "</td>";
                                        echo "<td>" . $row['VOTER_STATUS'] . "</td>";
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
                        ?>
                    </div>
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
    </script>
    
    <script>
    function popupOpen() {
        var width = window.outerWidth*(4/5);
        var height = window.outerHeight*(3/4);
        var left = (screen.width/2)-(width/2);
        var top = (screen.height/2)-(height/2);
        var features =' width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', resizable=false';
        var addNewResident = window.open("resident-add.php", "window", features);
    }
    </script>
</body>

</html>