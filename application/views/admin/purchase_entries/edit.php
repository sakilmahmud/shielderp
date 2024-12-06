<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Edit Purchase Entry</h2>
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
                                <div class="alert alert-danger">
                                    <?php echo $this->session->flashdata('message'); ?>
                                </div>
                            <?php endif; ?>
                            <form autocomplete="off" action="<?php echo base_url('admin/purchase_entries/edit/' . $purchase_entry['id']); ?>" method="post">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="is_gst">GST/Non-GST</label>
                                            <select class="form-control" id="is_gst" name="is_gst" readonly>
                                                <option value="1" <?php echo $purchase_entry['is_gst'] == 1 ? 'selected' : ''; ?>>GST</option>
                                                <option value="0" <?php echo $purchase_entry['is_gst'] == 0 ? 'selected' : ''; ?>>Non-GST</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="purchase_date">Purchase Date</label>
                                            <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo $purchase_entry['purchase_date']; ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="supplier_id">Supplier</label>
                                            <select class="form-control" id="supplier_id" name="supplier_id" readonly>
                                                <option value="">Choose a Supplier</option>
                                                <?php foreach ($suppliers as $supplier) : ?>
                                                    <option value="<?php echo $supplier['id']; ?>" <?php echo $supplier['id'] == $purchase_entry['supplier_id'] ? 'selected' : ''; ?>><?php echo $supplier['supplier_name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="invoice_no">Invoice Number</label>
                                            <input type="text" class="form-control" id="invoice_no" name="invoice_no" value="<?php echo $purchase_entry['invoice_no']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>

                                <h4>Products</h4>
                                <div class="row product-row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="mt-1 d-flex justify-content-between">
                                                <label for="product_id">Product</label>
                                                <a href="javascript:void(0)" class="text-sm add_product">Add Product</a>
                                            </div>
                                            <select class="form-control product_id">
                                                <option value="">Choose a Product</option>
                                                <?php foreach ($products as $product) : ?>
                                                    <option value="<?php echo $product['id']; ?>"><?php echo $product['name']; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="qnt">Qnt</label>
                                            <input type="number" min="1" value="1" step="1" class="form-control qnt">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label for="purchase_price">Price</label>
                                            <input type="number" autocomplete="false" min="1" class="form-control purchase_price">
                                            <input type="hidden" class="single_net_price">
                                            <input type="hidden" class="single_sale_price">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="discount_type">Disc Type</label>
                                            <select class="form-control discount_type">
                                                <option value="">No</option>
                                                <option value="1">Flat</option>
                                                <option value="2">Percent</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="discount">Discount</label>
                                            <input type="text" class="form-control discount" value="0" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="gst_rate">GST Rate</label>
                                            <select class="form-control gst_rate">
                                                <option value="0">0%</option>
                                                <option value="12">12%</option>
                                                <option value="18" selected>18%</option>
                                                <option value="28">28%</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for="gst_amount">GST Amount</label>
                                            <input type="text" class="form-control gst_amount" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="d-flex gap-3">
                                            <div class="form-group">
                                                <label for="final_price">Final Price</label>
                                                <input type="text" class="form-control final_price" readonly>
                                            </div>
                                            <div><button type="button" class="mt-4 btn btn-secondary add-product">+</button></div>
                                        </div>
                                    </div>
                                </div>
                                <div id="product-rows">
                                    <h4>All Items</h4>
                                    <table class="table table-hover table-bordered table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Price</th>
                                                <th>Net Single Price</th>
                                                <th>Discount</th>
                                                <th>GST</th>
                                                <th>Total</th>
                                                <th width="5%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($purchase_order_products as $product) : ?>
                                                <tr>
                                                    <td><?php echo $product['product_name']; ?> x <b><?php echo $product['qnt']; ?></b></td>
                                                    <td>₹<?php echo $product['purchase_price']; ?></td>
                                                    <td>₹<?php echo $product['single_net_price']; ?></td>
                                                    <td><?php echo ($product['discount_type'] === 1) ? "₹" . $product['discount'] : $product['discount'] . "%"; ?></td>
                                                    <td>₹<?php echo $product['gst_amount']; ?> (<?php echo $product['gst_rate']; ?>%)</td>
                                                    <td>₹<?php echo $product['final_price']; ?></td>
                                                    <td width="5%" class="text-center"><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>
                                                    <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
                                                    <input type="hidden" name='qnt[]' value="<?php echo $product['qnt']; ?>">
                                                    <input type="hidden" name="purchase_price[]" value="<?php echo $product['purchase_price']; ?>">
                                                    <input type="hidden" name="discount_type[]" value="<?php echo $product['discount_type']; ?>">
                                                    <input type="hidden" name="discount[]" value="<?php echo $product['discount']; ?>">
                                                    <input type="hidden" name="gst_rate[]" value="<?php echo $product['gst_rate']; ?>">
                                                    <input type="hidden" name="gst_amount[]" value="<?php echo $product['gst_amount']; ?>">
                                                    <input type="hidden" name="final_price[]" value="<?php echo $product['final_price']; ?>">
                                                    <input type="hidden" name="single_net_price[]" value="<?php echo $product['single_net_price']; ?>">
                                                    <input type="hidden" name="sale_price[]" value="<?php echo $product['single_net_price']; ?>">
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="total_amount_section">
                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="sub_total">Sub Total</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="sub_total" name="sub_total" value="<?php echo $purchase_entry['sub_total']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_discount">Total Discount</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_discount" name="total_discount" value="<?php echo $purchase_entry['total_discount']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_gst">Total GST Amount</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_gst" name="total_gst" value="<?php echo $purchase_entry['total_gst']; ?>" readonly>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-3 justify-content-end">
                                        <div class="form-group">
                                            <label for="total_amount">Grand Total</label>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="total_amount" name="total_amount" value="<?php echo $purchase_entry['total_amount']; ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary edit_purchase">Update Purchase Entry</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="form-group">
                        <div class="mt-1 d-flex justify-content-between">
                            <label for="category_id">Category</label>
                            <a href="javascript:void(0)" class="text-sm add_category">Add Category</a>
                        </div>
                        <select class="form-control category_id" name="category_id">
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category) { ?>
                                <option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="mt-1 d-flex justify-content-between">
                            <label for="brand_id">Brand</label>
                            <a href="javascript:void(0)" class="text-sm add_brand">Add Brand</a>
                        </div>
                        <select class="form-control brand_id" id="brand_id" name="brand_id">
                            <option value="">Select Brand</option>
                            <?php foreach ($brands as $brand) { ?>
                                <option value="<?php echo $brand['id']; ?>"><?php echo $brand['brand_name']; ?></option>
                            <?php } ?>
                        </select>
                        <?php echo form_error('brand_id'); ?>
                    </div>
                    <div class="form-group">
                        <label for="name">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" role="dialog" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCategoryModalLabel">Add Category</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addCategoryForm">
                    <div class="form-group">
                        <label for="category_name">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Brand Modal -->
<div class="modal fade" id="addBrandModal" tabindex="-1" role="dialog" aria-labelledby="addBrandModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBrandModalLabel">Add Brand</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addBrandForm">
                    <div class="form-group">
                        <label for="brand_name">Brand Name</label>
                        <input type="text" class="form-control" id="brand_name" name="brand_name" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Brand</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let addProduct_url = "<?php echo base_url('admin/products/add-ajax'); ?>";
    let addCategory_url = "<?php echo base_url('admin/categories/add-ajax'); ?>";
    let addBrand_url = "<?php echo base_url('admin/brands/add-ajax'); ?>";
</script>
<script src="<?php echo base_url('assets/admin/dist/js/purchase_order.js') ?>"></script>