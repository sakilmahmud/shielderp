<!-- Add Expense -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo ($isUpdate) ? "Edit " : "Add "; ?>Expense</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/expense'); ?>" class="btn btn-primary">All Expenses</a>
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
                            <?php
                            $action = base_url('admin/expense/add');
                            if ($isUpdate) {
                                $action = base_url('admin/expense/edit/' . $expense['id']);
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expense_title">Expense Title</label>
                                            <input type="text" class="form-control" id="expense_title" name="expense_title" value="<?php echo set_value('expense_title', isset($expense['expense_title']) ? $expense['expense_title'] : ''); ?>">
                                            <?php echo form_error('expense_title'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="expense_head_id">Expense Head</label>
                                            <select name="expense_head_id" class="form-control">
                                                <option value="">Select Expense Head</option>
                                                <?php foreach ($expenseHeads as $head): ?>
                                                    <option value="<?php echo $head['id']; ?>" <?php echo (isset($expense['expense_head_id']) && $expense['expense_head_id'] == $head['id']) ? 'selected' : ''; ?>><?php echo $head['head_title']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transaction_amount">Amount</label>
                                            <input type="number" class="form-control" id="transaction_amount" name="transaction_amount" min="0" value="<?php echo set_value('transaction_amount', isset($expense['transaction_amount']) ? $expense['transaction_amount'] : ''); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="transaction_date">Transaction Date</label>
                                            <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="<?php echo set_value('transaction_date', isset($expense['transaction_date']) ? $expense['transaction_date'] : date('Y-m-d')); ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="payment_method_id">Payment Method</label>
                                            <select name="payment_method_id" class="form-control">
                                                <?php foreach ($paymentMethods as $method): ?>
                                                    <option value="<?php echo $method['id']; ?>" <?php echo (isset($expense['payment_method_id']) && $expense['payment_method_id'] == $method['id']) ? 'selected' : ''; ?>><?php echo $method['title']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Invoice Number -->
                                        <div class="form-group">
                                            <label for="invoice_no">Invoice No</label>
                                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo set_value('invoice_no', isset($expense['invoice_no']) ? $expense['invoice_no'] : ''); ?>">
                                            <?php echo form_error('invoice_no'); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <!-- Document -->
                                        <div class="form-group">
                                            <label for="documents">Upload Document</label>
                                            <input type="file" class="form-control" id="documents" name="documents">
                                            <?php if (!empty($income['documents'])) : ?>
                                                <small class="form-text text-muted">Existing File: <?php echo $income['documents']; ?></small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="note">Note</label>
                                            <textarea class="form-control" id="note" name="note"><?php echo set_value('note', isset($expense['note']) ? $expense['note'] : ''); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary"><?php echo ($isUpdate) ? "Update" : "Add"; ?> Expense</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // Set focus to the Service Description field on page load
        $('#expense_title').focus();
    });
</script>