<?php
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\IOFactory;

// Step 1: Increase the maximum execution time to 300 seconds (5 minutes)
set_time_limit(300);

// Function to encode image as base64
function encodeImageToBase64($imagePath)
{
    $imageData = file_get_contents($imagePath);
    return base64_encode($imageData);
}

// Rest of the code remains the same...
// Step 2: Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Step 3: Handle file upload
    if ($_FILES['excel_file']['error'] === 0) {
        $excelFilePath = $_FILES['excel_file']['tmp_name'];

        // Step 4: Read data from Excel file
        $spreadsheet = IOFactory::load($excelFilePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $data = $worksheet->toArray();

        // Step 5 and 6: Process data and generate PDFs
        $headings = $data[0]; // Get the first row (heading row)

        // Start the loop from index 1 to skip the heading row
        for ($index = 1; $index < count($data); $index++) {
            $row = $data[$index];
            $entryName = $row[0]; // Assuming the first column contains the entry name

            // Create a DOM structure for PDF content
            $html = '<html><body>';
            $html .= '<div style="text-align: center;"><strong>JKBRSETI ANANTNAG</strong></div><br>';
            $html .= '<strong>' . $entryName . '</strong>';

            for ($i = 1; $i < count($row); $i++) {
                $heading = $headings[$i];
                $content = $row[$i];

                $html .= '<strong>' . $heading . '</strong>: ' . $content . '<br>';
            }

            // Assuming the image filename is the entry name with .jpg extension
            $imageFilename = $entryName . '.jpg';
            $imagePath = 'image_folder/' . $imageFilename;

            // Encode the image as base64
            if (file_exists($imagePath)) {
                $base64Image = 'data:image/jpeg;base64,' . encodeImageToBase64($imagePath);
                $html .= '<br><img src="' . $base64Image . '" style="height: 200px; width: 200px;" alt="Image">';
            } else {
                // Use the default image (01.jpg) as base64
                $defaultImagePath = 'image_folder/01.jpg';
                $base64DefaultImage = 'data:image/jpeg;base64,' . encodeImageToBase64($defaultImagePath);
                $html .= '<br><img src="' . $base64DefaultImage . '" style="height: 200px; width: 200px;" alt="Default Image">';
            }

            $html .= '</body></html>';

            // Step 7: Generate PDF
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait'); // Set paper size and orientation
            $dompdf->render();

            // Step 8: Output the PDFs
            // Save the PDF or output it for download/display
            $outputFilename = $entryName . '.pdf';
            file_put_contents($outputFilename, $dompdf->output());
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Excel to PDF Conversion</title>
</head>

<body>
    <h2>Upload an Excel file</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="excel_file">
        <input type="submit" value="Convert to PDF">
    </form>
</body>

</html>
