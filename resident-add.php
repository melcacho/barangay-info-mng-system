<?php include_once('config.php');

    if (isset($_POST['button'])) {
        $familyName = $_POST['familyName'];
        $firstName = $_POST['firstName'];
        $middleName = $_POST['middleName'];
        $alias = $_POST['alias'];
        $facemarks = $_POST['facemarks'];
        $birthMonth = $_POST['month'];
        $birthDay = $_POST['day'];
        $birthYear = $_POST['year'];
        $birthPlace = $_POST['birthplace'];
        $sex = $_POST['sex'];
        $civilStatus = $_POST['civilStatus'];
        $nationality = $_POST['nationality'];
        $occupation = $_POST['occupation'];
        $sector = $_POST['sector'];
        $spouseName = $_POST['spouseName'];
        $spouseOccupation = $_POST['spouseOccupation'];
        $voterStatus = $_POST['voterStatus'];
        $birthDate = $birthMonth."/".$birthDay."/".$birthYear;

        $sql = "INSERT INTO residents (LNAME, FNAME, MNAME, ALIAS, 
                                    FACE_MARKS, BIRTH_DATE, BIRTH_PLACE, SEX, 
                                    CIVIL_STATUS, NATIONALITY, RELIGION_BELIEF, OCCUPATION, 
                                    SPOUSE_NAME, SPOUSE_OCCUPATION, VOTER_STATUS)

                                VALUES('$familyName', '$firstName', '$middleName', '$alias', 
                                        '$facemarks', '$birthDate','$birthPlace', '$sex',
                                        '$civilStatus', '$nationality', '$occupation', '$sector',
                                        '$spouseName', '$spouseOccupation', '$voterStatus' );";
        mysqli_query($conn, $sql);

    
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:400,700">
    <title>Add New Resident</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container-fluid">
        <h5>New Resident Registration Form</h5>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row">
                <div class="col-md-4">
                    <img src="assets/barangay-config/logo.png" class="image-view">
                    <button type="submit" class="btn btn-block btn-secondary">Save changes</button>
                    <button type="submit" class="btn btn-block btn-secondary">Save changes</button>
                </div>
                <div class="col-md-8">
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
                                >
                        </div>
                        <!-- birthdate -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Birthdate: </span>
                            <input 
                            type="date"
                            name="birth-date"
                            class="form-control <?php echo (!empty($birth_date_err)) ? 'is-invalid' : ''; ?>"
                            placeholder="Birth Date"
                            value="<?php echo $birth_date; ?>">
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
                                >
                        </div>
                        <!-- civil-status -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Civil Status: </span>
                            <select class="form-control" 
                            aria-label="Default select example" 
                            name="committee"
                            required="required">
                            <?php
                                $a_civil_status = ["Single", "Married", "Separated", "Widowed"];
                                $a_cs_value = ['SG', 'MR', 'SP', 'WD'];
                                $i = 0;
                                foreach($a_civil_status as $value) {
                                    echo '<option value="'.$a_cs_value[$i].'" '.(($a_cs_value[$i] == $i++) ? 'selected': '').'>'.$value.'</option>';
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
                                >
                        </div>
                        <!-- gender -->
                        <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Gender: </span>
                            <select class="form-control" 
                            aria-label="Default select example" 
                            name="committee"
                            required="required">
                            <?php
                                $a_gender = ["Male", "Female"];
                                $a_gd_value = ['M', 'FM'];
                                $i = 0;
                                foreach($a_gender as $value) {
                                    echo '<option value="'.$a_gd_value[$i].'" '.(($a_gd_value[$i] == $i++) ? 'selected': '').'>'.$value.'</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- alias -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Alias: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="alias"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $middle_name; ?>"
                                >
                        </div>
                        <!-- voter status -->
                        <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Voter Status: </span>
                            <select class="form-control" 
                            aria-label="Default select example" 
                            name="committee"
                            required="required">
                            <?php
                                $a_voter_status= ["Yes", "No"];
                                $a_vs_value = ['Y', 'N'];
                                $i = 0;
                                foreach($a_voter_status as $value) {
                                    echo '<option value="'.$a_vs_value[$i].'" '.(($a_vs_value[$i] == $i++) ? 'selected': '').'>'.$value.'</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!---purok--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Purok: </span>
                                <select class="form-control" 
                                aria-label="Default select example" 
                                name="committee"
                                required="required">
                                <?php
                                    $a_purok= ["Area_1", "Area_2","Area_3","Area_4"];
                                    $a_pr_value = ['A1', 'A2', 'A3', 'A4'];
                                    $i = 0;
                                    foreach($a_purok as $value) {
                                        echo '<option value="'.$a_pr_value[$i].'" '.(($a_pr_value[$i] == $i++) ? 'selected': '').'>'.$value.'</option>';
                                    }
                                ?>
                                </select>
                        </div>

                        <div class="input-group col-md-6">
                            <!---Occupation--->
                        <span class="mb-0 mt-auto mx-1">Occupation & Sector: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="first-name"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $first_name; ?>"
                                >
                                <!---Sector--->      
                            <select class="form-control" 
                            aria-label="Default select example" 
                            name="committee"
                            required="required">
                            <?php
                                $a_sector= ["Private", "Public", "Government", "Unemployed", "Out of School Youth (OSY)", "Out of School Children (OSC)", "Person With Disability (PWD)",
                            "Senior Citizen (SC)", "Overseas Filipino Worker (OFW)", "Solo Parent", "Indigenous People (IP)", "Others"];
                                $a_st_value = ['PV', 'PB', 'GOV', 'UNEMP', 'OSY', 'OSC', 'PWD', 'SC', 'OFW', 'SP', 'IP', 'OTHERS'];
                                $i = 0;
                                foreach($a_sector as $value) {
                                    echo '<option value="'.$a_st_value[$i].'" '.(($a_st_value[$i] == $i++) ? 'selected': '').'>'.$value.'</option>';
                                }
                            ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <!-- nationality -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Nationality: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="nationality"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $middle_name; ?>"
                                >
                        </div>

                        <!-- religion/belief -->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Religion/Belief: </span>
                            <input
                                type="text"
                                class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                                name="religion"
                                placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                                value="<?php echo $middle_name; ?>"
                                >
                        </div>

                    </div>    

                    <div class="form-group row">
                        <!---Place of Birth--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Place of Birth: </span>
                            <textarea id="subject" 
                            name="subject" 
                            placeholder="" 
                            style="height:75px"></textarea>
                        </div>

                        <!---Face Marks--->
                        <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1">Face Marks: </span>
                            <textarea id="subject" 
                            name="subject" 
                            placeholder="" 
                            style="height:75px"></textarea>
                        </div>

    
                    </div>





                </div>
            </div>
        </form>
        <hr>
        <button type="submit" class="btn btn-success">Save new Resident</button>
    </div>
</body>
</html>