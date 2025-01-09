<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Invoices</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/invoices/create'); ?>" class="btn btn-primary">Create Invoice</a>
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
                            <?php if ($this->session->flashdata('message')) : ?>
                                <div class="alert alert-success">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('error_message')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error_message'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($invoices)) : ?>
                                <div class="table-responsive">
                                    <table id="invoicesTable" class="table table-sm table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Invoice No</th>
                                                <th>Customer Name</th>
                                                <th>Invoice Date</th>
                                                <th>Total Amount</th>
                                                <th>Created by</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($invoices as $invoice) : ?>
                                                <tr>
                                                    <td><?php echo $invoice['id']; ?></td>
                                                    <td><?php echo $invoice['invoice_no']; ?></td>
                                                    <td><?php echo $invoice['customer_name']; ?></td>
                                                    <td><?php echo date('Y-m-d', strtotime($invoice['invoice_date'])); ?></td>
                                                    <td>₹<?php echo number_format($invoice['total_amount'], 2); ?></td>
                                                    <td><?php echo $invoice['created_by_name']; ?></td>
                                                    <td>
                                                        <?php if ($invoice['payment_status'] == '1') : ?>
                                                            <span class="badge badge-success">Paid</span>
                                                        <?php elseif ($invoice['payment_status'] == '0') : ?>
                                                            <span class="badge badge-warning">Pending</span>
                                                        <?php elseif ($invoice['payment_status'] == '2') : ?>
                                                            <span class="badge badge-info">Partial</span>
                                                        <?php elseif ($invoice['payment_status'] == '3') : ?>
                                                            <span class="badge badge-danger">Return</span>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td>
                                                        <a href="<?php echo base_url('admin/invoices/view/' . $invoice['id']); ?>" class="btn btn-info btn-sm">View</a>
                                                        <a href="<?php echo base_url('admin/invoices/edit/' . $invoice['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="<?php echo base_url('admin/invoices/delete/' . $invoice['id']); ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this invoice? This action cannot be undone.');">Delete</a>
                                                        <a href="<?php echo base_url('admin/invoices/print/' . $invoice['id']); ?>" target="_blank" class="btn btn-primary btn-sm">Print</a>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else : ?>
                                <div class="alert alert-info">No invoices found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('#invoicesTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>