<div class="d-flex gap-3 justify-content-end mb-3">
    <div class="form-group">
        <label for="sub_total">Sub Total</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="sub_total" name="sub_total" value="<?php echo isset($invoice['sub_total']) ? $invoice['sub_total'] : ''; ?>" readonly>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mb-3">
    <div class="form-group">
        <label for="total_discount">Total Discount</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="total_discount" name="total_discount" value="<?php echo isset($invoice['total_discount']) ? $invoice['total_discount'] : ''; ?>" readonly>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mb-3">
    <div class="form-group">
        <label for="total_gst">Total GST Amount</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="total_gst" name="total_gst" value="<?php echo isset($invoice['total_gst']) ? $invoice['total_gst'] : ''; ?>" readonly>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mb-3">
    <div class="form-group">
        <label for="round_off">Round Off</label>
    </div>
    <div class="form-group">
        <input type="text" id="round_off" name="round_off" class="form-control" value="<?php echo isset($invoice['round_off']) ? $invoice['round_off'] : ''; ?>" readonly>
    </div>
</div>

<div class="d-flex gap-3 justify-content-end mb-3">
    <div class="form-group">
        <label for="total_amount">Grand Total</label>
    </div>
    <div class="form-group">
        <input type="text" class="form-control" id="total_amount" name="total_amount" value="<?php echo isset($invoice['total_amount']) ? $invoice['total_amount'] : ''; ?>" readonly>
    </div>
</div>