<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid d-flex justify-content-between align-items-center my-2">
            <h2>Stock Managements</h2>
            <a href="<?php echo base_url('admin/stocks/add'); ?>" class="btn btn-primary">Add Stock</a>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">

                            <form method="get" action="<?php echo base_url('admin/stocks'); ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="category_id">Category</label>
                                            <select name="category_id" id="category_id" class="form-control category_id">
                                                <option value="">All Categories</option>
                                                <?php foreach ($categories as $category): ?>
                                                    <option value="<?php echo $category['id']; ?>" <?php echo ($this->input->get('category_id') == $category['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $category['name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="brand_id">Brand</label>
                                            <select name="brand_id" id="brand_id" class="form-control brand_id">
                                                <option value="">All Brands</option>
                                                <?php foreach ($brands as $brand): ?>
                                                    <option value="<?php echo $brand['id']; ?>" <?php echo ($this->input->get('brand_id') == $brand['id']) ? 'selected' : ''; ?>>
                                                        <?php echo $brand['brand_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>&nbsp;</label>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <a href="<?php echo base_url('admin/stocks'); ?>" class="btn btn-secondary">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
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
                                            <th>Category</th>
                                            <th>Brand</th>
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
                                                <td><?php echo $stock['cat_name']; ?></td>
                                                <td><?php echo $stock['brand_name']; ?></td>
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
                                                        <?php if ($this->session->userdata('role') == 1) : ?>
                                                            <a href="<?php echo base_url('admin/stocks/edit/' . $stock['id']); ?>" class="btn btn-warning btn-sm">Edit</a>
                                                            <a href="<?php echo base_url('admin/stocks/delete/' . $stock['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this stock?')">Delete</a>
                                                        <?php else: ?>
                                                            -
                                                        <?php endif; ?>
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