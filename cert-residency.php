<?php
    require_once "config.php";

    $a_civil_status = ["Single", "Married", "Separated", "Widowed"];
    $a_cs_value = ['SG', 'MR', 'SP', 'WD'];
    
    $res_id = $last_name = $first_name = $middle_name = $sex = $birth_date = "";

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
                }
            } else {
                $res_id_err = "Nonexistent Resident ID";
            }
        }
    }

    if(isset($_POST["issue"])) {
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
                }
            } else {
                $res_id_err = "Nonexistent Resident ID";
            }
        }

        require('fpdf/fpdf.php');
        
        $myfile = fopen("assets/barangay-config/brgy-details.txt", "r") or die("Unable to open file!");
        $brgy_name = fgets($myfile);
        $brgy_name = str_replace("\n","",$brgy_name);
        $brgy_address = fgets($myfile);
        fclose($myfile);

        $pdf = new FPDF('P', 'mm', 'Letter');
        $pdf->AddPage();
        $pdf -> SetFont('Arial', '', 12);
        $pdf->Multicell(0,2,"Republic of the Philippines
        \nProvince of Dummy Province
        \nCity / Municipality of Dummy City\n\n",0,'C'); 

        $pdf -> SetFont('Arial', 'B', 16);
        $pdf->Multicell(0,5,"Barangay ".$brgy_name,0,'C'); 

        $pdf -> SetFont('Arial', 'B', 24);
        $pdf -> setY(40);
        $pdf -> Multicell(0,10,"Certificate of Residency",0,'C'); 

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(70, 70);
        $pdf -> Cell(0,5,$first_name." ".$middle_name[0].". ".$last_name,0,'C'); //Resident Name
        
        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setXY(20, 70);
        $pdf -> Multicell(0,5,"This is to certify that _____________________________________________________________,",0,'L');
        
        $diff = date_diff(date_create($birth_date), date_create(date("Y-m-d")));
        $age = $diff->format('%y');
        if($age < 18) {
            $age_group = "Minority";
        } else {
            $age_group = "Legal";
        }

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(18, 75);
        $pdf -> Cell(0,5,$age_group,0,'C'); //Age

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(51, 75);
        $pdf -> Cell(0,5,$a_civil_status[array_search($row['CIVIL_STATUS'], $a_cs_value)],0,'C'); //Civil Status

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(88, 75);
        $pdf -> Cell(0,5,$nationality,0,'C'); //Nationality
        
        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setY(75);
        $pdf -> Multicell(0,5," of __________ age, ______________, ____________________ Citizen, whose specimen signature appears below is a PERMANENT RESIDENT of this Barangay".", ".$brgy_address.".\n\n\n",0,'L');

        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setX(20);
        $pdf -> Multicell(0,5,"Based on records on this office, he / she has been residing at Barangay".", ".$brgy_address.".\n\n\n",0,'L');

        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setX(20);
        $pdf -> Multicell(0,5,"This CERTIFICATION is being issued upon the request of the above-named person for\n",0,'L');

        $pdf -> SetFont('Arial', '', 12);
        $pdf -> Multicell(0,5,"whatever legal purpose it may serve.\n\n\n",0,'L');

        $pdf -> SetFont('Arial', 'B', 12);
        $pdf -> setXY(45, 130);
        $pdf -> Cell(0,5,date("F j, Y"),0,'C'); //Date

        $pdf -> setY(130);
        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setX(20);
        $pdf -> Multicell(0,5,"Issued this ____________________ at Barangay ".$brgy_name.", ".$brgy_address.".",0,'L');

        $pdf -> SetFont('Arial', '', 12);
        $pdf -> Multicell(0,5,"\n\n\n\n\n\nSpecimen Signature
        \n\n_____________________",0,'L');

        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setX(120);
        $pdf -> Multicell(0,5,"\n\n\n\n\n\n Barangay ".$brgy_name,0,'C');

        $pdf -> SetFont('Arial', '', 12);
        $pdf -> setX(120);
        $pdf -> Multicell(0,5,"\n\n_____________________
        \nBarangay Captain",0,'C');

        $pdf->Output('I','Certificate of Residency.pdf'); 
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add New Resident</title>
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
    <div class="container-fluid py-2 h-100">
    <h2>Issuing Certificate of Residency</h2>
        <div class="row cert">
            <div class="col-md-5 details p-1">
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

                    <button name="issue" class="my-3 btn btn-success" 
                        <?php echo (empty($res_id) ? "disabled" : "")?>>
                        Issue Certificate
                    </button>
                </form>
            </div>

            <div class="col-md-7">
                <iframe  src="assets/barangay-config/cert-residency-prev.pdf#view=FitH" width="100%" height="100%">
                </iframe>
            </div>
        </div>
    </div>
</body>
</html>
