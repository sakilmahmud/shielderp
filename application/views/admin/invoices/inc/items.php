<div id="product-rows">
    <h4>All Items</h4>
    <table class="table table-hover table-bordered table-sm">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Discount</th>
                <th>GST</th>
                <th>Total</th>
                <th width="10%"></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($invoice_details)) : ?>
                <?php foreach ($invoice_details as $product) : ?>
                    <tr data-product-id="<?php echo $product['product_id']; ?>">
                        <td>
                            <?php echo $product['product_name']; ?> x <b><?php echo $product['quantity']; ?></b>

                            <input type="hidden" name='qnt[]' value="<?php echo $product['quantity']; ?>">
                        </td>
                        <td>
                            <p>₹<?php echo $product['price']; ?></p>
                        </td>
                        <td>
                            <?php echo ($product['discount_type'] == 1) ? "₹" . $product['discount'] : $product['discount'] . "%"; ?>
                        </td>
                        <td>
                            <p>₹<?php echo $product['gst_amount']; ?> (<?php echo $product['gst_rate']; ?>%)</p>
                        </td>
                        <td>₹<?php echo $product['final_price']; ?></td>
                        <td width="5%" class="text-center">
                            <button type="button" class="btn btn-info btn-sm edit-item">Edit</button>
                            <button type="button" class="btn btn-danger btn-sm remove-item">X</button>
                        </td>
                        <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="product_descriptions[]" value="<?php echo $product['product_descriptions']; ?>">
                        <input type="hidden" name="purchase_price[]" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="discount_type[]" value="<?php echo $product['discount_type']; ?>">
                        <input type="hidden" name="discount[]" value="<?php echo $product['discount']; ?>">
                        <input type="hidden" name="gst_rate[]" value="<?php echo $product['gst_rate']; ?>">
                        <input type="hidden" name="gst_amount[]" value="<?php echo $product['gst_amount']; ?>">
                        <input type="hidden" name="final_price[]" value="<?php echo $product['final_price']; ?>">
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>