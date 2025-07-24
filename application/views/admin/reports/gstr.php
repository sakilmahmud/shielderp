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
                    <form id="gstrReportForm" action="<?= base_url('admin/reports/gstr/generate_json'); ?>" method="post">
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="report_type">Report Type</label>
                                    <select class="form-control" id="report_type" name="report_type" required>
                                        <option value="">Select Report Type</option>
                                        <option value="gstr1">GSTR-1</option>
                                        <option value="gstr3b">GSTR-3B</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">Generate JSON</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h3 class="card-title">Generated Reports</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Report Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>File Name</th>
                                    <th>Generated At</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($generated_reports)) : ?>
                                    <?php foreach ($generated_reports as $report) : ?>
                                        <tr>
                                            <td><?= strtoupper($report['report_type']); ?></td>
                                            <td><?= $report['from_date']; ?></td>
                                            <td><?= $report['to_date']; ?></td>
                                            <td><?= $report['file_name']; ?></td>
                                            <td><?= $report['created_at']; ?></td>
                                            <td>
                                                <?php if ($report['status'] == 'success') : ?>
                                                    <span class="badge badge-success">Success</span>
                                                <?php else : ?>
                                                    <span class="badge badge-danger">Failed</span>
                                                    <?php if (!empty($report['error_message'])) : ?>
                                                        <br><small><?= $report['error_message']; ?></small>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($report['status'] == 'success') : ?>
                                                    <a href="<?= base_url('admin/reports/gstr/download_report/' . $report['id']); ?>" class="btn btn-sm btn-primary">Download</a>
                                                <?php else : ?>
                                                    N/A
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else : ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No reports generated yet.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>