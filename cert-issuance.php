<?php
    session_start();
    
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: index.php");
        exit;
    }

    $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
    $brgy_name = fgets($myfile);
    $brgy_address = fgets($myfile);
    fclose($myfile);
    
    require_once "config.php";

    if(isset($_POST["issuance-report"])) {
        $sql = "SELECT * FROM issuance";

        if ($result = $mysqli->query($sql)) {
            if($result->num_rows > 0){ 
                $delimiter = ","; 
                $filename = "issuance-report-" . date('Ymd') . ".csv"; 
                 
                $f = fopen('php://memory', 'w'); 
                 
                $fields = array('TRANSACTION ID', 'PROCESSED BY'); 
                fputcsv($f, $fields, $delimiter); 
                 
                while($row = $result->fetch_assoc()){ 
                    $lineData = array($row['TRANSACTION_ID'], $row['PROCESSED_BY']); 
                    fputcsv($f, $lineData, $delimiter); 
                } 
                
                fseek($f, 0); 
                
                header('Content-Type: text/csv'); 
                header('Content-Disposition: attachment; filename="' . $filename . '";'); 
                
                fpassthru($f); 
            }
            die();
        } else {
            $mysqli -> error;
        }
    }

    if(isset($_POST["logs-report"])) {
        $sql = "SELECT * FROM logs";

        if ($result = $mysqli->query($sql)) {
            if($result->num_rows > 0){ 
                $delimiter = ","; 
                $filename = "logs-report-" . date('Ymd') . ".csv"; 
                 
                $f = fopen('php://memory', 'w'); 
                 
                $fields = array('TIMESTAMP', 'ACTION', 'PROCESSED BY'); 
                fputcsv($f, $fields, $delimiter); 
                 
                while($row = $result->fetch_assoc()){ 
                    $lineData = array($row['TIMESTAMP'], $row['ACTION'], $row['PROCESSED_BY']); 
                    fputcsv($f, $lineData, $delimiter); 
                } 
                
                fseek($f, 0); 
                
                header('Content-Type: text/csv'); 
                header('Content-Disposition: attachment; filename="' . $filename . '";'); 
                
                fpassthru($f); 
            }
            die();
        } else {
            $mysqli -> error;
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

                    <h3 class="nav-item ml-auto mr-0 my-auto">CERTIFICATE ISSUANCE</h3>
                </div>
            </nav>

            <div class="content">
                <div class="card-sm m-2">
                    <a class="btn btn-success" 
                        data-toggle="tooltip"
                        onclick="popupOpen('cert-residency.php')">
                        <i class="fa fa-plus"></i> 
                        Certificate of Residency
                    </a>
                </div>
                <div class="card-sm m-2">
                    <a class="btn btn-success" 
                        data-toggle="tooltip"
                        onclick="popupOpen('cert-clearance.php')">
                        <i class="fa fa-plus"></i> 
                        Barangay Clearance
                    </a>
                </div>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="card-sm m-2">
                        <button name="issuance-report" class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            Download Issuance Report
                        </button>
                    </div>
                    <div class="card-sm m-2">
                        <button name="logs-report" class="btn btn-primary">
                            <i class="fas fa-download"></i>
                            Download Logs Report
                        </button>
                    </div>
                </form>
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
        
        function popupOpen(link) {
            var width = window.outerWidth*(4/5);
            var height = window.outerHeight*(3/4);
            var left = (screen.width/2)-(width/2);
            var top = (screen.height/2)-(height/2);
            var features ='width=' + width + ', height=' + height + ', top=' + top + ', left=' + left + ', resizable=false';
            var popup = window.open(link, 'window', features);
        }
    </script>
</body>

</html>