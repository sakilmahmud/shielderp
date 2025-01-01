<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><?php echo ($isUpdate) ? "Edit " : "Add "; ?>Income</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/income'); ?>" class="btn btn-primary">All Incomes</a>
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
                            $action = base_url('admin/income/add');
                            if ($isUpdate) {
                                $action = base_url('admin/income/edit/') . $income['id'];
                            }
                            ?>
                            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                                <!-- Income Head -->
                                <div class="form-group">
                                    <label for="income_head_id">Income Head</label>
                                    <select class="form-control" id="income_head_id" name="income_head_id">
                                        <option value="">Select Income Head</option>
                                        <?php foreach ($income_heads as $head) : ?>
                                            <option value="<?php echo $head['id']; ?>" <?php echo set_select('income_head_id', $head['id'], isset($income['income_head_id']) && $income['income_head_id'] == $head['id']); ?>>
                                                <?php echo $head['head_title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('income_head_id'); ?>
                                </div>

                                <!-- Income Title -->
                                <div class="form-group">
                                    <label for="income_title">Income Title</label>
                                    <input type="text" class="form-control" id="income_title" name="income_title" value="<?php echo set_value('income_title', isset($income['income_title']) ? $income['income_title'] : ''); ?>">
                                    <?php echo form_error('income_title'); ?>
                                </div>

                                <!-- Invoice Number -->
                                <div class="form-group">
                                    <label for="invoice_no">Invoice No</label>
                                    <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo set_value('invoice_no', isset($income['invoice_no']) ? $income['invoice_no'] : ''); ?>">
                                    <?php echo form_error('invoice_no'); ?>
                                </div>

                                <!-- Transaction Date -->
                                <div class="form-group">
                                    <label for="transaction_date">Transaction Date</label>
                                    <input type="date" class="form-control" id="transaction_date" name="transaction_date" value="<?php echo set_value('transaction_date', isset($income['transaction_date']) ? $income['transaction_date'] : date('Y-m-d')); ?>">
                                    <?php echo form_error('transaction_date'); ?>
                                </div>

                                <!-- Transaction Amount -->
                                <div class="form-group">
                                    <label for="transaction_amount">Transaction Amount</label>
                                    <input type="number" step="0.01" class="form-control" id="transaction_amount" name="transaction_amount" value="<?php echo set_value('transaction_amount', isset($income['transaction_amount']) ? $income['transaction_amount'] : ''); ?>">
                                    <?php echo form_error('transaction_amount'); ?>
                                </div>

                                <!-- Payment Method -->
                                <div class="form-group">
                                    <label for="payment_method_id">Payment Method</label>
                                    <select class="form-control payment_method_id" id="payment_method_id" name="payment_method_id">
                                        <?php foreach ($payment_methods as $method) : ?>
                                            <option value="<?php echo $method['id']; ?>" <?php echo set_select('payment_method_id', $method['id'], isset($income['payment_method_id']) && $income['payment_method_id'] == $method['id']); ?>>
                                                <?php echo $method['title']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('payment_method_id'); ?>
                                </div>

                                <!-- Note -->
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea class="form-control" id="note" name="note"><?php echo set_value('note', isset($income['note']) ? $income['note'] : ''); ?></textarea>
                                    <?php echo form_error('note'); ?>
                                </div>

                                <!-- Document -->
                                <div class="form-group">
                                    <label for="documents">Upload Document</label>
                                    <input type="file" class="form-control" id="documents" name="documents">
                                    <?php if (!empty($income['documents'])) : ?>
                                        <small class="form-text text-muted">Existing File: <?php echo $income['documents']; ?></small>
                                    <?php endif; ?>
                                </div>

                                <!-- Is Refunded -->
                                <div class="form-group">
                                    <label for="is_refunded">Refunded</label>
                                    <select class="form-control" id="is_refunded" name="is_refunded">
                                        <option value="0" <?php echo set_select('is_refunded', 0, isset($income['is_refunded']) && $income['is_refunded'] == 0); ?>>No</option>
                                        <option value="1" <?php echo set_select('is_refunded', 1, isset($income['is_refunded']) && $income['is_refunded'] == 1); ?>>Yes</option>
                                    </select>
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary"><?php echo ($isUpdate) ? "Update" : "Add"; ?> Income</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>