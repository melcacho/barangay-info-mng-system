<?php
    session_start();
    date_default_timezone_set('Asia/Manila');
    
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: index.php");
        exit;
    }
    
    require_once "config.php";
    
    $myfile = fopen("assets/barangay-config/committee.txt", "r") or die("Unable to open file!");
    $a_committee = [];
    while(!feof($myfile)) {
        array_push($a_committee, fgets($myfile));
    }
    fclose($myfile);
    
    $a_civil_status = ["Single", "Married", "Separated", "Widowed"];
    $a_cs_value = ['SG', 'MR', 'SP', 'WD'];
    
    $res_id = $last_name = $first_name = $middle_name = $sex = $birth_date = $civil_status = $nationality = $purpose = "";

    if(isset($_POST["check"])) {
        $sql = "SELECT * FROM residents WHERE RESIDENT_ID = ".$_POST["res-id"]."";
        if ($result = $mysqli->query($sql)) {
            if($result->num_rows > 0) {
                if($row = $result->fetch_array()) {
                    $res_id = trim($_POST["res-id"]);
                    $last_name = $row["LNAME"];
                    $first_name = $row["FNAME"];
                    $middle_name = $row["MNAME"];
                    $sex = $row["SEX"];
                    $birth_date = $row["BIRTH_DATE"];
                    $civil_status = $row["CIVIL_STATUS"];
                    $nationality = $row["NATIONALITY"];
                }
            } else {
                $res_id_err = "Nonexistent Resident ID";
            }
        }
    }    

    if(isset($_POST["issue"])) {
        //Barangay Captain
        $sql = "SELECT * FROM admins WHERE POSITION = 0";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $last_name = $row["LNAME"][0].strtolower(substr($row["LNAME"], 1));
        $first_name_a = explode(" ", $row["FNAME"]);
        $first_name = '';
        foreach($first_name_a as $name) {
            $first_name = $first_name." ".$name[0].strtolower(substr($name, 1));
        }
        $middle_name = $row["MNAME"][0].". ";
        $brgy_captain = $first_name." ".$middle_name.$last_name;
    
        //Kagawads
        $sql = "SELECT * FROM admins WHERE POSITION = 1";
        $result = $mysqli->query($sql);
        $kagawad_a = [];
        $commitee_a = [];
        while ($row = $result->fetch_array()) {
            $last_name = $row["LNAME"][0].strtolower(substr($row["LNAME"], 1));
            $first_name_a = explode(" ", $row["FNAME"]);
            $first_name = '';
            foreach($first_name_a as $name) {
                $first_name = $first_name." ".$name[0].strtolower(substr($name, 1));
            }
            $middle_name = $row["MNAME"][0].". ";
            $full_name = $first_name." ".$middle_name.$last_name;
            array_push($kagawad_a, $full_name);
            array_push($commitee_a, $a_committee[$row['COMMITTEE']]);
        }
        
        //Secretary
        $sql = "SELECT * FROM admins WHERE POSITION = 2";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $last_name = $row["LNAME"][0].strtolower(substr($row["LNAME"], 1));
        $first_name_a = explode(" ", $row["FNAME"]);
        $first_name = '';
        foreach($first_name_a as $name) {
            $first_name = $first_name." ".$name[0].strtolower(substr($name, 1));
        }
        $middle_name = $row["MNAME"][0].". ";
        $secretary = $first_name." ".$middle_name.$last_name;
        
        //Treasurer
        $sql = "SELECT * FROM admins WHERE POSITION = 3";
        $result = $mysqli->query($sql);
        $row = $result->fetch_assoc();
        $last_name = $row["LNAME"][0].strtolower(substr($row["LNAME"], 1));
        $first_name_a = explode(" ", $row["FNAME"]);
        $first_name = '';
        foreach($first_name_a as $name) {
            $first_name = $first_name." ".$name[0].strtolower(substr($name, 1));
        }
        $middle_name = $row["MNAME"][0].". ";
        $treasurer = $first_name." ".$middle_name.$last_name;
        
        $sql = "SELECT * FROM residents WHERE RESIDENT_ID = ".$_POST["res-id"]."";
        if ($result = $mysqli->query($sql)) {
            if($result->num_rows > 0) {
                if($row = $result->fetch_array()) {
                    $res_id = trim($_POST["res-id"]);
                    $last_name = $row["LNAME"];
                    $first_name = $row["FNAME"];
                    $middle_name = $row["MNAME"];
                    $civil_status = $row["CIVIL_STATUS"];
                    $nationality = $row["NATIONALITY"];
                    $birth_date = $row["BIRTH_DATE"];
                }
            } else {
                $res_id_err = "Nonexistent Resident ID";
            }
        }

        $purpose = $_POST["purpose"];

        require('fpdf/fpdf.php');

        $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
        $brgy_name = fgets($myfile);
        $brgy_name = str_replace("\n","",$brgy_name);
        $brgy_address = fgets($myfile);
        fclose($myfile);
        
        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf -> AddPage();
        
        $pic_name = $first_name[0].$first_name[1].$last_name[-1].$last_name[-2].explode('-', $birth_date)[1];
        $pdf -> Image('assets/residents-profile/'.$pic_name.'.png',15,10,30,30);

        $pdf -> SetFont('Arial', '', 12);
        $pdf->Multicell(0,2,"Republic of the Philippines
        \nProvince of ".explode(", ", $brgy_address)[1]."
        \nCity / Municipality of ".explode(", ", $brgy_address)[1]."\n\n",0,'C'); 

        $pdf -> SetFont('Arial', 'B', 16);
        $pdf->Multicell(0,5,"Barangay ".$brgy_name,0,'C'); 

        $pdf -> SetFont('Arial', 'B', 24);
        $pdf -> setXY(20,40);
        $pdf->Multicell(0,15,"Barangay Clearance\n\n",0,'C'); 

        $kagawad = "";
        $i = 0;
        foreach($kagawad_a as $name) {
            $kagawad = $kagawad."\n\n".$name."\nKagawad\n".$commitee_a[$i++];
        }

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(5,60);
        $pdf-> Multicell(60,4,"\n".$brgy_captain."\nBarangay Captain".
        $kagawad."
        \n\n".$secretary."\nSecretary
        \n\n".$treasurer."\nTreasurer\n ",1,'C');

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(115,75);
        $pdf -> Cell(0,5,$first_name." ".$middle_name[0].". ".$last_name,0,'C'); //Resident Name

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(75,75);
        $pdf -> Cell(0,5,"This is to certify that ______________________________________,",0,'L');

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(97, 80);
        $pdf -> Cell(0,5,$a_civil_status[array_search($row['CIVIL_STATUS'], $a_cs_value)],0,'C'); //Civil Status

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(132, 80);
        $pdf -> Cell(0,5,$nationality,0,'C'); //Nationality

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(65, 80);
        $pdf -> Multicell(0,5,"is of legal age, ______________, ____________________ and a bonafied resident of Barangay".", ".$brgy_address.
        " and that has no derogatory records in the Barangay prior to the date of issuance of this certificate.\n\n",0    );

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setX(75);
        $pdf -> Multicell(0,5,"This certificate is issued in his/her request for:\n\n",0,'L');

        $pdf -> SetFont('Arial', 'B', 11);
        $pdf -> setX(75);
        $pdf -> Multicell(0,5,$purpose,0,'L');

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(96, 140);
        $pdf -> Cell(0,5,date("F j, Y"),0,'C'); //Date
        
        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(75, 140);
        $pdf -> Multicell(0,5,"Issued this ____________________",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(65,170);
        $pdf-> Multicell(0,4,"_________________\n         Signature",0,'L');

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(140,169);
        $pdf -> Cell(0,5,$brgy_captain,0,'C');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(140,170);
        $pdf-> Multicell(0,4,"__________________________\nBarangay Captain",0,'C');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(65,205);
        $pdf-> Multicell(0,6,"OR Number: __________________\nOR Date Issued: _______________",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(130,205);
        $pdf-> Multicell(0,6,"Cedulla Number: _________________\nCedulla Date Issued: ______________",0,'L');

        $transaction_id = "BC-".date("YmdHis");
        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(145, 250);
        $pdf -> Cell(0,5,$transaction_id,0,'C'); //Transaction ID

        $sql = "INSERT INTO issuance (TRANSACTION_ID, PROCESSED_BY) VALUES (?, ?)";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt -> bind_param("ss", $param_transaction, $param_admin);

            $param_transaction = $transaction_id;
            $param_admin = $_SESSION["admin-id"];

            if($stmt -> execute()) {
                $pdf->Output('I', $param_transaction.'.pdf');
            } else {
                echo '<script>
                alert("Push Sequence Error: Database Access Error");
                </script>';
            }
        } else {
            echo '<script>
            alert("Push Sequence Error: Database Parameters Error");
            </script>';
        }

        $sql = "INSERT INTO logs (TIMESTAMP, ACTION, PROCESSED_BY) VALUES (?, ?, ?)";
        $action = "Issued Barangay Clearance ".$res_id." (".$param_transaction.")";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt -> bind_param("sss", $param_timestamp, $param_action, $param_admin);

            $param_timestamp = date("Y-m-d H:i:s");
            $param_action = $action;
            $param_admin = $_SESSION["admin-id"];
            if($stmt -> execute()) {
                echo '<script>
                alert("'.$action.'");
                </script>';
            } else {
                echo '<script>
                alert("'.$stmt->error.'");
                </script>';
            }
        } else {
            echo '<script>
            alert("Push Sequence Error: Database Parameters Error");
            </script>';
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Barangay Clearance</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        html, body {
            height: 100%;
        }
    </style>
</head>

<body>
    <div class="container-fluid my-2 h-100">
        <h2>Issuing Barangay Clearance</h2>
        <div class="row cert">
            <div class="col-md-5 details">
                <h4>Resident Details</h4>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- resident-id -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Resident ID: </span>
                        <input type="text"
                            class="form-control col-md-8 <?php echo (!empty($res_id_err)) ? 'invalid' : ''; ?>"
                            name="res-id"
                            placeholder="<?php echo (!empty($res_id_err)) ? $res_id_err : ''; ?>"
                            maxlength="6"
                            onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;"
                            value="<?php echo $res_id; ?>"
                            required>
                    </div>

                    <button name="check" class="my-3 btn btn-primary">
                        Fetch Resident Information
                    </button>
                </form>

                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <!-- last-name -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Family Name: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="last-name"
                            value="<?php echo $last_name;?>"
                            disabled>
                    </div>

                    <!-- first-name -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">First Name: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="first-name"
                            value="<?php echo $first_name;?>"
                            disabled>
                    </div>

                    <!-- middle-name -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Middle Name: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="middle-name"
                            value="<?php echo $middle_name;?>"
                            disabled>
                    </div>

                    <!-- sex -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Sex: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="sex"
                            value="<?php echo $sex;?>"
                            disabled>
                    </div>

                    <!-- Birthdate -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Birthday: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="birth-date"
                            value="<?php echo $birth_date;?>"
                            disabled>
                    </div>

                    <!-- civil-status -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Civil Status: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="civil-status"
                            value="<?php echo (!empty($civil_status)) ? $a_civil_status[array_search($civil_status, $a_cs_value)] : ""?>"
                            disabled>
                    </div>

                    <!-- nationality -->
                    <div class="input-group row">
                        <span class="mb-0 mt-auto mx-1 col-md-4">Nationality: </span>
                        <input type="text"
                            class="form-control col-md-8"
                            name="nationality"
                            value="<?php echo $nationality;?>"
                            disabled>
                    </div>

                    <!--- Puspose --->
                    <div class="input-group row">
                        <span class="mx-1 col-md-4">Puspose: </span>
                            <textarea
                                name="purpose"  
                                rows="2"
                                class="form-control col-md-8 bg-white text-dark"
                                maxlength="50"
                                required></textarea>
                    </div>

                    <input name="res-id" value="<?php echo $res_id;?>" hidden>
                    <button name="issue" class="my-3 btn btn-success" 
                        <?php echo (empty($res_id) ? "disabled" : "")?>>
                        Issue Certificate
                    </button>
                </form>
            </div>

            <div class="col-md-7">
                <iframe  src="assets/barangay-config/cert-clearance-prev.pdf#view=FitH" width="100%" height="100%">
                </iframe>
            </div>
        </div>
    </div>
</body>
</html>
