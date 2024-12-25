<style>
    .sale-price {
        color: red;
        font-weight: bold;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>List of Products</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/products/add'); ?>" class="btn btn-primary">Add Product</a>
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
                            <?php if ($this->session->flashdata('duplicate')) : ?>
                                <div class="alert alert-warning">
                                    <?php echo $this->session->flashdata('duplicate'); ?>
                                </div>
                            <?php endif; ?>
                            <?php if ($this->session->flashdata('update')) : ?>
                                <div class="alert alert-info">
                                    <?php echo $this->session->flashdata('update'); ?>
                                </div>
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

                            <?php if (!empty($products)) : ?>
                                <table class="table table-sm table-striped table-bordered" id="commonTable">

                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th></th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Stocks</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product) :
                                            $getProductStocks = getProductStocks($product['id']);
                                            if (!empty($getProductStocks)) {
                                                $total_quantity = $getProductStocks['total_quantity'];
                                                $total_available_stocks = $getProductStocks['total_available_stocks'];
                                            } else {

                                                $total_quantity = 0;
                                                $total_available_stocks = 0;
                                            }
                                            $endpoint = ($product['slug'] != "") ? $product['slug'] : $product['id'];
                                        ?>
                                            <tr>
                                                <td><?php echo $product['id']; ?></td>
                                                <td>
                                                    <div class="d-flex justify-content-center">
                                                        <img src="<?php echo base_url('uploads/products/' . $product['featured_image']); ?>" alt="Featured Image" style="max-width: 50px;">
                                                    </div>
                                                </td>
                                                <td><a href="<?php echo base_url('products/' . $endpoint); ?>" target="_blank"><?php echo $product['name']; ?></a></td>
                                                <td><?php echo $product['category_name']; ?></td>
                                                <td>
                                                    <?php echo ($product['sale_price'] > 0)
                                                        ? '<del>₹' . number_format($product['regular_price'], 2) . '</del> <span class="sale-price">₹' . number_format($product['sale_price'], 2) . '</span>'
                                                        : '₹' . number_format($product['regular_price'], 2); ?>
                                                </td>


                                                <td>
                                                    Total Purchased: <?php echo $total_quantity; ?><br>
                                                    In stocks: <?php echo $total_available_stocks; ?>
                                                </td>

                                                <td>
                                                    <a href="<?php echo base_url('admin/products/edit/') . $product['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                    <a href="<?php echo base_url('admin/products/delete/') . $product['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else : ?>
                                <p>No products found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>