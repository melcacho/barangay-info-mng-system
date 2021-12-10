<?php 
    include_once('config.php');
    date_default_timezone_set('Asia/Manila');

    $a_civil_status = ["Single", "Married", "Separated", "Widowed"];
    $a_cs_value = ['SG', 'MR', 'SP', 'WD'];
    $a_sector= ["Private", "Public", "Government", "Unemployed", "Out of School Youth (OSY)", "Out of School Children (OSC)", "Person With Disability (PWD)",
    "Senior Citizen (SC)", "Overseas Filipino Worker (OFW)", "Solo Parent", "Indigenous People (IP)", "Others"];
    $a_st_value = ['PRV', 'PUB', 'GOV', 'UEP', 'OSY', 'OSC', 'PWD', 'SEN', 'OFW', 'SPA', 'IDP', 'OTH'];
    
    $myfile = fopen("assets/barangay-config/area.txt", "r") or die("Unable to open file!");
    $a_area = [];
    while(!feof($myfile)) {
        array_push($a_area, fgets($myfile));
    }
    fclose($myfile);
    $a_area = array_filter($a_area, 'trim');

    $last_name = $first_name = $middle_name = $birth_date = $civil_status = $sex = $alias = $voter_status = 
    $voter_active = $area = $occupation = $sector = $nationality = $belief = $birth_place = $face_marks = 
    $spouse_name = $spouse_occupation = $contact_number_one = $contact_number_two = $email_one = $email_two = 
    $resident_type = $resident_status = "";

    $name_err = $spouse_name_err = $contact_number_one_err = $contact_number_two_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        //validate name
        if (!preg_match('/^[a-zA-Z ]+$/', trim($_POST["first-name"])) ||
        !preg_match('/^[a-zA-Z ]+$/', trim($_POST["last-name"])) ||
        (!empty(trim($_POST["middle-name"])) && !preg_match('/^[a-zA-Z ]+$/', trim($_POST["middle-name"])))) {
            $name_err = "Must only contain letters";
        } else {
            $first_name = trim($_POST["first-name"]);
            $middle_name = trim($_POST["middle-name"]);
            $last_name = trim($_POST["last-name"]);
        }
        
        //validate spouse name
        if(isset($_POST["spouse-name"])) {
            if (!preg_match('/^[a-zA-Z ]+$/', trim($_POST["spouse-name"]))) {
                $spouse_name_err = "Must only contain letters";
            } else {
                $spouse_name = trim($_POST["spouse-name"]);
            }
        }

        //validate contact number one
        if(strlen(trim($_POST["contact-number-one"])) < 11) {
            $contact_number_one_err = "Must be 11 digits";
        } elseif(substr($_POST["contact-number-one"], 0, 2) != "09") {
            $contact_number_one_err = "Must start with 09";
        } else {
            $contact_number_one = trim($_POST["contact-number-one"]);
        }

        //validate contact number two
        if(isset($_POST["contact-number-two"])) {
            if(strlen(trim($_POST["contact-number-two"])) < 11) {
                $contact_number_two_err = "Must be 11 digits";
            } elseif(substr($_POST["contact-number-two"], 0, 2) != "09") {
                $contact_number_two_err = "Must start with 09";
            } else {
                $contact_number_two = trim($_POST["contact-number-two"]);
            }
        }

        if(isset($_POST["occupation"])) {
            $occupation = trim($_POST["occupation"]);
        }

        if(isset($_POST["spouse-occupation"])) {
            $spouse_occupation = trim($_POST["spouse-occupation"]);
        }

        if(isset($_POST["voter-active"])) {
            $voter_active = trim($_POST["voter-active"]);
        }
        
        $birth_date = trim($_POST["birth-date"]);
        $civil_status = trim($_POST["civil-status"]);
        $sex = trim($_POST["sex"]);
        $alias = trim($_POST["alias"]);
        $voter_status = trim($_POST["voter-status"]);
        $area = trim($_POST["area"]);
        $sector = trim($_POST["sector"]);
        $nationality = trim($_POST["nationality"]);
        $belief = trim($_POST["belief"]);
        $birth_place = trim($_POST["birth-place"]);
        $face_marks = trim($_POST["face-marks"]);
        $voter_status = trim($_POST["voter-status"]);
        $email_one = trim($_POST["email-one"]);
        $email_two = trim($_POST["email-two"]);
        $resident_type = trim($_POST["resident-type"]);
        $resident_status = trim($_POST["resident-status"]);
        
        if(empty($name_err) && empty($spouse_name_err) && empty($contact_number_one_err) && 
        empty($contact_number_two_err)) {
            echo '<script>
            alert("IN");
            </script>';
            $sql = "INSERT INTO residents (LNAME, FNAME, MNAME, ALIAS, FACE_MARKS, BIRTH_DATE, BIRTH_PLACE, 
            SEX, CIVIL_STATUS, NATIONALITY, BELIEF, OCCUPATION, SECTOR, SPOUSE_NAME, SPOUSE_OCCUPATION, 
            VOTER_STATUS, VOTER_ACTIVE, CONTACT_ONE, CONTACT_TWO, EMAIL_ONE, EMAIL_TWO, RES_TYPE, RES_STATUS, 
            DATE_TIME_REG, PROCESSED_BY) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            if ($stmt = $mysqli->prepare($sql)) {
                $stmt->bind_param("sssssssssssssssssssssssss", $param_lname, $param_fname, $param_mname, $param_alias, 
                $param_face_marks, $param_bdate, $param_bplace, $param_sex, $param_cs, $param_nationality, 
                $param_belief, $param_occupation, $param_sector, $param_spousen, $param_spouseo, $param_voters, 
                $param_votera, $param_contactone, $param_conacttwo, $param_emailone, $param_emailtwo, $param_restype, 
                $param_resstat, $param_date, $param_procby);

                $param_lname = $last_name;
                $param_fname = $first_name;
                $param_mname = $middle_name;
                $param_alias = $alias;
                $param_face_marks = $face_marks;
                $param_bdate = $birth_date;
                $param_bplace = $birth_place;
                $param_sex = $sex;
                $param_cs = $civil_status;
                $param_nationality = $nationality;
                $param_belief = $belief;
                $param_occupation = $occupation;
                $param_sector = $sector;
                $param_spousen = $spouse_name;
                $param_spouseo = $spouse_occupation;
                $param_voters = $voter_status;
                $param_votera = $voter_active;
                $param_contactone = $contact_number_one;
                $param_conacttwo = $contact_number_two;
                $param_emailone = $email_one;
                $param_emailtwo = $email_two;
                $param_restype = $resident_type;
                $param_resstat = $resident_status;
                $param_date = date("Y/m/d").', '.date("h:i:sa");
                $param_procby = 1;

                if ($stmt->execute()) {
                    echo '<script>
                    alert("Resident Registered");
                    window.close();
                    </script>';
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
        }
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
</head>

<body>
    <div class="container-fluid my-2">
        <h2>New Resident Registration Form</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">   
                <div class="col-md-4"> 
                    <img src="assets/barangay-config/sumin.png" class="image-view">
                    <div class="row mt-2">
                        <div class="col-sm-6">
                            <a type = "submit" class="btn btn-block btn-primary">
                            <i class="fa fa-folder"></i> 
                                Browse
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a type = "submit" class="btn btn-block btn-primary">
                            <i class="fa fa-folder"></i> 
                                Camera
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <h3>Personal Information</h3>
                    <!-- INPUT ROW -->
                    <div class="form-group row">
                        <!-- last-name -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Last Name: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="last-name"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $last_name; ?>"
                                maxlength="20"
                                required>
                        </div>
                        <!-- birthdate -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Birthdate: </span>
                            <input id="birth-date"
                                type="date"
                                name="birth-date"
                                class="form-control <?php echo (!empty($birth_date_err)) ? 'is-invalid' : ''; ?>"
                                placeholder="Birth Date"
                                required>
                        </div>
                    </div>
                    <!-- INPUT ROW END-->
                    <div class="form-group row">
                        <!-- first-name -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">First Name: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="first-name"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $first_name; ?>"
                                maxlength="50"
                                required>
                        </div>
                        <!-- civil-status -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Civil Status: </span>
                            <select id="civil-status"
                                class="form-control" 
                                name="civil-status"
                                required>
                                <option value='' hidden selected>Select</option>
                                <?php
                                    $i = 0;
                                    foreach($a_civil_status as $value) {
                                        echo '<option value="'.$a_cs_value[$i].'" '.(($a_cs_value[$i++] == $civil_status) ? 'selected': '').'>'.$value.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group row">
                        <!-- middle-name -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Middle Name: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="middle-name"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $middle_name; ?>"
                                maxlength="20"
                                required>
                        </div>
                        <!-- sex -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Sex: </span>
                            <select class="form-control" 
                                name="sex"
                                required>
                                <option value='' hidden selected>Select</option>
                                <option value="M" <?php echo ($sex == "M") ? 'selected' : ''?>>Male</option>
                                <option value="F" <?php echo ($sex == "F") ? 'selected' : ''?>>Female</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- alias -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Alias: </span>
                            <input
                                type="text"
                                class="form-control"
                                name="alias"
                                value="<?php echo $alias; ?>"
                                maxlength="20">
                        </div>
                        <!-- voter status -->
                        <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Voter Status: </span>
                            <select id="voter-status"
                                class="form-control"
                                name="voter-status"
                                disabled
                                required>
                                <option value='' hidden selected>Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            <select id="voter-active"
                                class="form-control"
                                name="voter-active"
                                disabled
                                required>
                                <option value='' hidden selected>Select</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!---area--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Purok: </span>
                            <select class="form-control" 
                            aria-label="Default select example" 
                            name="area"
                            required>
                                <option value='' hidden selected>Select</option>
                                <?php
                                    $i = 0;
                                    foreach($a_area as $value) {
                                        echo '<option value="'.$i.'" '.(($area == $i++) ? 'selected': '').'>'.$value.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <!-- nationality -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Nationality: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($nationality_err)) ? 'invalid' : ''; ?>"
                                name="nationality"
                                placeholder="<?php echo (!empty($nationality_err)) ? $nationality_err : ''; ?>"
                                value="<?php echo $nationality; ?>"
                                maxlength="20"
                                required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!---Occupation--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Occupation: </span>
                            <input id="occupation"
                                type="text"
                                class="form-control <?php echo (!empty($occupation_err)) ? 'invalid' : ''; ?>"
                                name="occupation"
                                placeholder="<?php echo (!empty($occupation_err)) ? $occupation_err : ''; ?>"
                                value="<?php echo $occupation; ?>"
                                maxlength="20"
                                required>
                        </div>
                        <!---Sector--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Sector: </span>
                            <select id="sector"
                                class="form-control" 
                                name="sector"
                                required>
                                <option value='' hidden selected>Select</option>
                                <?php
                                    $i = 0;
                                    foreach($a_sector as $value) {
                                        echo '<option value="'.$a_st_value[$i++].'">'.$value.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- religion/belief -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Religion / Belief: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($belief_err)) ? 'invalid' : ''; ?>"
                                name="belief"
                                placeholder="<?php echo (!empty($belief_err)) ? $belief_err : ''; ?>"
                                value="<?php echo $belief;?>"
                                maxlength="20"
                                required>
                        </div>
                    </div>    

                    <div class="form-group row">
                        <!---Place of Birth--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1 w-100">Place of Birth: </span>
                            <textarea id="subject" 
                                name="birth-place"  
                                style="height:4rem"
                                class="w-100"
                                maxlength="50"
                                required><?php echo $birth_place;?></textarea>
                        </div>
                        <!---Face Marks--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1 w-100">Face Marks: </span>
                            <textarea id="subject" 
                                name="face-marks" 
                                style="height:4rem"
                                class="w-100"
                                maxlength="50"><?php echo $face_marks;?></textarea>
                        </div>
                    </div>

                    <hr>

                    <div class="form-group row">
                        <!-- spouse-name -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Spouse's Name: </span>
                            <input id="spouse-name"
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="spouse-name"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $spouse_name; ?>"
                                maxlength="50"
                                disabled
                                required>
                        </div>
                        <!-- spouse-occupation -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Spouse's Occupation: </span>
                            <input id="spouse-occupation"
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="spouse-occupation"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $spouse_occupation; ?>"
                                maxlength="20"
                                disabled
                                required>
                        </div>
                    </div>    
                </div>
            </div>
            <hr>

            <div>
                <h3>Contact Details</h3>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <span>Contact Number:</span>
                        <input type="text"
                            class="form-control <?php echo (!empty($contact_number_one_err)) ? 'invalid' : ''; ?>"
                            name="contact-number-one"
                            placeholder="<?php echo (!empty($contact_number_one_err)) ? $contact_number_one_err : ''; ?>"
                            maxlength="11"
                            onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;"
                            value="<?php echo $contact_number_one; ?>">
                        <input type="text"
                            class="form-control <?php echo (!empty($contact_number_two_err)) ? 'invalid' : ''; ?>"
                            name="contact-number-two"
                            placeholder="<?php echo (!empty($contact_number_two_err)) ? $contact_number_two_err : ''; ?>"
                            maxlength="11"
                            onkeypress="if(isNaN(String.fromCharCode(event.keyCode))) return false;"
                            value="<?php echo $contact_number_two; ?>">
                    </div>
                    <div class="col-md-4">
                        <span>Email Address:</span>
                        <input type="text"
                            class="form-control"
                            name="email-one"
                            value="<?php echo $email_one; ?>"
                            maxlength="50">
                        <input type="text"
                            class="form-control"
                            name="email-two"
                            value="<?php echo $email_two; ?>"
                            maxlength="50">
                    </div>
                </div>
            </div>
            <hr>

            <div>
                <h3>Contact Details</h3>
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <span class="mb-0 mt-auto mx-1">Resident Type: </span>
                        <select class="form-control"
                            name="resident-type"
                            required>
                            <option value='' hidden selected>Select</option>
                            <option value="N" <?php echo ($resident_type == "N") ? 'selected' : ''?>>Native</option>
                            <option value="R" <?php echo ($resident_type == "R") ? 'selected' : ''?>>Rentee</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <span class="mb-0 mt-auto mx-1">Resident Status: </span>
                        <select class="form-control"
                            name="resident-status"
                            required>
                            <option value='' hidden selected>Select</option>
                            <option value="A" <?php echo ($resident_status == "A") ? 'selected' : ''?>>Active</option>
                            <option value="I" <?php echo ($resident_status == "I") ? 'selected' : ''?>>Inactive</option>
                            <option value="D" <?php echo ($resident_status == "D") ? 'selected' : ''?>>Deceased</option>
                        </select>
                    </div>
                </div>
            </div>
            <hr>

            <div class="text-right">
                <button type="button" class="btn btn-secondary" onclick="window.close()">Close</button>
                <button type="submit" class="btn btn-success">Save changes</button>
            </div>
        </form>
    </div>
    
    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sector').on('change', function () {
                if ($(this).val() != "UEP") {
                    $('#occupation').prop('disabled', false);
                } else {
                    $('#occupation').prop('disabled', true).val('').removeAttr("alt");;
                }
            });

            $('#civil-status').on('change', function () {
                if ($(this).val() == "MR") {
                    $('#spouse-name').prop('disabled', false);
                    $('#spouse-occupation').prop('disabled', false);
                } else {
                    $('#spouse-name').prop('disabled', true).val('').removeAttr("alt");;
                    $('#spouse-occupation').prop('disabled', true).val('').removeAttr("alt");;
                }
            });

            $('#voter-status').on('change', function () {
                if ($(this).val() == '1') {
                    $('#voter-active').prop('disabled', false);
                } else {
                    $('#voter-active').prop('disabled', true).val('').removeAttr("alt");
                }
            });

            $('#birth-date').on('change', function () {
                var today = new Date();
                var birthDate = new Date($(this).val());
                var year = birthDate.getFullYear()*1;
                if(year > 1900) {
                    var age = today.getFullYear() - birthDate.getFullYear();
                    var m = today.getMonth() - birthDate.getMonth();
                    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    
                    if(age >= 18) {
                    $('#voter-status').prop('disabled', false);
                    } else {
                        $('#voter-status').prop('disabled', true).val('').removeAttr("alt");
                    }
                }
            });
        });
    </script>
</body>
</html>