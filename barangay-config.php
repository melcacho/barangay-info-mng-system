<?php
    define('MB', 1048576);
    $brgy_name = $brgy_address = "";
    $brgy_name_err = $brgy_address_err = $err = "";

    $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
    $brgy_name = fgets($myfile);
    $brgy_address = fgets($myfile);
    fclose($myfile);

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

    $myfile = fopen("assets/barangay-config/area.txt", "r") or die("Unable to open file!");
    $a_area = [];
    while(!feof($myfile)) {
        array_push($a_area, fgets($myfile));
    }
    fclose($myfile);

    $a_committee = array_filter($a_committee, 'trim');
    $a_position = array_filter($a_position, 'trim');
    $a_area = array_filter($a_area, 'trim');
    
    if(isset($_GET["type"])) {
        $type = trim($_GET["type"]);
        if(isset($_GET["id"])) {
            $id = trim($_GET["id"]);
        }
        if(isset($_GET["add"])) {
            $add = trim($_GET["add"]);
        }
    }

    if($_SERVER["REQUEST_METHOD"] == "POST") {

        if(isset($_POST["committee"]) && empty(trim($_POST["committee"]))) {
            $err = "Must Fill.";
        }

        if(isset($_POST["position"]) && empty(trim($_POST["position"]))) {
            $err = "Must Fill.";
        }

        if(isset($_POST["area"]) && empty(trim($_POST["area"]))) {
            $err = "Must Fill.";
        }

        if(isset($_POST["add"]) && !empty(trim($_POST["add"]))) {
            $add = 1;
        }

        if(isset($_POST["delete"])) {
            switch($_POST["type"]) {
                case 0: 
                    unset($a_committee[$_POST["id"]-1]);
                    $myfile = fopen("assets/barangay-config/committee.txt", "w") or die("Unable to open file!");
                    foreach ($a_committee as $text) {
                        fwrite($myfile, $text);
                    }
                    fclose($myfile);
                    break;
                case 1:
                    unset($a_position[$_POST["id"]-1]);
                    $myfile = fopen("assets/barangay-config/position.txt", "w") or die("Unable to open file!");
                    foreach ($a_position as $text) {
                        fwrite($myfile, $text);
                    }
                    fclose($myfile);
                    break;
                case 2:
                    unset($a_area[$_POST["id"]-1]);
                    $myfile = fopen("assets/barangay-config/area.txt", "w") or die("Unable to open file!");
                    foreach ($a_area as $text) {
                        fwrite($myfile, $text);
                    }
                    fclose($myfile);
                    break;
            }
        }

        if(empty($err)) {
            if(isset($_POST["committee"])) {
                if(!isset($add)) {
                    $ext = (count($a_committee)==(trim($_POST["id"]))) ? '' : "\n";
                    $a_committee = array_replace($a_committee, array(trim($_POST["id"])-1 => trim($_POST["committee"]).$ext));
                }
                $myfile = fopen("assets/barangay-config/committee.txt", "w") or die("Unable to open file!");
                foreach ($a_committee as $text) {
                    fwrite($myfile, $text);
                }
                if(isset($add)) {
                    fwrite($myfile, trim($_POST["committee"]));
                    array_push($a_committee, trim($_POST["committee"]));
                }
                fclose($myfile);
            } elseif(isset($_POST["position"]) && !empty(trim($_POST["position"]))) {
                if(!isset($add)) {
                    $ext = (count($a_position)==(trim($_POST["id"]))) ? '' : "\n";
                    $a_position = array_replace($a_position, array(trim($_POST["id"])-1 => trim($_POST["position"]).$ext));
                }
                $myfile = fopen("assets/barangay-config/position.txt", "w") or die("Unable to open file!");
                foreach ($a_position as $text) {
                    fwrite($myfile, $text);
                }
                if(isset($add)) {
                    fwrite($myfile, trim($_POST["position"]));
                    array_push($a_position, trim($_POST["position"]));
                }
                fclose($myfile);
            } elseif(isset($_POST["area"]) && !empty(trim($_POST["area"]))) {
                if(!isset($add)) {
                    $ext = (count($a_area)==(trim($_POST["id"]))) ? '' : "\n";
                    $a_area = array_replace($a_area, array(trim($_POST["id"])-1 => trim($_POST["area"]).$ext));
                }
                $myfile = fopen("assets/barangay-config/area.txt", "w") or die("Unable to open file!");
                foreach ($a_area as $text) {
                    fwrite($myfile, $text);
                }
                if(isset($add)) {
                    fwrite($myfile, trim($_POST["area"]));
                    array_push($a_area, trim($_POST["area"]));
                }
                fclose($myfile);
            }
        }

        if(isset($_FILES["logo"]["name"])) {
            $target_dir = "assets/barangay-config/";
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo(basename($_FILES["logo"]["name"]),PATHINFO_EXTENSION));
            $error_msg = "";
            
            if($_FILES["logo"]["size"] != 0) {
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES["logo"]["tmp_name"]);
                if($check === false) {
                    $error_msg = $error_msg.'\nFile is not an image.';
                    $uploadOk = 0;
                }

                // Check file size
                if ($_FILES["logo"]["size"] > 2*MB) {
                    $error_msg = $error_msg.'\nSorry, your file is too large.';
                    $uploadOk = 0;
                }

                // Allow certain file formats
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                    $error_msg = $error_msg.'\nSorry, only JPG, JPEG, PNG & GIF files are allowed.';
                    $uploadOk = 0;
                }

                // Check if $uploadOk is set to 0 by an error
                if ($uploadOk == 0) {
                    echo "<script>
                    alert('Logo not uploaded. ".$error_msg."');
                    </script>";
                    // if everything is ok, try to upload file
                } else {
                    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_dir . 'logo.png')) {
                        echo "<script>
                        alert('Logo has been uploaded.');
                        </script>";
                        header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1.
                        header("Pragma: no-cache"); // HTTP 1.0.
                        header("Expires: 0");
                        header("Refresh:0");
                    } else {
                        echo "<script>
                        alert('Sorry, there was an error uploading your file.');
                        </script>";
                    }
                }
            }            

            if(empty(trim($_POST["brgy-name"]))) {
                $brgy_name_err = "Must Fill";
            } else {
                $brgy_name = trim($_POST["brgy-name"])."\n";
            }

            if(empty(trim($_POST["brgy-address"]))) {
                $brgy_address_err = "Must Fill";
            } else {
                $brgy_address = trim($_POST["brgy-address"]);
            }

            if(empty($brgy_name_err) && empty($brgy_address_err)) {
                $myfile = fopen("assets/barangay-config/brgy-details.txt", "w") or die("Unable to open file!");
                fwrite($myfile, $brgy_name);
                fwrite($myfile, $brgy_address);
                fclose($myfile);
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
                <li class="active">
                    <a>
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

                    <h3 class="nav-item ml-auto mr-0 my-auto">BARANGAY CONFIGURATION</h3>
                </div>
            </nav>

            <div class="content">
                <div class="container-xl">
                    <h3 class="bg-dark text-white m-0 p-1">Main Configuration</h3>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" 
                    enctype="multipart/form-data" class="bg-white">
                        <div class="form-group row">
                            <div class="col-xl-6">
                                <div class="input-group my-1">
                                    <span class="mb-0 mt-auto mx-1">Barangay Name: </span>
                                    <input
                                        type="text"
                                        class="form-control <?php echo (!empty($brgy_name)) ? 'invalid' : ''; ?>"
                                        name="brgy-name"
                                        placeholder="<?php echo (!empty($brgy_name_err)) ? $brgy_name_err : ''; ?>"
                                        value="<?php echo $brgy_name; ?>"
                                    >
                                </div>
                                <div class="input-group my-1">
                                    <span class="mb-0 mt-auto mx-1">Barangay Address: </span>
                                    <input
                                        type="text"
                                        class="form-control <?php echo (!empty($brgy_address)) ? 'invalid' : ''; ?>"
                                        name="brgy-address"
                                        placeholder="<?php echo (!empty($brgy_address_err)) ? $brgy_address_err : ''; ?>"
                                        value="<?php echo $brgy_address; ?>"
                                    >
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="input-group my-1">
                                    <span class="mb-0 mt-auto mx-1">Barangay Logo: </span>
                                    <input type="file" class="form-control" id="logo" name="logo">
                                </div>
                                
                                <div class="text-right m-1">
                                    <button type="submit" class="btn btn-success">Apply Changes</button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="container-xl row m-0 p-0 mx-auto">
                    <div class="col-xl-6">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr class='bg-dark'>
                                    <th>
                                        <div class="row">
                                            <div class="col-xl-10">Committee</div>
                                            <div class="col-xl-2">
                                                <a href="?type=0&add=1" class="action" title="Update Record" data-toggle="tooltip">
                                                    <span class="fa fa-plus"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $index = 1;
                                    foreach ($a_committee as $text) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo '<div class="row"><div class="col-xl-10">'.$text.'</div>';
                                        echo '<div class="col-xl-2"><a href="?id='.$index.'&type=0" class="mr-3 action" title="Update Record" data-toggle="tooltip"><span class="fas fa-pencil-alt"></span></a>';
                                        echo '<a href="?id='.$index++.'&type=0&add=0" class="action" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                        <table class="table table-striped text-center">
                            <thead>
                                <tr class='bg-dark'>
                                    <th>
                                        <div class="row">
                                            <div class="col-xl-10">Position</div>
                                            <div class="col-xl-2">
                                                <a href="?type=1&add=1" class="action" title="Update Record" data-toggle="tooltip">
                                                    <span class="fa fa-plus"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $index = 1;
                                    foreach ($a_position as $text) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo '<div class="row"><div class="col-xl-10">'.$text.'</div>';
                                        echo '<div class="col-xl-2"><a href="?id='.$index.'&type=1" class="mr-3 action" title="Update Record" data-toggle="tooltip"><span class="fas fa-pencil-alt"></span></a>';
                                        echo '<a href="?id='.$index++.'&type=1&add=0" class="action" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-xl-6">
                        <table class="table table-striped text-center">
                            <thead>
                                <tr class='bg-dark'>
                                    <th>
                                        <div class="row">
                                            <div class="col-xl-10">Area / Purok</div>
                                            <div class="col-xl-2">
                                                <a href="?type=2&add=1" class="action" title="Update Record" data-toggle="tooltip">
                                                    <span class="fa fa-plus"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $index = 1;
                                    foreach ($a_area as $text) {
                                        echo "<tr>";
                                        echo "<td>";
                                        echo '<div class="row"><div class="col-xl-10">'.$text.'</div>';
                                        echo '<div class="col-xl-2"><a href="?id='.$index.'&type=2" class="mr-3 action" title="Update Record" data-toggle="tooltip"><span class="fas fa-pencil-alt"></span></a>';
                                        echo '<a href="?id='.$index++.'&type=2&add=0" class="action" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</td>";
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

    <!-----------------------------------------------------
        Modal
    ------------------------------------------------------->

    <div class="modal fade" id="update_modal" tabindex="-1" role="dialog" aria-labelledby="create" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md " role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        <?php 
                        echo isset($add) ? 'Add ' : '';
                        switch($type) {
                            case 0: echo "Committee"; break;
                            case 1: echo "Position"; break;
                            case 2: echo "Area";
                        }?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="modal-body mx-auto">
                            <div class="input-group">
                                <span class="mb-0 mt-auto mx-1">Name: </span>
                                <input
                                    type="text"
                                    class="form-control <?php echo (!empty($err)) ? 'invalid' : ''; ?>"
                                    name="<?php 
                                        switch($type) {
                                            case 0: echo "committee"; break;
                                            case 1: echo "position"; break;
                                            case 2: echo "area";
                                        }?>"
                                    placeholder="<?php 
                                        if(!empty($err)) {
                                            echo $err;
                                        } elseif(!isset($add)) {
                                            switch($type) {
                                                case 0: echo $a_committee[$id-1]; break;
                                                case 1: echo $a_position[$id-1]; break;
                                                case 2: echo $a_area[$id-1];
                                            }
                                        }?>"
                                    value="<?php 
                                        if(!isset($add)) {
                                            switch($type) {
                                                case 0: echo $a_committee[$id-1]; break;
                                                case 1: echo $a_position[$id-1]; break;
                                                case 2: echo $a_area[$id-1];
                                            }
                                        }?>"
                                >
                                <input type="hidden" 
                                name="<?php echo isset($add) ? "add" : "id"?>" 
                                value="<?php echo isset($add) ? $add : $id?>"/>
                            </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
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
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="modal-body mx-auto">
                    <h4>Delete <?php
                        switch($type) {
                            case 0: echo "Committee"; break;
                            case 1: echo "Position"; break;
                            case 2: echo "Area";
                        }?>: 
                        <?php 
                        switch($type) {
                            case 0: echo $a_committee[$id-1]; break;
                            case 1: echo $a_position[$id-1]; break;
                            case 2: echo $a_area[$id-1];
                        }?>?
                    </h4>
                    
                    <input type="hidden" name="type" value="<?php echo $type?>" />
                    <input type="hidden" name="delete" value="1" />
                    <input type="hidden" name="id" value="<?php echo $id?>" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
                </form>
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

            <?php 
            if(isset($add) && $add==0) {
                echo '$(document).ready(function(){
                $(\'#delete_modal\').modal(\'show\');});';
            } elseif(isset($type)) {
                echo '$(document).ready(function(){
                $(\'#update_modal\').modal(\'show\');});';
            }
            ?>
        });
    </script>
</body>

</html>