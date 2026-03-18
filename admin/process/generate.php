<?php

if (isset($_POST["ref_no"])) {
    require_once '../../database/config.php';
    $refNo = $_POST["ref_no"];
    $stmt = $pdo->prepare('
   SELECT 
   R.ref_no as ref_no,
R.emp_name as requestor_name, 
R.emp_id as requestor_id,
MA.remarks as remarks,
IFNULL(SStatus.name, MA.other_status) as service_status,
IFNULL(SS.name, R.other_category) as type_of_service, 
CONCAT(MP.first_name, " ", MP.last_name) as service_personnel,
DATE_FORMAT(R.created_at, "%M %e, %Y - %l:%i%p") as created_at, 
DATE_FORMAT(MA.created_at, "%M %e, %Y - %l:%i%p") as finished_at, 
CONCAT(
    FLOOR(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at) / 86400), "d ",  
    LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 86400) / 3600), 2, "0"), "h:",
    LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 3600) / 60), 2, "0"), "m:",
    LPAD(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 60), 2, "0"), "s"
) as duration,
L.location_name
FROM 
request R
LEFT JOIN sub_service SS ON R.sub_category_id = SS.id
LEFT JOIN service S ON SS.service_id = S.id
INNER JOIN location L ON R.location_id = L.location_id
LEFT JOIN maintenance_activity MA ON R.id = MA.request_id
LEFT JOIN service_status SStatus ON MA.service_status_id = SStatus.id
INNER JOIN maintenance_personnel MP ON MA.personnel_id = MP.id
WHERE 
R.ref_no = :ref_no 
ORDER BY 
R.created_at DESC;

    ');
    $stmt->execute(['ref_no' => $refNo]);
    $data = $stmt->fetch(PDO::FETCH_OBJ);
    $data = (object) [
        'ref_no' => $refNo ?? '',
        'requestor_name' => ucwords(strtolower($data->requestor_name)) ?? '',
        'requestor_id' => $data->requestor_id ?? '',
        'service_status' => $data->service_status ?? '',
        'type_of_service' => $data->type_of_service ?? '',
        'service_personnel' => $data->service_personnel ?? '',
        'created_at' => $data->created_at ?? '',
        'finished_at' => $data->finished_at ?? '',
        'remarks' => $data->remarks ?? '',
    ];



    require_once '../../vendor/autoload.php'; // Ensure TCPDF is installed via Composer

    class CustomPDF extends TCPDF
    {
        // Custom Header
        /*  public function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 5, 'ICT SERVICES SUPPORT RECEIPT', 0, 1, 'C');
        $this->Ln(5);
    } */

        // Custom Footer
        /*   public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->getAliasNumPage() . ' of ' . $this->getAliasNbPages(), 0, 0, 'C');
    } */
        // Custom Footer with a line
        /* public function Footer()
    {
        $this->SetY(-20); // Position at 15mm from the bottom
        $pageWidth = $this->getPageWidth(); // Get page width dynamically

        $this->Line(10, $this->getY(), $pageWidth - 10, $this->getY());
    } */
    }

    // Function to Generate a Single Card
    function generateCard($pdf, $x, $y, $data)
    {
        $pdf->SetXY($x, $y);
        $pdf->SetFont('times', '', 10);

        // Draw Card Border
        /*  $pdf->Rect($x, $y, 190, 120); // (X, Y, Width, Height) */
        // Draw only top and bottom borders

        // Title
        $pdf->SetFont('times', 'B', 9);
        $pdf->SetXY($x + 35, $y + 0);
        $pdf->Cell(0, 5, 'Republic of the Philippines', 0, 1, 'L');
        $pdf->SetXY($x + 35, $y + 4);
        $pdf->SetFont('times', 'm', 9);
        $pdf->Cell(0, 5, 'OFFICE OF THE PRESIDENT', 0, 1, 'L');
        $pdf->SetFont('times', 'B', 9);
        $pdf->SetXY($x + 35, $y + 7.5);
        $pdf->Cell(0, 5, 'NATIONAL IRRIGATION ADMINISTRATION', 0, 1, 'L');
        $pdf->SetFont('times', 'm', 9);
        $pdf->SetXY($x + 35, $y + 11.5);
        $pdf->Cell(0, 5, 'UPPER PAMPANGA RIVER INTEGRATED IRRIGATION SYSTEMS', 0, 1, 'L');
        // Insert Image\
        $pdf->Image('../img/Office of the President of the Philippines.png', $x, $y + 2, 15, 15, '', '', '', false, 300, '', false, false, 0);
        $pdf->Image('../img/nia-logo.png', $x + 17, $y + 2, 15, 15, '', '', '', false, 300, '', false, false, 0);
        $pdf->Image('../img/bagong pilipinas.png', $x + 170, $y, 17, 17, '', '', '', false, 300, '', false, false, 0);

        // Table Structure
        $pdf->SetFont('times', 'B', 12);
        $pdf->SetXY($x + 0, $y + 35); // Centered horizontally assuming page width is 190
        $pdf->MultiCell(0, 5, 'ICT Services Support Receipt', 0, 'C', false);
        $pdf->SetFont('times', 'B', 12);
        $pdf->SetXY($x + 0, $y + 45); // Centered horizontally assuming page width is 190
        $pdf->MultiCell(0, 5, 'Receipt No. ' . $data->ref_no, 0, 'C', false);

        $tableY = $y + 60;
        $pdf->SetFont('times', '', 11);
        $pdf->SetXY($x + 5, $tableY);


        // Define column width
        $col1 = 90;
        $col2 = 90;
        $random = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequuntur deserunt, excepturi sapiente deleniti aperiam quis suscipit quibusdam vitae dolor iusto. Eius sed iste rem, a dolorum molestiae expedita, tenetur soluta fuga accusamus omnis. Tempore nihil corporis itaque aperiam laudantium, quos consequatur, saepe repellat tenetur quaerat, facere tempora asperiores temporibus deserunt' .
            $sada = "<b>Sample text for placeholder purposes.</b>";
        $col1Text = 'Name of Requester: <br><b>' . $data->requestor_name . '</b>';
        $col2Text = 'ID Number : <br> <b>' . $data->requestor_id . '</b>';
        $col3Text = 'Type of Service: <br><b>' . $data->type_of_service . '</b>';
        $col4Text = 'Servicing Personnel: <br><b>' . $data->service_personnel . '</b>';
        $col5Text = 'Date and Time Requested: <br><b>' . $data->created_at . '</b>';
        $col6Text = 'Date and Time Completed: <br><b>' . $data->finished_at . '</b>';
        $col7Text = 'Status of Service: <br><b>' . $data->service_status . '</b>';
        $col8Text = 'Remarks: <br><b>' . $data->remarks . '</b>';
        // Calculate the required height for each column
        $col1Height = $pdf->getStringHeight($col1, $col1Text);
        $col2Height = $pdf->getStringHeight($col2, $col2Text);
        $col3Height = $pdf->getStringHeight($col1, $col3Text);
        $col4Height = $pdf->getStringHeight($col2, $col4Text);
        $col5Height = $pdf->getStringHeight($col1, $col5Text);
        $col6Height = $pdf->getStringHeight($col2, $col6Text);
        $col7Height = $pdf->getStringHeight($col1, $col7Text);
        $col8Height = $pdf->getStringHeight($col2, $col8Text);

        $row1MinHeight = 15;
        $row2MinHeight = 20;
        $row3MinHeight = 25;
        $row4MinHeight = 25;

        // Set the max height between the two columns
        $row1Height = max($col1Height, $col2Height);
        if ($row1Height < $row1MinHeight) {
            $row1Height = $row1MinHeight;
        }
        $row2Height = max($col3Height, $col4Height);
        if ($row2Height < $row2MinHeight) {
            $row2Height = $row2MinHeight;
        }
        $row3Height = max($col5Height, $col6Height);
        if ($row3Height < $row3MinHeight) {
            $row3Height = $row3MinHeight;
        }
        $row4Height = max($col7Height, $col8Height);
        if ($row4Height < $row4MinHeight) {
            $row4Height = $row4MinHeight;
        }
        // Set padding for cells
        $pdf->setCellPaddings(2, 1, 2, 2); // (left, top, right, bottom)


        // Now use writeHTMLCell() instead of MultiCell() for bold text support
        $pdf->writeHTMLCell($col1, $row1Height, '', '', $col1Text, 1, 0, false, true, 'L', true);
        $pdf->writeHTMLCell($col2, $row1Height, '', '', $col2Text, 1, 1, false, true, 'L', true);
        $pdf->SetX($x + 5);
        $pdf->writeHTMLCell($col1, $row2Height, '', '', $col3Text, 1, 0, false, true, 'L', true);
        $pdf->writeHTMLCell($col2, $row2Height, '', '', $col4Text, 1, 1, false, true, 'L', true);
        $pdf->SetX($x + 5);
        $pdf->writeHTMLCell($col1, $row3Height, '', '', $col5Text, 1, 0, false, true, 'L', true);
        $pdf->writeHTMLCell($col2, $row3Height, '', '', $col6Text, 1, 1, false, true, 'L', true);
        $pdf->SetX($x + 5);
        $pdf->writeHTMLCell($col1, $row4Height, '', '', $col7Text, 1, 0, false, true, 'L', true);
        $pdf->writeHTMLCell($col2, $row4Height, '', '', $col8Text, 1, 1, false, true, 'L', true);

        $pdf->SetX($x + 3, $y);
        $pdf->Cell(0, 5, 'Printed at: ' . date('F j, Y, g:i a'), 0, 1, 'L');

        // Signature Line
        $pdf->SetXY($x + 110, $y + 220);
        $pdf->Cell(0, 5, '_____________________________________', 0, 1, 'L');
        $pdf->SetXY($x + 113, $y + 225);
        $pdf->Cell(0, 5, 'Signature of Requestor over Printed Name', 0, 1, 'L');

        $pageHeight = $pdf->getPageHeight();
        $footerY = $pageHeight - 60; // Adjust based on the footer height

        $pdf->Image('../img/footer.png', 5, $footerY, 200, 55, '', '', '', false, 300, '', false, false, 0);
        $pdf->Image('../img/qrcode.png', 160, $footerY + 32, 39, 14, '', '', '', false, 300, '', false, false, 0);
        $footerY = $pageHeight - 50; // Adjust based on the footer height
        $pdf->SetFont('times', 'B', 9);
        $pdf->SetXY($x, $footerY + 24.5);
        $pdf->Cell(0, 5, 'Maharlika Highway, Cabanatuan City, Nueva Ecija, Philippines', 0, 1, 'L');
        $pdf->SetFont('times', '', 9);/* 
$pdf->SetXY($x, $footerY + 29); */
        $pdf->writeHTMLCell(0, 5, $x, $footerY + 29, 'Direct line No.: (044) 958 9709 &#8226; Telefax No.: (044) 958 9709', 0, 1, false, true, 'L', true);
        /*   $pdf->SetXY($x, $footerY + 33); */
        $pdf->writeHTMLCell(0, 5, $x, $footerY + 33, 'Email: upriis@nia.gov.ph &#8226; Website: www.upriis.nia.gov.ph &#8226; TIN: 000916415024', 0, 1, false, true, 'L', true);
        $pdf->SetFont('times', 'I', 8);
        $pdf->writeHTMLCell(0, 5, $x, $footerY + 38, 'NIA-UPRIIS-HEAD OFFICE-ODM-ICT-INT-Form03-Rev00', 0, 1, false, true, 'L', true);
    }

    // Create PDF instance for A4 size
    $pdf = new CustomPDF('P', 'mm', 'A4'); // 'P' for Portrait, 'mm' for millimeters
    $pdf->SetMargins(0, 0, 0, 0); // Left, Top, Right margins
    $pdf->setPrintHeader(false); // Disable header (removes header line)
    $pdf->setPrintFooter(false); // Disable footer if not needed
    $pdf->SetAutoPageBreak(false, 0);

    $pdf->AddPage();
    // Get page width dynamically
    $pageWidth = $pdf->getPageWidth();

    // Draw a centered horizontal dotted line

    // Generate Two Cards on One Page
    generateCard($pdf, 10, 10, $data);  // First Card (Top)
    // generateCard($pdf, 10, 150); // Second Card (Below)
    // $style = array('width' => 0.5, 'cap' => 'round', 'join' => 'round', 'dash' => '2,5', 'color' => array(0, 0, 0));
    // $pdf->setLineStyle($style);
    // $pdf->Line(10, 145, $pageWidth - 10, 145); // X1, Y1, X2, Y2

    // Output PDF
    $pdf->Output('ICT_Support_Receipt.pdf', 'I'); // 'I' to view in browser, 'D' to download
} else {
    echo "<script>alert('Access Denied');</script>";
}
