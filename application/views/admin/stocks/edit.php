<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Edit Stock</h2>
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
                            <form action="<?php echo base_url('admin/stocks/edit/' . $stock['id']); ?>" method="post">
                                <div class="form-group mb-3">
                                    <label for="product_id">Product</label>
                                    <select class="form-control" id="product_id" name="product_id" required>
                                        <option value="">Choose a Product</option>
                                        <?php foreach ($products as $product) : ?>
                                            <option value="<?php echo $product['id']; ?>" <?php echo ($product['id'] == $stock['product_id']) ? 'selected' : ''; ?>>
                                                <?php echo $product['name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php echo form_error('product_id'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="purchase_price">Purchase Price</label>
                                    <input type="text" class="form-control" id="purchase_price" name="purchase_price" value="<?php echo set_value('purchase_price', $stock['purchase_price']); ?>">
                                    <?php echo form_error('purchase_price'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="sale_price">Sale Price</label>
                                    <input type="text" class="form-control" id="sale_price" name="sale_price" value="<?php echo set_value('sale_price', $stock['sale_price']); ?>">
                                    <?php echo form_error('sale_price'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="purchase_date">Purchase Date</label>
                                    <input type="date" class="form-control" id="purchase_date" name="purchase_date" value="<?php echo set_value('purchase_date', $stock['purchase_date']); ?>">
                                    <?php echo form_error('purchase_date'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="quantity">Quantity</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" value="<?php echo set_value('quantity', $stock['quantity']); ?>">
                                    <?php echo form_error('quantity'); ?>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="batch_no">Batch No</label>
                                    <input type="text" class="form-control" id="batch_no" name="batch_no" value="<?php echo set_value('batch_no', $stock['batch_no']); ?>">
                                </div>
                                <button type="submit" class="btn btn-primary">Update Stock</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>