<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Purchase Entries</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/purchase_entries/add'); ?>" class="btn btn-primary">Add Purchase Entry</a>
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

                            <?php if (!empty($purchase_entries)) : ?>
                                <table class="table table-sm table-striped table-bordered" id="commonTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Supplier</th>
                                            <th>Purchase Date</th>
                                            <th>Invoice No</th>
                                            <th>Total Amount</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($purchase_entries as $entry) : ?>
                                            <tr>
                                                <td><?php echo $entry['id']; ?></td>
                                                <td><?php echo $entry['supplier_name']; ?></td>
                                                <td><?php echo $entry['purchase_date']; ?></td>
                                                <td><?php echo $entry['invoice_no']; ?></td>
                                                <td><?php echo $entry['total_amount']; ?></td>
                                                <td>
                                                    <a href="<?php echo base_url('admin/purchase_entries/edit/' . $entry['id']); ?>"
                                                        class="btn btn-info btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/purchase_entries/delete/' . $entry['id']); ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure you want to delete this purchase entry?');">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-info">No purchase entries found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>