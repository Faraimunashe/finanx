# PDF Export Functionality

This document explains how to use the PDF export functionality in the Finanx application.

## Overview

The PDF export feature allows users to export financial reports as PDF files directly from the browser using the `html2pdf.js` library. This is a client-side solution that doesn't require server-side PDF generation.

## Features

- **Client-side PDF generation** - No server resources required
- **Professional formatting** - Clean, business-ready PDF layouts
- **Multiple report types** - Balance Sheet, Income Statement, Cash Flow, Trial Balance, General Ledger
- **Customizable options** - Page size, orientation, margins
- **Real-time feedback** - Success/error notifications
- **Responsive design** - Works on all screen sizes

## Components

### PdfExport Component

The main component is located at `resources/js/Shared/Components/PdfExport.vue`

#### Props

- `companyName` (String): Company name to display in the header
- `reportTitle` (String, required): Title of the report
- `reportDate` (String, required): Date or period of the report
- `filename` (String): Name of the generated PDF file
- `orientation` (String): 'portrait' or 'landscape' (default: 'portrait')
- `pageSize` (String): 'A4', 'A3', 'Letter', 'Legal' (default: 'A4')

#### Events

- `export-start`: Emitted when export begins
- `export-complete`: Emitted when export completes successfully
- `export-error`: Emitted when export fails

#### Usage

```vue
<template>
  <PdfExport
    ref="pdfExport"
    :report-title="'Balance Sheet'"
    :report-date="'As of December 31, 2024'"
    :filename="'balance-sheet-2024.pdf'"
    @export-complete="onExportComplete"
    @export-error="onExportError"
  >
    <template #content>
      <!-- Your PDF content here -->
      <div class="pdf-section">
        <h2>Report Content</h2>
        <table>
          <!-- Table content -->
        </table>
      </div>
    </template>
  </PdfExport>
</template>

<script setup>
import PdfExport from '../../../Shared/Components/PdfExport.vue';

const pdfExport = ref(null);

const onExportComplete = () => {
  console.log('PDF exported successfully');
};

const onExportError = (error) => {
  console.error('PDF export failed:', error);
};

const exportReport = () => {
  if (pdfExport.value) {
    pdfExport.value.exportToPdf();
  }
};
</script>
```

## Available Reports

### 1. Balance Sheet
- **File**: `resources/js/Pages/Treasurer/Reports/BalanceSheetPage.vue`
- **Content**: Assets, Liabilities, and Equity sections
- **Features**: Balance verification, financial position summary

### 2. Income Statement
- **File**: `resources/js/Pages/Treasurer/Reports/IncomeStatementPage.vue`
- **Content**: Revenue and Expenses with net income calculation
- **Features**: Profitability analysis, profit margin calculation

### 3. Cash Flow Statement
- **File**: `resources/js/Pages/Treasurer/Reports/CashFlowPage.vue`
- **Content**: Operating, Investing, and Financing activities
- **Features**: Cash flow summary, net cash flow calculation

### 4. Trial Balance
- **File**: `resources/js/Pages/Treasurer/Reports/TrialBalancePage.vue`
- **Content**: All accounts with debit and credit balances
- **Features**: Balance verification, account type filtering

### 5. General Ledger
- **File**: `resources/js/Pages/Treasurer/Reports/GeneralLedgerPage.vue`
- **Content**: Detailed transaction history for each account
- **Features**: Account filtering, running balances, landscape orientation

### 6. Reports Summary
- **File**: `resources/js/Pages/Treasurer/Reports/ReportsPage.vue`
- **Content**: Overview of all available reports and recent transactions
- **Features**: Summary statistics, report availability list

## PDF Styling

The PDF export uses custom CSS classes for consistent formatting:

### Section Classes
- `.pdf-section`: Main content sections
- `.pdf-section-title`: Section headers
- `.pdf-summary-section`: Summary sections with totals

### Table Classes
- `.pdf-total-row`: Total rows in tables
- `.text-right`: Right-aligned text
- `.text-green-600`: Green text for positive values
- `.text-red-600`: Red text for negative values

### Summary Classes
- `.pdf-summary`: Individual summary items
- `.pdf-summary-label`: Summary labels
- `.pdf-summary-value`: Summary values
- `.pdf-summary-grid`: Grid layout for summaries

## Technical Details

### Dependencies
- `html2pdf.js`: Client-side PDF generation library
- `jsPDF`: PDF generation engine
- `html2canvas`: HTML to canvas conversion

### Configuration
The PDF generation is configured with the following options:
- **Margin**: 10mm on all sides
- **Image Quality**: 98% JPEG quality
- **Scale**: 2x for high resolution
- **Page Breaks**: Automatic with CSS support
- **Font**: Arial, 12px

### Browser Compatibility
- Chrome/Chromium: Full support
- Firefox: Full support
- Safari: Full support
- Edge: Full support

## Customization

### Adding New Reports
1. Create a new Vue component in the Reports directory
2. Import and use the `PdfExport` component
3. Define the PDF content using the provided CSS classes
4. Add the route in `routes/web.php`
5. Add the controller method in `ReportsController.php`

### Modifying PDF Layout
1. Edit the styles in `PdfExport.vue`
2. Adjust the `pdfStyles` computed property
3. Modify the PDF options in the `exportToPdf` method

### Company Branding
1. Update the `companyName` prop
2. Replace the logo placeholder with actual logo
3. Customize colors in the CSS classes

## Troubleshooting

### Common Issues

1. **PDF not generating**
   - Check browser console for errors
   - Ensure all content is properly loaded
   - Verify html2pdf.js is installed

2. **Layout issues**
   - Check CSS classes are properly applied
   - Verify table structure is correct
   - Test with different page sizes

3. **Performance issues**
   - Reduce image quality if needed
   - Limit content size for large reports
   - Use landscape orientation for wide tables

### Debug Mode
Enable debug mode by adding console logs in the `exportToPdf` method:

```javascript
console.log('PDF options:', options);
console.log('Content element:', contentElement);
```

## Future Enhancements

- **Watermark support**: Add watermarks to PDFs
- **Password protection**: Secure PDFs with passwords
- **Digital signatures**: Add digital signature support
- **Batch export**: Export multiple reports at once
- **Email integration**: Send PDFs via email
- **Cloud storage**: Save PDFs to cloud storage
- **Template system**: Customizable PDF templates
