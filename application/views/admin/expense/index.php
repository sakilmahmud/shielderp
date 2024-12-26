<!-- View for displaying expenses -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Expenses</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/expense/add'); ?>" class="btn btn-primary">Add Expense</a>
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
                            <table id="commonTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Expense Title</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($expenses as $expense): ?>
                                        <tr>
                                            <td><?php echo $expense['id']; ?></td>
                                            <td><?php echo $expense['expense_title']; ?></td>
                                            <td><?php echo $expense['transaction_amount']; ?></td>
                                            <td><?php echo $expense['transaction_date']; ?></td>
                                            <td><?php echo ($expense['status'] == 1) ? 'Active' : 'Inactive'; ?></td>
                                            <td>
                                                <a href="<?php echo base_url('admin/expense/edit/' . $expense['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="<?php echo base_url('admin/expense/delete/' . $expense['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete?');">Delete</a>
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