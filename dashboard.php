<?php
    session_start();
    
    // if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    //     header("location: index.php");
    //     exit;
    // }

    require_once "config.php";
    
    $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
    $brgy_name = fgets($myfile);
    $brgy_address = fgets($myfile);
    fclose($myfile);

    $myfile = fopen("assets/barangay-config/area.txt", "r") or die("Unable to open file!");
    $a_area = [];
    while(!feof($myfile)) {
        array_push($a_area, fgets($myfile));
    }
    fclose($myfile);
    $a_area = array_filter($a_area, 'trim');

    $total_count = $male_count = 0;

    $sql = "SELECT * FROM residents";
    if ($result = $mysqli->query($sql)) {
        $total_count = $result->num_rows;
    }

    $sql = "SELECT * FROM residents WHERE SEX = 'M'";
    if ($result = $mysqli->query($sql)) {
        $male_count = $result->num_rows;
    }

    $sql = "SELECT * FROM residents WHERE SEX = 'F'";
    if ($result = $mysqli->query($sql)) {
        $female_count = $result->num_rows;
    }

    $sql = "SELECT * FROM residents WHERE VOTER_STATUS = 1";
    if ($result = $mysqli->query($sql)) {
        $voter_count = $result->num_rows;
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
                <li class="active">
                    <a>
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
                        Certificate Issuance
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
                <li class="text-danger">
                    <a href="logout.php">
                        <span class="icon"><i  class="fas fa-sign-out-alt"></i></span>
                        Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-----------------------------------------------------
            CONTENT
        ------------------------------------------------------->

        <div id="content">

            <nav id="topbar" class="navbar navbar-expand-lg">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse2" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <h3 class="nav-item ml-auto mr-0 my-auto">DASHBOARD</h3>
                </div>
            </nav>

            <div class="content">

                <div id="header">
                        <div class="d-flex justify-content-center">
                            <img src="assets/barangay-config/logo.png" alt="Logo" class="logo">
                        </div>
                        <div class="text-center">
                            <h1>Brgy. <?php echo $brgy_name;?></h1>
                            <h3><?php echo $brgy_address;?></h3>
                        </div>
                </div>

                <div class="container-xl row m-0 p-0 mx-auto">
                    <div class="col-xl-7">
                        <div class="bg-dark p-1">
                            <span class="text-white">Barangay Officials</span>
                        </div>
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
                                    echo '<table class="table table-bordered table-striped text-center bg-white">';
                                    echo "<thead>";
                                    echo "<tr>";
                                    echo "<th>Full Name</th>";
                                    echo "<th>Committee</th>";
                                    echo "<th>Position</th>";
                                    echo "</tr>";
                                    echo "</thead>";
                                    echo "<tbody>";
                                    while ($row = $result->fetch_array()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['LNAME'] . ', ' . $row['FNAME'] . ' ' . $row['MNAME'][0] . ".</td>";
                                        echo "<td>" . $a_committee[$row['COMMITTEE']] . "</td>";
                                        echo "<td>" . $a_position[$row['POSITION']] . "</td>";
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
                                echo "Oops! Something went wrong. Please try again later.";
                            }
                        ?>
                    </div>

                    <div class="col-xl-5">
                        <div class="container-xl row m-0 p-0 mx-auto">
                            <div class="card col-xl-12">
                                <div class="card-body">
                                    <h5 class="card-title">Total Registered Population</h5>
                                    <p class="card-text text-center"><?php echo $total_count?></p>
                                </div>
                            </div>
                        </div>
                        <div class="container-xl row m-0 p-0 mx-auto">
                            <div class="card col-xl-6">
                                <div class="card-body">
                                    <h5 class="card-title">Male</h5>
                                    <p class="card-text text-center"><?php echo $male_count?></p>
                                </div>
                            </div>
                            <div class="card col-xl-6">
                                <div class="card-body">
                                    <h5 class="card-title">Female</h5>
                                    <p class="card-text text-center"><?php echo $female_count?></p>
                                </div>
                            </div>
                        </div>
                        <div class="container-xl row m-0 p-0 mx-auto">
                            <div class="card col-xl-12">
                                <div class="card-body">
                                    <h5 class="card-title">Registered Voters</h5>
                                    <p class="card-text text-center"><?php echo $voter_count?></p>
                                </div>
                            </div>
                        </div>

                        <div class="line"></div>
                        
                        <div class="container-xl m-0 p-0 mx-auto">
                            <div class="bg-dark p-1">
                                <span class="text-white">Puroks / Area</span>
                            </div>
                            <table class="table table-striped text-center bg-white">
                                <thead>
                                    <tr>
                                        <th>Area / Purok</th>
                                        <th>Population</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $i = 0;
                                        foreach ($a_area as $text) {
                                            $sql = "SELECT * FROM residents WHERE AREA = '".$i++."'";
                                            if ($result = $mysqli->query($sql)) {
                                                $count = $result->num_rows;
                                            }
                                            echo "<tr>";
                                            echo "<td>".$text."</td>";
                                            echo "<td>".$count."</td>";
                                            echo "</tr>";
                                        }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-----------------------------------------------------
        Scripts
    ------------------------------------------------------->

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
</body>

</html>