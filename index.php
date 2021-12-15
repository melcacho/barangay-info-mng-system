<?php
    session_start();

    $_SESSION = array();

    session_destroy();
    require_once "config.php";

    $username = $password = $login_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);

        $sql = "SELECT ADMIN_ID, PASSWORD FROM admins WHERE USERNAME = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = strtoupper($username);

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($admin_id, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_start();

                            $_SESSION["loggedin"] = true;
                            $_SESSION["admin-id"] = $admin_id;

                            header("location: dashboard.php");
                        } else {
                            $login_err = "Invalid username or password";
                            $username = $password = "";
                        }
                    }
                } else {
                    $login_err = "Invalid username or password";
                    $username = $password = "";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
            $stmt->close();
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

    <style>
        html,
        body {
            height: 100%
        }
    </style>
</head>

<body>
    <div class="d-flex h-100 align-items-center justify-content-center">
        <div id="sign-in" class="p-5 rounded-lg">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <h2>Barangay Information System</h2>
                <hr>
                <div class="form-group">
                    <!-- username -->
                    <div class="input-group my-2">
                        <span class="mb-0 mt-auto mx-1">Username: </span>
                        <input type="text"
                            class="form-control text-white"
                            name="username"
                            required>
                    </div>
                    <!-- password -->
                    <div class="input-group my-2">
                        <span class="mb-0 mt-auto mx-1">Password: </span>
                        <input type="password"
                            class="form-control text-white" 
                            name="password"
                            required>
                    </div>
                </div>
                
                <span class="text-danger">
                    <?php echo (!empty($login_err)) ? $login_err : ''?>
                </span>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>