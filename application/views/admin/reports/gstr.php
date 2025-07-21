<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <h1>GSTR Reports</h1>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <form id="gstrReportForm" action="<?php echo base_url('admin/reports/gstr/generate_json'); ?>" method="post">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="from_date">From Date</label>
                                    <input type="date" class="form-control" id="from_date" name="from_date" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="to_date">To Date</label>
                                    <input type="date" class="form-control" id="to_date" name="to_date" required>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary" name="report_type" value="gstr1">Generate GSTR-1 JSON</button>
                                <button type="submit" class="btn btn-info" name="report_type" value="gstr3b">Generate GSTR-3B JSON</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>