<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'barangay_system');

/* Attempt to connect to MySQL database */
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Check connection
if ($mysqli === false) {
    die("ERROR: Could not connect. " . $mysqli->connect_error);
}

$sql = "CREATE TABLE residents (
    RESIDENT_ID INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    LNAME VARCHAR(20) NOT NULL,
    FNAME VARCHAR(50) NOT NULL, 
    MNAME VARCHAR(20),
    ALIAS VARCHAR(20),
    FACE_MARKS VARCHAR(50),
    BIRTH_DATE CHAR(10) NOT NULL,
    BIRTH_PLACE VARCHAR(100) NOT NULL,
    SEX CHAR(1) NOT NULL,
    CIVIL_STATUS CHAR(2) NOT NULL,
    NATIONALITY VARCHAR(20) NOT NULL,
    BELIEF VARCHAR(20) NOT NULL,
    OCCUPATION VARCHAR(20),
    SECTOR CHAR(3) NOT NULL,
    SPOUSE_NAME VARCHAR(50),
    SPOUSE_OCCUPATION VARCHAR(50),
    VOTER_STATUS TINYINT NOT NULL,
    VOTER_ACTIVE TINYINT,
    CONTACT_ONE VARCHAR(11),
    CONTACT_TWO VARCHAR(11),
    EMAIL_ONE VARCHAR(50),
    EMAIL_TWO VARCHAR(50),
    RES_TYPE CHAR(1) NOT NULL,
    RES_STATUS CHAR(1) NOT NULL,
    DATE_TIME_REG CHAR(22) NOT NULL,
    PROCESSED_BY INT(6) NOT NULL
)";

if($mysqli->query($sql)) {
    echo '<script>
    alert("Table Residents Created");
    </script>';
}

$sql = "CREATE TABLE admins (
    ADMIN_ID INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    LNAME VARCHAR(20) NOT NULL,
    FNAME VARCHAR(50) NOT NULL, 
    MNAME VARCHAR(20),
    COMMITTEE TINYINT NOT NULL,
    POSITION TINYINT NOT NULL,
    USERNAME VARCHAR(20) NOT NULL,
    PASSWORD VARCHAR(255) NOT NULL,
    RESIDENT_ID INT(6) NOT NULL
)";

if($mysqli->query($sql)) {
    echo '<script>
    alert("Table Admins Created");
    </script>';
} 

/*else {
    echo '<script>
    alert("'.mysqli_error($mysqli).'");
    </script>';
}*/