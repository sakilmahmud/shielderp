<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Purchase Report</h1>
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

                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('error'); ?>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($purchase_data)) : ?>
                                <table id="purchaseTable" class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Invoice No</th>
                                            <th>Purchase Date</th>
                                            <th>Supplier Name</th>
                                            <th>Total Amount</th>
                                            <th>GST Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchase_data as $purchase) : ?>
                                            <tr>
                                                <td><?php echo $purchase['id']; ?></td>
                                                <td><?php echo $purchase['invoice_no']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($purchase['purchase_date'])); ?></td>
                                                <td><?php echo $purchase['supplier_name']; ?></td>
                                                <td>₹<?php echo number_format($purchase['total_amount'], 2); ?></td>
                                                <td>
                                                    <?php echo $purchase['is_gst'] ? 'GST' : 'Non-GST'; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No purchase data found.</p>
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
        $('#purchaseTable').DataTable({
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