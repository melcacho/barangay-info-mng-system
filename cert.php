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
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Family Name: </span>
                        <input
                            type="text"
                            class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                            name="last-name"
                            placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                            value="<?php echo $last_name; ?>"
                            maxlength="20"
                            <?php echo (isset($ses_type) && !$ses_type) ? 'disabled' : ''?>
                            required>
                    </div>
                </div>
            </div>

            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">First Name: </span>
                        <input
                            type="text"
                            class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                            name="last-name"
                            placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                            value="<?php echo $last_name; ?>"
                            maxlength="20"
                            <?php echo (isset($ses_type) && !$ses_type) ? 'disabled' : ''?>
                            required>
                    </div>
                </div>
            </div>

            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Middle Name: </span>
                        <input
                            type="text"
                            class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                            name="last-name"
                            placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                            value="<?php echo $last_name; ?>"
                            maxlength="20"
                            <?php echo (isset($ses_type) && !$ses_type) ? 'disabled' : ''?>
                            required>
                    </div>
                </div>
            </div>

            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Gender: </span>
                        <input
                            type="text"
                            class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                            name="last-name"
                            placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                            value="<?php echo $last_name; ?>"
                            maxlength="20"
                            <?php echo (isset($ses_type) && !$ses_type) ? 'disabled' : ''?>
                            required>
                    </div>
                </div>
            </div>

            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Birthdate: </span>
                        <input
                            type="text"
                            class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                            name="last-name"
                            placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                            value="<?php echo $last_name; ?>"
                            maxlength="20"
                            <?php echo (isset($ses_type) && !$ses_type) ? 'disabled' : ''?>
                            required>
                    </div>
                </div>
            </div>

            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                        <span class="mb-0 mt-auto mx-1">Civil Status: </span>
                        <input
                            type="text"
                            class="form-control <?php echo (!empty($name_err)) ? 'invalid' : ''; ?>"
                            name="last-name"
                            placeholder="<?php echo (!empty($name_err)) ? $name_err : ''; ?>"
                            value="<?php echo $last_name; ?>"
                            maxlength="20"
                            <?php echo (isset($ses_type) && !$ses_type) ? 'disabled' : ''?>
                            required>
                    </div>
                </div>
            </div>

            <div class="row">   
                <div class="form-group row">
                    <!-- last-name -->
                    <div class="input-group col-md-6">
                            <span class="mb-0 mt-auto mx-1 w-100">Purpose: </span>
                            <textarea
                                name="birth-place"  
                                rows="2"
                                class="w-100"
                                maxlength="50"
                                <?php echo isset($ses_type) ? 'disabled' : ''?>
                                required><?php echo $birth_place;?></textarea>
                        </div>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
