# Excel to PDF Converter

This PHP script allows you to upload an Excel file, process its contents, and generate individual PDF files for each row. The script also embeds images corresponding to each entry.

## Features
- Convert Excel rows into PDF documents.
- Include corresponding images or default placeholders in the PDF.
- Automatically handles missing images by using a default image.

## Requirements
- PHP 8.2 or later.
- [Dompdf](https://github.com/dompdf/dompdf) for PDF generation.
- [PhpSpreadsheet](https://github.com/PHPOffice/PhpSpreadsheet) for reading Excel files.

## Setup
1. Clone this repository:
   ```bash
   git clone https://github.com/ubaidzargar/Excel2Pdf.git
