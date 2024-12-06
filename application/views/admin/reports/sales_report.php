<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Sales Report</h1>
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

                            <?php if (!empty($sales_data)) : ?>
                                <table id="salesTable" class="table table-sm table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th>Customer Name</th>
                                            <th>Mobile</th>
                                            <th>Total Amount</th>
                                            <th>Payment Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($sales_data as $sale) : ?>
                                            <tr>
                                                <td><?php echo $sale['id']; ?></td>
                                                <td><?php echo $sale['invoice_no']; ?></td>
                                                <td><?php echo date('d-m-Y', strtotime($sale['invoice_date'])); ?></td>
                                                <td><?php echo $sale['customer_name']; ?></td>
                                                <td><?php echo $sale['mobile']; ?></td>
                                                <td>₹<?php echo number_format($sale['total_amount'], 2); ?></td>
                                                <td>
                                                    <?php
                                                    switch ($sale['payment_status']) {
                                                        case 0:
                                                            echo 'Pending';
                                                            break;
                                                        case 1:
                                                            echo 'Paid';
                                                            break;
                                                        case 2:
                                                            echo 'Partial Paid';
                                                            break;
                                                        case 3:
                                                            echo 'Return';
                                                            break;
                                                    }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No sales data found.</p>
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
        $('#salesTable').DataTable({
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