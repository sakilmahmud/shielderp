<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Transfer Funds</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/accounts/list_fund_transfers'); ?>" class="btn btn-primary">Transactions</a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Fund Transfer</h3>
                </div>
                <div class="card-body">
                    <?php if ($this->session->flashdata('message')): ?>
                        <div class="alert alert-success">
                            <?php echo $this->session->flashdata('message'); ?>
                        </div>
                    <?php elseif ($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?php echo $this->session->flashdata('error'); ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="<?php echo base_url('admin/accounts/transfer_fund'); ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_payment_method">From Payment Method</label>
                                    <select id="from_payment_method" name="from_payment_method" class="form-control" required>
                                        <option value="">Select</option>
                                        <?php foreach ($paymentMethods as $method): ?>
                                            <option value="<?php echo $method['id']; ?>"><?php echo $method['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <p id="current_balance" class="text-success font-weight-bold">0.00</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_payment_method">To Payment Method</label>
                                    <select id="to_payment_method" name="to_payment_method" class="form-control" required>
                                        <option value="">Select</option>
                                        <?php foreach ($paymentMethods as $method): ?>
                                            <option value="<?php echo $method['id']; ?>"><?php echo $method['title']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="amount">Amount</label>
                                    <input type="number" id="amount" name="amount" class="form-control" required>
                                    <span id="balance_error" class="text-danger" style="display: none;">Insufficient balance!</span>
                                </div>
                                <div class="form-group">
                                    <label for="transfer_date">Transfer Date</label>
                                    <input type="date" id="transfer_date" name="transfer_date" class="form-control" value="<?php echo date("Y-m-d"); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea id="note" name="note" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Transfer Funds</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var currentBalance = 0;

        // Fetch balance when payment method changes
        $('#from_payment_method').change(function() {
            var paymentMethodId = $(this).val();

            if (paymentMethodId) {
                $.ajax({
                    url: "<?php echo base_url('admin/accounts/get_payment_method_balance'); ?>",
                    type: "POST",
                    data: {
                        payment_method_id: paymentMethodId
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.balance !== undefined) {
                            currentBalance = parseFloat(response.balance);
                            $('#current_balance').text('₹' + currentBalance.toFixed(2));
                        } else {
                            currentBalance = 0;
                            $('#current_balance').text('0.00');
                        }
                    },
                    error: function() {
                        currentBalance = 0;
                        $('#current_balance').text('Error fetching balance');
                    }
                });
            } else {
                currentBalance = 0;
                $('#current_balance').text('0.00');
            }
        });

        // Validate amount before form submission
        $('#amount').on('input', function() {
            var enteredAmount = parseFloat($(this).val());

            if (enteredAmount > currentBalance) {
                $('#balance_error').show();
                $('button[type="submit"]').prop('disabled', true);
            } else {
                $('#balance_error').hide();
                $('button[type="submit"]').prop('disabled', false);
            }
        });
    });
</script>