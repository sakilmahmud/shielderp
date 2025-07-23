<div id="product-rows" class="mt-3" style="<?php echo empty($invoice_details) ? 'display: none' : ''; ?>;">
    <h4>All Items</h4>
    <table class="table table-hover table-bordered table-sm">
        <thead>
            <tr>
                <th width="31%">Product</th>
                <th width="6%">QNT</th>
                <th width="9%">Price</th>
                <th width="9%">Discount</th>
                <th width="10%">GST</th>
                <th width="9%">Unit Price</th>
                <th width="10%">Total</th>
                <th width="11%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($invoice_details)) :
                $count = 0;
                $totalQnt = 0;
            ?>
                <?php foreach ($invoice_details as $product) :
                    $count++;
                    $totalQnt += $product['quantity']; ?>
                    <tr data-product-id="<?php echo $product['product_id']; ?>">
                        <!-- <td><?= $count ?></td> -->
                        <td>
                            <b><?php echo $product['product_name']; ?></b>
                            <br>
                            <?php echo $product['product_descriptions']; ?>
                        </td>
                        <td>
                            <p><?php echo $product['quantity']; ?></p>
                            <input type="hidden" name='qnt[]' value="<?php echo $product['quantity']; ?>">
                        </td>
                        <td>
                            <p>₹<?php echo $product['price']; ?></p>
                        </td>
                        <td>
                            <?php echo ($product['discount_type'] == 1) ? "₹" . $product['discount'] : $product['discount'] . "%"; ?>
                        </td>
                        <td>
                            <p>₹<?php echo $product['gst_amount']; ?> (<?php echo $product['cgst'] + $product['sgst']; ?>%)</p>
                        </td>
                        <td>
                            <b>₹<?php echo number_format(($product['price'] + $product['gst_amount']), 2); ?></b>
                        </td>
                        <td>₹<?php echo $product['final_price']; ?></td>
                        <td width="5%" class="text-center">
                            <button type="button" class="btn btn-info btn-sm edit-item"><i class="bi bi-pencil-square"></i></button>
                            <button type="button" class="btn btn-danger btn-sm remove-item"><i class="bi bi-x-circle"></i></button>
                        </td>
                        <input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
                        <input type="hidden" name="product_descriptions[]" value="<?php echo $product['product_descriptions']; ?>">
                        <input type="hidden" name="purchase_price[]" value="<?php echo $product['price']; ?>">
                        <input type="hidden" name="discount_type[]" value="<?php echo $product['discount_type']; ?>">
                        <input type="hidden" name="discount[]" value="<?php echo $product['discount']; ?>">
                        <input type="hidden" name="cgst[]" value="<?php echo $product['cgst']; ?>">
                        <input type="hidden" name="sgst[]" value="<?php echo $product['sgst']; ?>">
                        <input type="hidden" name="gst_amount[]" value="<?php echo $product['gst_amount']; ?>">
                        <input type="hidden" name="gst_rate[]" value="<?php echo $product['cgst'] + $product['cgst']; ?>">
                        <input type="hidden" name="hsn_code[]" value="<?php echo $product['hsn_code']; ?>">
                        <input type="hidden" name="final_price[]" value="<?php echo $product['final_price']; ?>">
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <!-- <tfoot>
            <tr>
                <td colspan="3"></td>
                <td><?= $totalQnt ?></td>
                <td colspan="6"></td>
            </tr>
        </tfoot> -->
    </table>
</div>