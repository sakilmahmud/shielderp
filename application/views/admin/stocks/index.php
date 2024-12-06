<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Stock Management</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/stocks/add'); ?>" class="btn btn-primary">Add Stock</a>
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
                            <?php if (!empty($error)) : ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>
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

                            <?php if (!empty($stocks)) : ?>
                                <table class="table table-sm table-striped table-bordered" id="commonTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Product</th>
                                            <th>PP</th>
                                            <th>SP</th>
                                            <th>Purchase Date</th>
                                            <th>Qnty</th>
                                            <th>Available Stock</th>
                                            <th>Supplier</th>
                                            <th>Batch No</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($stocks as $stock) : ?>
                                            <tr>
                                                <td><?php echo $stock['id']; ?></td>
                                                <td><?php echo $stock['product_name']; ?></td>
                                                <td><?php echo $stock['purchase_price']; ?></td>
                                                <td><?php echo $stock['sale_price']; ?></td>
                                                <td><?php echo $stock['purchase_date']; ?></td>
                                                <td><?php echo $stock['quantity']; ?></td>
                                                <td><?php echo $stock['available_stock']; ?></td>
                                                <td><?php echo ($stock['supplier_name'] != "") ? $stock['supplier_name'] : "Inhouse Stocks" ?></td>
                                                <td><?php echo $stock['batch_no']; ?></td>
                                                <td>
                                                    <?php if ($stock['supplier_name'] != "") : ?>
                                                        <p class="text-center">-</p>
                                                    <?php else : ?>
                                                        <a href="<?php echo base_url('admin/stocks/edit/' . $stock['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                        <a href="<?php echo base_url('admin/stocks/delete/' . $stock['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this stock?')">Delete</a>
                                                    <?php endif ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <div class="alert alert-info">No stocks found.</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>