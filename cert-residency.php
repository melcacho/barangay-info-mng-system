<?php

    if(isset($_POST["issue"])) {
        require('fpdf/fpdf.php');
        
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial','B',16);
        $pdf->Cell(40,10,'Hello World!');
        $pdf->Output();
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
    <div class="container-fluid my-2 h-100">
        <h2>Issuing Certificate of Residency</h2>
        <div class="row cert">
            <div class="col-md-4 details">
                <h4>Resident Details</h4>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button name="issue" class="btn btn-primary">
                    Issue Certificate
                </button>
            </form>
            </div>
            <div class="col-md-8">
                <object data="test.pdf" type="application/pdf" width="300" height="200">
                    alt : <a href="test.pdf">test.pdf</a>
                </object>
            </div>
        </div>
    </div>
</body>
</html>
