<?php
// Check if parameters are set (but don't require them to be non-empty)
if (isset($_GET["start_date"]) && isset($_GET["end_date"]) && isset($_GET["category"]) && isset($_GET["maintenancePersonnel"])) {
    require_once '../../database/config.php';

    $startDate = !empty($_GET["start_date"]) ? $_GET["start_date"] : null;
    $endDate = !empty($_GET["end_date"]) ? $_GET["end_date"] : null;
    $category = !empty($_GET["category"]) ? $_GET["category"] : null;
    $personnelId = !empty($_GET["maintenancePersonnel"]) ? $_GET["maintenancePersonnel"] : null;

    // Find the date range to display in the "AS OF" section - Optimized query
    $dateSql = 'SELECT 
                MIN(R.created_at) as earliest_date,
                MAX(R.created_at) as latest_date
            FROM 
                request R 
            WHERE 
                R.status = "completed"';

    // Apply the same filters to the date query
    $dateParams = [];
    if (!empty($startDate)) {
        $dateSql .= " AND R.created_at >= :start_date";
        $dateParams[':start_date'] = $startDate . " 00:00:00";
    }

    if (!empty($endDate)) {
        $dateSql .= " AND R.created_at <= :end_date";
        $dateParams[':end_date'] = $endDate . " 23:59:59";
    }

    if (!empty($category)) {
        $dateSql .= " AND EXISTS (SELECT 1 FROM sub_service SS JOIN service S ON SS.service_id = S.id WHERE R.sub_category_id = SS.id AND S.id = :category)";
        $dateParams[':category'] = $category;
    }

    if (!empty($personnelId)) {
        $dateSql .= " AND EXISTS (SELECT 1 FROM maintenance_activity MA WHERE R.id = MA.request_id AND MA.personnel_id = :personnel_id)";
        $dateParams[':personnel_id'] = $personnelId;
    }

    $dateStmt = $pdo->prepare($dateSql);
    $dateStmt->execute($dateParams);
    $dateRange = $dateStmt->fetch(PDO::FETCH_OBJ);

    // Format display date range
    $displayDateRange = "";
    if (!empty($startDate) && !empty($endDate)) {
        $displayDateRange = date('F j, Y', strtotime($startDate)) . " - " . date('F j, Y', strtotime($endDate));
    } else if (!empty($startDate)) {
        $displayDateRange = date('F j, Y', strtotime($startDate)) . " onwards";
    } else if (!empty($endDate)) {
        $displayDateRange = "up to " . date('F j, Y', strtotime($endDate));
    } else if ($dateRange && $dateRange->earliest_date && $dateRange->latest_date) {
        $earliestDate = date('F j, Y', strtotime($dateRange->earliest_date));
        $latestDate = date('F j, Y', strtotime($dateRange->latest_date));

        if ($earliestDate === $latestDate) {
            $displayDateRange = $earliestDate;
        } else {
            $displayDateRange = $earliestDate . " - " . $latestDate;
        }
    } else {
        $displayDateRange = date('F j, Y'); // Default to today if no data
    }

    // Build the SQL query with filters - Optimized with selective columns
    $sql = 'SELECT 
            R.ref_no as ref_no,
            R.emp_name as requestor_name, 
            R.emp_id as requestor_id,
            MA.remarks as remarks,
            L.location_name as location_name,
            IFNULL(SStatus.name, MA.other_status) as service_status,
            IFNULL(SS.name, R.other_category) as type_of_service,
            IFNULL(S2.name, "Others") as type_of_support,
            IFNULL(S.name, "Others") as category,
            CONCAT(MP.first_name, " ", MP.last_name) as service_personnel,
            DATE_FORMAT(R.created_at, "%M %e, %Y ") as created_at, 
            DATE_FORMAT(MA.created_at, "%M %e, %Y ") as finished_at, 
            CONCAT(
                FLOOR(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at) / 86400), "d ",  
                LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 86400) / 3600), 2, "0"), "h:",
                LPAD(FLOOR(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 3600) / 60), 2, "0"), "m:",
                LPAD(MOD(TIMESTAMPDIFF(SECOND, R.created_at, MA.created_at), 60), 2, "0"), "s"
            ) as duration
        FROM 
            request R
            LEFT JOIN sub_service SS ON R.sub_category_id = SS.id
            LEFT JOIN service S ON SS.service_id = S.id
            INNER JOIN location L ON R.location_id = L.location_id
            LEFT JOIN maintenance_activity MA ON R.id = MA.request_id
            LEFT JOIN service_status SStatus ON MA.service_status_id = SStatus.id
            INNER JOIN maintenance_personnel MP ON MA.personnel_id = MP.id
            LEFT JOIN service S2 ON S2.id = SS.service_id
        WHERE 
            R.status = "completed"';

    $params = [];

    // Add filters
    if (!empty($startDate)) {
        $sql .= " AND R.created_at >= :start_date";
        $params[':start_date'] = $startDate . " 00:00:00";
    }

    if (!empty($endDate)) {
        $sql .= " AND R.created_at <= :end_date";
        $params[':end_date'] = $endDate . " 23:59:59";
    }

    if (!empty($category)) {
        $sql .= " AND S.id = :category";
        $params[':category'] = $category;
    }

    // Add maintenance personnel filter to the main query
    if (!empty($personnelId)) {
        $sql .= " AND MP.id = :personnel_id";
        $params[':personnel_id'] = $personnelId;
    }

    $sql .= " ORDER BY R.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_OBJ);

    // Get category name if a category filter is applied
    $categoryName = '';
    if (!empty($category)) {
        $catStmt = $pdo->prepare("SELECT name FROM service WHERE id = :id");
        $catStmt->execute([':id' => $category]);
        $catResult = $catStmt->fetch(PDO::FETCH_OBJ);
        if ($catResult) {
            $categoryName = $catResult->name;
        }
    }

    // Get personnel name if a personnel filter is applied
    $personnelName = '';
    if (!empty($personnelId)) {
        $perStmt = $pdo->prepare("SELECT CONCAT(first_name, ' ', last_name) as name FROM maintenance_personnel WHERE id = :id");
        $perStmt->execute([':id' => $personnelId]);
        $perResult = $perStmt->fetch(PDO::FETCH_OBJ);
        if ($perResult) {
            $personnelName = $perResult->name;
        }
    }

    require_once '../../vendor/autoload.php';

    class CustomPDF extends TCPDF
    {
        // Properties
        protected $dateRange;
        protected $categoryName;
        protected $personnelName;
        protected $pageNumPrefix = 'Page ';
        protected $pageNumSuffix = ' of ';

        // Setters
        public function setDateRange($dateRange)
        {
            $this->dateRange = $dateRange;
        }

        public function setCategoryName($categoryName)
        {
            $this->categoryName = $categoryName;
        }

        public function setPersonnelName($personnelName)
        {
            $this->personnelName = $personnelName;
        }

        // Custom Header
        public function Header()
        {
            $pageWidth = $this->getPageWidth();
            $x = 10; // Left margin
            $y = 10; // Top margin

            // Title
            $this->SetFont('times', 'B', 9);
            $this->SetXY($x + 35, $y);
            $this->Cell(0, 5, 'Republic of the Philippines', 0, 1, 'L');
            $this->SetXY($x + 35, $y + 4);
            $this->SetFont('times', '', 9);
            $this->Cell(0, 5, 'OFFICE OF THE PRESIDENT', 0, 1, 'L');
            $this->SetFont('times', 'B', 9);
            $this->SetXY($x + 35, $y + 7.5);
            $this->Cell(0, 5, 'NATIONAL IRRIGATION ADMINISTRATION', 0, 1, 'L');
            $this->SetFont('times', '', 9);
            $this->SetXY($x + 35, $y + 11.5);
            $this->Cell(0, 5, 'UPPER PAMPANGA RIVER INTEGRATED IRRIGATION SYSTEMS', 0, 1, 'L');

            // Insert Images
            $this->Image('../img/Office of the President of the Philippines.png', $x, $y + 2, 15, 15, '', '', '', false, 72, '', false, false, 0);
            $this->Image('../img/nia-logo.png', $x + 17, $y + 2, 15, 15, '', '', '', false, 72, '', false, false, 0);
            $this->Image('../img/bagong pilipinas.png', $pageWidth - 30, $y, 17, 17, '', '', '', false, 72, '', false, false, 0);

            // Report Title
            $this->SetFont('times', 'B', 11);
            $this->SetXY($x, $y + 24);
            $this->Cell($pageWidth - 20, 10, 'ICT SUPPORT SERVICES ACCOMPLISHMENT REPORT', 0, 1, 'C');

            // HEAD OFFICE text
            $this->SetFont('times', 'B', 10);
            $this->SetXY($x, $y + 32);
            $this->Cell($pageWidth - 20, 6, 'HEAD OFFICE', 0, 1, 'C');

            // AS OF date
            $this->SetXY($x, $y + 38);
            $this->Cell($pageWidth - 20, 6, 'As of ' . $this->dateRange, 0, 1, 'C');

            // Information about filters
            $filterY = $y + 45;

            // Category filter if applied
            if (!empty($this->categoryName)) {
                $this->SetFont('times', '', 10);
                $this->SetXY($x, $filterY);
                $this->Cell($pageWidth - 20, 5, 'Category: ' . $this->categoryName, 0, 1, 'C');
                $filterY += 5;
            }


            // Space before table
            $this->Ln(5);
        }

        // Custom Footer
        public function Footer()
        {
            $pageWidth = $this->getPageWidth();
            $pageHeight = $this->getPageHeight();

            // Add page numbers at the bottom
            $this->SetY(-45);
            $this->SetFont('times', 'I', 8);
            $this->Cell(0, 2, $this->pageNumPrefix . $this->getAliasNumPage() . $this->pageNumSuffix . $this->getAliasNbPages(), 0, false, 'C');

            $footerY = $pageHeight - 60;

            // Scale footer image to fit page width
            $this->Image('../img/footer.png', 5, $footerY, $pageWidth - 10, 55, '', '', '', false, 72, '', false, false, 0);

            // Position QR code
            $qrX = $pageWidth - 50;
            $this->Image('../img/qrcode.png', $qrX, $footerY + 32, 39, 14, '', '', '', false, 72, '', false, false, 0);

            $footerY = $pageHeight - 50;
            $this->SetFont('times', 'B', 9);
            $this->SetXY(10, $footerY + 24.5);
            $this->Cell(0, 5, 'Maharlika Highway, Cabanatuan City, Nueva Ecija, Philippines', 0, 1, 'L');

            $this->SetFont('times', '', 9);
            $this->writeHTMLCell(0, 5, 10, $footerY + 29, 'Direct line No.: (044) 958 9709 &#8226; Telefax No.: (044) 958 9709', 0, 1, false, true, 'L', true);
            $this->writeHTMLCell(0, 5, 10, $footerY + 33, 'Email: upriis@nia.gov.ph &#8226; Website: www.upriis.nia.gov.ph &#8226; TIN: 000916415024', 0, 1, false, true, 'L', true);
        }

        // Helper function to truncate text with ellipsis if needed
        public function truncateText($text, $width, $font, $style, $size)
        {
            $this->SetFont($font, $style, $size);
            if ($this->GetStringWidth($text) <= $width) {
                return $text;
            }

            // Truncate text to fit
            $char_width = $this->GetStringWidth('a') * 0.5; // Approximate width per character
            $max_chars = floor($width / $char_width);
            return substr($text, 0, $max_chars - 3) . '...';
        }
    }

    // Create PDF instance for Legal size in landscape mode
    define('PDF_JPEG_QUALITY', 75);
    $pdf = new CustomPDF('L', 'mm', [330.2, 215.9]);
    $pdf->setCompression(true);
    $pdf->setDateRange($displayDateRange);

    // Set category and personnel names if filters are applied
    if (!empty($categoryName)) {
        $pdf->setCategoryName($categoryName);
    }
    if (!empty($personnelName)) {
        $pdf->setPersonnelName($personnelName);
    }

    // Optimize margins
    $pdf->SetMargins(10, 60, 10);
    $pdf->SetAutoPageBreak(true, 65);
    $pdf->setFontSubsetting(true);

    // Setup for pagination
    $pdf->setHeaderMargin(0);
    $pdf->setFooterMargin(0);
    $pdf->setHeaderFont(array('helvetica', '', 10));
    $pdf->setFooterFont(array('helvetica', '', 8));

    $pdf->AddPage();
    $pdf->setPrintHeader(true);
    $pdf->setPrintFooter(true);

    // Calculate available width for the table
    $availableWidth = $pdf->getPageWidth() - 20;

    // Define column widths with more space for location column
    $colWidths = [
        $availableWidth * 0.03,  // 3% for No.
        $availableWidth * 0.09,  // 9% for Ref No.
        $availableWidth * 0.15,  // 15% for Requesting Personnel
        $availableWidth * 0.11,  // 11% for Location
        $availableWidth * 0.14,  // 14% for Type of Support
        $availableWidth * 0.13,  // 13% for Service Personnel
        $availableWidth * 0.10,  // 10% for Date Requested
        $availableWidth * 0.10,  // 10% for Date Resolved
        $availableWidth * 0.15   // 15% for Remarks
    ];

    // Table header function to reuse on new pages
    function addTableHeader($pdf, $colWidths)
    {
        $pdf->SetFont('times', 'B', 10);
        $pdf->SetFillColor(220, 220, 220);
        $pdf->Cell($colWidths[0], 7, 'No.', 1, 0, 'C', true);
        $pdf->Cell($colWidths[1], 7, 'Ref No.', 1, 0, 'C', true);
        $pdf->Cell($colWidths[2], 7, 'Requesting Personnel', 1, 0, 'C', true);
        $pdf->Cell($colWidths[3], 7, 'Location', 1, 0, 'C', true);
        $pdf->Cell($colWidths[4], 7, 'Type of Support', 1, 0, 'C', true);
        $pdf->Cell($colWidths[5], 7, 'Service Personnel', 1, 0, 'C', true);
        $pdf->Cell($colWidths[6], 7, 'Date Requested', 1, 0, 'C', true);
        $pdf->Cell($colWidths[7], 7, 'Date Resolved', 1, 0, 'C', true);
        $pdf->Cell($colWidths[8], 7, 'Remarks', 1, 1, 'C', true);
    }

    // Add initial table header
    addTableHeader($pdf, $colWidths);

    // Add table rows
    $pdf->SetFont('times', '', 10);
    $counter = 1;


    if (count($records) > 0) {
        foreach ($records as $record) {
            // Prepare all cell values
            $rowData = [
                $counter,
                $record->ref_no,
                strtoupper($record->requestor_name),
                $record->location_name,
                $record->type_of_service,
                ucwords($record->service_personnel),
                $record->created_at,
                $record->finished_at ?: '—',
                $record->remarks ?: '—'
            ];

            // Calculate the required height for each cell in the row
            $cellHeights = [];
            for ($i = 0; $i < count($colWidths); $i++) {
                $cellHeights[$i] = $pdf->getStringHeight($colWidths[$i], $rowData[$i]);
            }
            // Use the maximum height for the row
            $rowHeight = max(5, max($cellHeights)) + 1; // +1 for padding

            // Check for page break
            if ($pdf->getY() + $rowHeight > $pdf->getPageHeight() - 65) {
                $pdf->AddPage();
                addTableHeader($pdf, $colWidths);
                $pdf->SetFont('times', '', 10);
            }

            // Print the row using MultiCell for all columns
            for ($i = 0; $i < count($colWidths); $i++) {
                // Last column: set $ln=1 to move to next line, else $ln=0
                $ln = ($i == count($colWidths) - 1) ? 1 : 0;
                $align = ($i == 0 || $i == 1 || $i == 6 || $i == 7) ? 'C' : 'L'; // Center for No., Ref No., Dates
                $pdf->MultiCell($colWidths[$i], $rowHeight, $rowData[$i], 1, $align, 0, $ln);
            }

            $counter++;
        }
    } else {
        // No records found
        $pdf->SetFont('times', 'I', 10);
        $totalWidth = array_sum($colWidths);
        $pdf->Cell($totalWidth, 10, 'No records found matching the selected criteria', 1, 1, 'C');
    }

    // Build output file name
    $reportTitle = 'ICT_Support_Report';
    if (!empty($categoryName)) {
        $reportTitle .= '_' . preg_replace('/[^a-zA-Z0-9]/', '_', $categoryName);
    }
    if (!empty($personnelName)) {
        $reportTitle .= '_Personnel_' . preg_replace('/[^a-zA-Z0-9]/', '_', $personnelName);
    }
    if (!empty($startDate) || !empty($endDate)) {
        $dateInfo = '';
        if (!empty($startDate))
            $dateInfo .= date('Ymd', strtotime($startDate));
        if (!empty($endDate))
            $dateInfo .= '_to_' . date('Ymd', strtotime($endDate));
        $reportTitle .= '_' . $dateInfo;
    }

    // Add summary page
    $pdf->AddPage();

    // Count service types
    $serviceTypes = [];
    foreach ($records as $record) {
        $typeOfSupport = $record->type_of_support;
        if (!isset($serviceTypes[$typeOfSupport])) {
            $serviceTypes[$typeOfSupport] = 1;
        } else {
            $serviceTypes[$typeOfSupport]++;
        }
    }

    $pdf->Ln(0);

    // Calculate summary table dimensions
    $summaryTableWidth = $availableWidth * 0.4;
    $startX = ($availableWidth - $summaryTableWidth) / 2 + 10;
    // Personnel filter if applied

    $pdf->Ln(0);
    // Add summary title
    $pdf->SetFont('times', 'B', 11);
    $pdf->Cell(0, 2, 'SUMMARY OF SERVICES', 0, 1, 'C');
    $pdf->Ln(1);

    // Add summary table headers
    $pdf->SetFont('times', 'B', 9.5);
    $pdf->SetFillColor(220, 220, 220);
    $pdf->SetX($startX);
    $pdf->Cell($summaryTableWidth * 0.7, 5, 'Type of Service', 1, 0, 'C', true);
    $pdf->Cell($summaryTableWidth * 0.3, 5, 'Total', 1, 1, 'C', true);

    // Add summary table rows
    $pdf->SetFont('times', '', 9.5);
    $totalServices = 0;

    // Sort service types alphabetically for better presentation
    ksort($serviceTypes);

    foreach ($serviceTypes as $type => $count) {
        $pdf->SetX($startX);
        $pdf->Cell($summaryTableWidth * 0.7, 5, $type, 1, 0, 'L');
        $pdf->Cell($summaryTableWidth * 0.3, 5, $count, 1, 1, 'C');
        $totalServices += $count;
    }

    // Add total row
    $pdf->SetFont('times', 'B', 8.5);
    $pdf->SetX($startX);
    $pdf->Cell($summaryTableWidth * 0.7, 5, 'TOTAL', 1, 0, 'C');
    $pdf->Cell($summaryTableWidth * 0.3, 5, $totalServices, 1, 1, 'C');

    // Add space before signature section
    $pdf->Ln(3.5);

    // First row: Prepared by and Reviewed by side by side
    $signatureWidth = $availableWidth / 2;

    // Signature headers for first row
    $pdf->SetFont('times', '', 10);
    $pdf->Cell($signatureWidth, 4, 'Prepared by:', 0, 0, 'C');
    $pdf->Cell($signatureWidth, 4, 'Reviewed by:', 0, 1, 'C');

    // Space for signatures
    $pdf->Ln(5);

    // Names for first row
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell($signatureWidth, 4, 'MARK IAN D. VILLANUEVA', 0, 0, 'C');
    $pdf->Cell($signatureWidth, 4, 'ENGR. ALVIN L. MANUEL', 0, 1, 'C');

    // Titles for first row
    $pdf->SetFont('times', '', 10);
    $pdf->Cell($signatureWidth, 4, 'Senior Computer Services Programmer', 0, 0, 'C');
    $pdf->Cell($signatureWidth, 4, 'Division Manager, EOD', 0, 1, 'C');

    // Space between rows
    $pdf->Ln(5);

    // Second row: Noted by (centered)
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(0, 4, 'Noted by:', 0, 1, 'C');

    // Space for signature
    $pdf->Ln(5);

    // Name for second row
    $pdf->SetFont('times', 'B', 10);
    $pdf->Cell(0, 4, 'ENGR. ALVIN L. MANUEL', 0, 1, 'C');

    // Title for second row
    $pdf->SetFont('times', '', 10);
    $pdf->Cell(0, 4, 'Acting Department Manager', 0, 1, 'C');

    // Output PDF
    $pdf->Output($reportTitle . '.pdf', 'I');

} else {
    echo "<script>alert('Access Denied');</script>";
}