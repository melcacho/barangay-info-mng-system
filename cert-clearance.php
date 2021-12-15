<?php

    if(isset($_POST["issue"])) {
        require('fpdf/fpdf.php');
        
        $pdf = new FPDF();
        $pdf->AddPage();
        // Insert a logo in the top-left corner at 300 dpi
        $pdf->Image('user-icon.png',0,0,-300);
        $pdf -> SetFont('Arial', '', 12);
        $pdf->Multicell(0,2,"Republic of the Philippines
        \nProvince of Dummy Province
        \nCity / Municipality of Dummy City",0,'C'); 

        $pdf -> SetFont('Arial', 'B', 16);
        $pdf->Multicell(0,10,"Barangay Langgam\n\n",0,'C'); 

        $pdf -> SetFont('Arial', 'B', 24);
        $pdf->Multicell(0,15,"Barangay Clearance\n\n",0,'C'); 

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(10,70);
        $pdf-> Multicell(50,4,"Aidel Fosana\nBarangay Captain
        \n\nAidel Fosana\nKagawad\nProgram1
        \n\nAidel Fosana\nKagawad\nProgram2
        \n\nAidel Fosana\nKagawad\nProgram3
        \n\nAidel Fosana\nKagawad\nProgram4
        \n\nAidel Fosana\nKagawad\nProgram5
        \n\nAidel Fosana\nKagawad\nProgram6
        \n\nAidel Fosana\nKagawad\nProgram7
        \n\nAidel Fosana\nSecretary
        \n\nAidel Fosana\nTreasurer",1,'C');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(85,70);
        $pdf-> Multicell(0,3,"This is to certify that <name>, that is of legal age, <nationality>",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setX(65);
        $pdf -> Multicell(0,5,"and a bonafied resident of Barangay Langgam and that has no derogatory",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setX(65);
        $pdf -> Multicell(0,5,"records in the Barangay prior to the date of issuance of this certificate.\n\n",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setX(85);
        $pdf-> Multicell(0,3,"This certificate is issued in his/her request for:",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setX(90);
        $pdf-> Multicell(0,3,"\n\n\n\n<purpose>",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setX(85);
        $pdf-> Multicell(0,3,"\n\n\n\nIssued this <date>",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(65,170);
        $pdf-> Multicell(0,4,"_________________\n         Signature",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(160,170);
        $pdf-> Multicell(0,4,"    Aidel Fosana\nBarangay Captain",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(65,205);
        $pdf-> Multicell(0,6,"OR Number: __________________\nOR Date Issued: _______________",0,'L');

        $pdf -> SetFont('Arial', '', 11);
        $pdf -> setXY(130,205);
        $pdf-> Multicell(0,6,"Cedulla Number: _________________\nCedulla Date Issued: ______________",0,'L');

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
