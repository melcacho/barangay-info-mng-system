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
    <link rel="stylesheet" href="css/resident-add-style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
</head>

<div class="signup-form">
    <form name="AddResident" method="post">
        <h2>Resident Information</h2>
        <hr>

        <div class="form-group">
            <div class="row">
                <div class="col"><input type="text" class="form-control" name="familyName" placeholder="Family Name" required></div>

                <div class="col"><input type="text" class="form-control" name="firstName" placeholder="First Name" required></div>

                <div class="col"><input type="text" class="form-control" name="middleName" placeholder="Middle Name" required></div>

            </div>
        </div>


        <div class="form-group">
            <div class="row">
                <div class="col"><input type="text" class="form-control" name="alias" placeholder="Alias" required></div>

                <div class="col"><input type="text" class="form-control" name="facemarks" placeholder="Face Marks" required></div>
            </div>
        </div>

        <div class="form-group">
            <p2>Birthdate</p2>
            <div class="row">
                <div class="col">
                    <select class="custom-select" name="month" required>
                        <option selected disabled>Month</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>

                <div class="col">
                    <select class="custom-select" name="day" required>
                        <option selected disabled>Day</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                        <option value="21">21</option>
                        <option value="22">22</option>
                        <option value="23">23</option>
                        <option value="24">24</option>
                        <option value="25">25</option>
                        <option value="26">26</option>
                        <option value="27">27</option>
                        <option value="28">28</option>
                        <option value="29">29</option>
                        <option value="30">30</option>
                        <option value="31">31</option>
                    </select>
                </div>

                <div class="col">
                    <select class="custom-select" name="year" required>
                        <option selected disabled>Year</option>
                        <option value="2003">2003</option>
                        <option value="2002">2002</option>
                        <option value="2001">2001</option>
                        <option value="2000">2000</option>
                        <option value="1999">1999</option>
                        <option value="1998">1998</option>
                        <option value="1997">1997</option>
                        <option value="1996">1996</option>
                        <option value="1995">1995</option>
                        <option value="1994">1994</option>
                        <option value="1993">1993</option>
                        <option value="1992">1992</option>
                        <option value="1991">1991</option>
                        <option value="1990">1990</option>
                        <option value="1989">1989</option>
                        <option value="1988">1988</option>
                        <option value="1987">1987</option>
                        <option value="1986">1986</option>
                        <option value="1985">1985</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-group">
            <input type="email" class="form-control" name="birthplace" placeholder="Birthplace" required>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col">
                    <select class="custom-select" name="sex" required>
                        <option selected disabled>Sex</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="col">
                    <select class="custom-select" name="civilStatus" required>
                        <option selected disabled>Civil Status</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Separated">Separated</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>

            </div>
        </div>

        <div class="form-group">
            <div class="row">
                <div class="col"><input type="text" class="form-control" name="occupation" placeholder="Occupation" required>
                </div>

                <div class="col">
                    <select class="custom-select" name="sector" required>
                        <option selected disabled>Sector</option>
                        <option value="Priv">Private</option>
                        <option value="Pub">Public</option>
                        <option value="Gov">Government</option>
                        <option value="Unemp">Unemployed</option>
                        <option value="OSY">Out of School Youth (OSY)</option>
                        <option value="OSC">Out of School Children (OSC)</option>
                        <option value="PWD">Person with Disability (PWD)</option>
                        <option value="SC">Senior Citizen (SC)</option>
                        <option value="OFW">Overseas Filipino Worker (OFW)</option>
                        <option value="Solo">Solo Parent</option>
                        <option value="IP">Indiginous People (IP)</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
            </div>
        </div>


        <div id="test" class="form-group">
            <div class="row">
                <div class="col"><input type="text" class="form-control" name="spouseName" placeholder="Spouse's Name" required></div>

                <div class="col"><input type="text" class="form-control" name="spouseOccupation" placeholder="Spouse's Occupation" required></div>
            </div>
        </div>

        <div class="form-group">
            <select class="custom-select" name="voterStatus" required>
                <option selected disabled>Voter Status</option>
                <option value="Yes">Yes</option>
                <option value="No">No</option>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg" name="button">Add New Resident</button>
        </div>

    </form>
</div>
</body>
</html>