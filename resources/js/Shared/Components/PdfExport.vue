<template>
  <div>
    <!-- PDF Export Button -->
    <button
      @click="exportToPdf"
      :disabled="isExporting"
      class="inline-flex items-center px-4 py-2 bg-emerald-600 text-white rounded-lg shadow-lg hover:bg-emerald-700 hover:shadow-xl transition-all font-medium gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
    >
      <svg v-if="isExporting" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
      </svg>
      <i v-else class="fas fa-download"></i>
      {{ isExporting ? 'Generating PDF...' : 'Export PDF' }}
    </button>

    <!-- Success Notification -->
    <div
      v-if="showSuccess"
      class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2"
    >
      <i class="fas fa-check-circle"></i>
      <span>PDF exported successfully!</span>
      <button @click="showSuccess = false" class="ml-2 hover:text-green-200">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Error Notification -->
    <div
      v-if="showError"
      class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center gap-2"
    >
      <i class="fas fa-exclamation-circle"></i>
      <span>PDF export failed. Please try again.</span>
      <button @click="showError = false" class="ml-2 hover:text-red-200">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <!-- Hidden PDF Content -->
    <div ref="pdfContent" class="hidden">
      <div class="pdf-container" :style="pdfStyles">
        <!-- Header -->
        <div class="pdf-header">
          <div class="company-info">
            <h1 class="company-name">{{ companyName }}</h1>
            <p class="company-subtitle">{{ reportTitle }}</p>
            <p class="report-date">{{ reportDate }}</p>
          </div>
          <div class="logo-section">
            <div class="logo-container">
              <div class="logo-icon">
                <i class="fas fa-chart-line"></i>
              </div>
              <div class="logo-text">
                <span class="logo-title">FINANX</span>
                <span class="logo-subtitle">Financial Management</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Report Content -->
        <div class="pdf-content">
          <slot name="content"></slot>
        </div>

        <!-- Footer -->
        <div class="pdf-footer">
          <div class="footer-left">
            <p class="footer-text">Generated on: {{ currentDate }}</p>
            <p class="footer-text">Page {{ currentPage }} of {{ totalPages }}</p>
          </div>
          <div class="footer-right">
            <p class="footer-text">{{ companyName }}</p>
            <p class="footer-text">Financial Management System</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import html2pdf from 'html2pdf.js';

const props = defineProps({
  companyName: {
    type: String,
    default: 'Finanx Organization'
  },
  reportTitle: {
    type: String,
    required: true
  },
  reportDate: {
    type: String,
    required: true
  },
  filename: {
    type: String,
    default: 'report.pdf'
  },
  orientation: {
    type: String,
    default: 'portrait', // 'portrait' or 'landscape'
    validator: (value) => ['portrait', 'landscape'].includes(value)
  },
  pageSize: {
    type: String,
    default: 'A4',
    validator: (value) => ['A4', 'A3', 'Letter', 'Legal'].includes(value)
  },
  selectedCurrency: {
    type: Object,
    default: null
  }
});

const emit = defineEmits(['export-start', 'export-complete', 'export-error']);

const isExporting = ref(false);
const pdfContent = ref(null);
const currentPage = ref(1);
const totalPages = ref(1);
const showSuccess = ref(false);
const showError = ref(false);

const currentDate = computed(() => {
  return new Date().toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
});

const pdfStyles = computed(() => ({
  fontFamily: 'Georgia, "Times New Roman", serif',
  fontSize: '11px',
  lineHeight: '1.6',
  color: '#2d3748',
  maxWidth: props.orientation === 'landscape' ? '297mm' : '210mm',
  margin: '0 auto',
  padding: '15mm',
  backgroundColor: '#ffffff',
  boxShadow: '0 0 20px rgba(0,0,0,0.1)'
}));

const exportToPdf = async () => {
  if (isExporting.value) return;

  isExporting.value = true;
  showSuccess.value = false;
  showError.value = false;
  emit('export-start');

  try {
    // Show the PDF content temporarily
    const contentElement = pdfContent.value;
    contentElement.classList.remove('hidden');

    // Configure PDF options
    const options = {
      margin: [8, 8, 8, 8],
      filename: props.filename,
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: {
        scale: 2,
        useCORS: true,
        letterRendering: true,
        allowTaint: true
      },
      jsPDF: {
        unit: 'mm',
        format: props.pageSize,
        orientation: props.orientation
      },
      pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
    };

    // Generate PDF
    await html2pdf().set(options).from(contentElement).save();

    // Hide the PDF content
    contentElement.classList.add('hidden');

    // Show success notification
    showSuccess.value = true;
    setTimeout(() => {
      showSuccess.value = false;
    }, 3000);

    emit('export-complete');
  } catch (error) {
    console.error('PDF export error:', error);

    // Show error notification
    showError.value = true;
    setTimeout(() => {
      showError.value = false;
    }, 5000);

    emit('export-error', error);
  } finally {
    isExporting.value = false;
  }
};

// Expose the export function for external use
defineExpose({
  exportToPdf
});
</script>

<style scoped>
.pdf-container {
  background: white;
  color: #2d3748;
  font-family: Georgia, "Times New Roman", serif;
  min-height: 297mm;
  position: relative;
}

/* Header Styles */
.pdf-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 25px;
  padding-bottom: 20px;
  border-bottom: 3px solid #e2e8f0;
  position: relative;
}

.pdf-header::after {
  content: '';
  position: absolute;
  bottom: -3px;
  left: 0;
  right: 0;
  height: 1px;
  background: linear-gradient(90deg, #4299e1, #48bb78, #ed8936, #f56565);
}

.company-info {
  flex: 1;
}

.company-name {
  font-size: 28px;
  font-weight: bold;
  color: #1a202c;
  margin: 0 0 8px 0;
  font-family: 'Georgia', serif;
  letter-spacing: 0.5px;
}

.company-subtitle {
  font-size: 18px;
  color: #4a5568;
  margin: 0 0 6px 0;
  font-weight: 600;
  font-style: italic;
}

.report-date {
  font-size: 14px;
  color: #718096;
  margin: 0;
  font-weight: 500;
}

.logo-section {
  width: 120px;
  height: 80px;
}

.logo-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border-radius: 8px;
  padding: 10px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.logo-icon {
  font-size: 24px;
  color: white;
  margin-bottom: 5px;
}

.logo-text {
  text-align: center;
}

.logo-title {
  display: block;
  font-size: 12px;
  font-weight: bold;
  color: white;
  letter-spacing: 1px;
}

.logo-subtitle {
  display: block;
  font-size: 8px;
  color: rgba(255,255,255,0.9);
  font-weight: 500;
}

/* Content Styles */
.pdf-content {
  margin-bottom: 25px;
  min-height: 200mm;
}

/* Footer Styles */
.pdf-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 15px;
  border-top: 2px solid #e2e8f0;
  font-size: 9px;
  color: #718096;
  position: absolute;
  bottom: 15mm;
  left: 15mm;
  right: 15mm;
}

.footer-left, .footer-right {
  text-align: center;
}

.footer-text {
  margin: 2px 0;
  font-weight: 500;
}

/* PDF-specific styles for tables */
.pdf-content table {
  width: 100%;
  border-collapse: collapse;
  margin: 20px 0;
  font-size: 10px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
  border-radius: 6px;
  overflow: hidden;
}

.pdf-content th,
.pdf-content td {
  border: 1px solid #e2e8f0;
  padding: 12px 8px;
  text-align: left;
  font-size: 10px;
  line-height: 1.4;
}

.pdf-content th {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  font-weight: bold;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.pdf-content tr:nth-child(even) {
  background-color: #f7fafc;
}

.pdf-content tr:hover {
  background-color: #edf2f7;
}

/* PDF-specific styles for sections */
.pdf-content .pdf-section {
  margin-bottom: 30px;
  page-break-inside: avoid;
}

.pdf-content .pdf-section-title {
  font-size: 16px;
  font-weight: bold;
  color: #2d3748;
  margin-bottom: 15px;
  padding: 12px 15px;
  background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
  border-left: 4px solid #4299e1;
  border-radius: 4px;
  font-family: 'Georgia', serif;
  letter-spacing: 0.3px;
}

.pdf-content .pdf-summary-section {
  margin-top: 30px;
  padding: 20px;
  background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.pdf-content .pdf-summary-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 12px;
}

/* PDF-specific styles for summary boxes */
.pdf-content .pdf-summary {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 16px;
  margin: 6px 0;
  border: 1px solid #e2e8f0;
  background: white;
  border-radius: 6px;
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.pdf-content .pdf-summary-label {
  font-weight: bold;
  color: #2d3748;
  font-size: 11px;
}

.pdf-content .pdf-summary-value {
  font-weight: bold;
  color: #059669;
  font-size: 11px;
}

.pdf-content .pdf-total-row {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
  font-weight: bold;
  font-size: 11px;
}

.pdf-content .text-right {
  text-align: right;
}

.pdf-content .text-green-600 {
  color: #059669;
  font-weight: bold;
}

.pdf-content .text-red-600 {
  color: #dc2626;
  font-weight: bold;
}

/* PDF-specific styles for account sections */
.pdf-content .pdf-account-section {
  margin-bottom: 40px;
  page-break-inside: avoid;
  border: 1px solid #e2e8f0;
  border-radius: 8px;
  overflow: hidden;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.pdf-content .pdf-account-section .pdf-section-title {
  margin: 0;
  border-radius: 0;
  border-left: none;
  border-top: none;
  border-right: none;
  background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
  color: white;
}

/* PDF-specific styles for different report types */
.pdf-content .pdf-balance-sheet,
.pdf-content .pdf-income-statement,
.pdf-content .pdf-cash-flow,
.pdf-content .pdf-trial-balance,
.pdf-content .pdf-general-ledger,
.pdf-content .pdf-all-reports {
  display: grid;
  grid-template-columns: 1fr;
  gap: 25px;
}

/* Enhanced table styling */
.pdf-content table tbody tr:last-child {
  border-bottom: 2px solid #e2e8f0;
}

.pdf-content table tfoot tr {
  background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
  color: white;
  font-weight: bold;
}

.pdf-content table tfoot td {
  border-color: #38a169;
}

/* Hide elements that shouldn't appear in PDF */
.pdf-container .no-pdf {
  display: none !important;
}

/* Ensure proper page breaks */
.pdf-container .page-break {
  page-break-before: always;
}

.pdf-container .avoid-break {
  page-break-inside: avoid;
}

/* Additional styling for better visual hierarchy */
.pdf-content h2 {
  font-size: 14px;
  font-weight: bold;
  color: #1a202c;
  margin: 20px 0 10px 0;
  font-family: 'Georgia', serif;
  letter-spacing: 0.3px;
}

.pdf-content h3 {
  font-size: 12px;
  font-weight: bold;
  color: #2d3748;
  margin: 15px 0 8px 0;
  font-family: 'Georgia', serif;
}

/* Enhanced spacing and typography */
.pdf-content p {
  margin: 8px 0;
  line-height: 1.5;
}

.pdf-content .highlight {
  background-color: #fef5e7;
  padding: 8px 12px;
  border-radius: 4px;
  border-left: 3px solid #ed8936;
  margin: 10px 0;
}

/* Status indicators */
.pdf-content .status-balanced {
  color: #059669;
  font-weight: bold;
  background: #f0fff4;
  padding: 4px 8px;
  border-radius: 4px;
  border: 1px solid #9ae6b4;
}

.pdf-content .status-unbalanced {
  color: #dc2626;
  font-weight: bold;
  background: #fef2f2;
  padding: 4px 8px;
  border-radius: 4px;
  border: 1px solid #fecaca;
}
</style>
