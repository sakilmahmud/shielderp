<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>GST Report</h1>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <form id="gstReportForm" method="POST" action="<?= base_url('admin/reports/gstReport') ?>">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <label for="fromDate">From Date</label>
                                        <input type="date" class="form-control" id="fromDate" name="from_date" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="toDate">To Date</label>
                                        <input type="date" class="form-control" id="toDate" name="to_date" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="gstType">GST Type</label>
                                        <select class="form-control" id="gstType" name="gst_type" required>
                                            <option value="GSTR-1">GSTR-1</option>
                                            <option value="GSTR3B">GSTR3B</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-block">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="gstReportTable" class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Customer Name</th>
                                            <th>Total Amount</th>
                                            <th>GST Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($gst_report_data)) : ?>
                                            <?php foreach ($gst_report_data as $report) : ?>
                                                <tr>
                                                    <td><?= $report['invoice_no']; ?></td>
                                                    <td><?= date('d-m-Y', strtotime($report['invoice_date'])); ?></td>
                                                    <td><?= $report['customer_name']; ?></td>
                                                    <td>₹<?= number_format($report['total_amount'], 2); ?></td>
                                                    <td>₹<?= number_format($report['total_gst'], 2); ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php else : ?>
                                            <tr>
                                                <td colspan="5">No data found.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Export buttons -->
                            <div class="text-right mt-3">
                                <button id="exportExcel" class="btn btn-success">Export to Excel</button>
                                <button id="exportPDF" class="btn btn-danger">Export to PDF</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#gstReportTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });

        // Export to Excel
        $('#exportExcel').on('click', function() {
            window.location.href = '<?= base_url("ReportsController/exportToExcel") ?>';
        });

        // Export to PDF
        $('#exportPDF').on('click', function() {
            window.location.href = '<?= base_url("ReportsController/exportToPDF") ?>';
        });
    });
</script>