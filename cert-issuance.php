<?php
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
                <li>
                    <a href="dashboard.php">
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
                <li class="active">
                    <a>
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
            CONTENT
        ------------------------------------------------------->

        <div id="content">

            <nav id="topbar" class="navbar navbar-expand-lg">
                <div class="container-fluid">

                    <button type="button" id="sidebarCollapse2" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <h3 class="nav-item ml-auto mr-0 my-auto">CERTIFICATE ISSUANCE</h3>
                </div>
            </nav>

            <div class="content">
                
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