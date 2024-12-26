<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Incomes</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/income/add'); ?>" class="btn btn-primary">Add Income</a>
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
                            <table id="commonTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Income Title</th>
                                        <th>Amount</th>
                                        <th>Invoice No</th>
                                        <th>Transaction Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($incomes as $income) : ?>
                                        <tr>
                                            <td><?php echo $income['id']; ?></td>
                                            <td><?php echo $income['income_title']; ?></td>
                                            <td><?php echo $income['transaction_amount']; ?></td>
                                            <td><?php echo $income['invoice_no']; ?></td>
                                            <td><?php echo $income['transaction_date']; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/income/edit/' . $income['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="<?php echo base_url('admin/income/delete/' . $income['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>