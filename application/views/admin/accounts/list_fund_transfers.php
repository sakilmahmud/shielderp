<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Fund Transfer Data</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/accounts/transfer_fund'); ?>" class="btn btn-primary">New Transfer</a>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="container-fluid">
            <table id="fundTransfersTable" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Payment Method</th>
                        <th>Transaction Type</th>
                        <th>Amount</th>
                        <th>Note</th>
                        <th>Transferred By</th>
                        <th>Transfer Date</th>
                        <th>Created Date</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#fundTransfersTable').DataTable({
            processing: true,
            serverSide: true,
            order: false,
            pageLength: 40,
            lengthMenu: [
                [40, 100, -1], // Options for number of items per page
                [40, 100, "All"] // Labels for those options
            ],
            ajax: {
                url: '<?php echo base_url("admin/accounts/fetch_fund_transfers"); ?>',
                type: 'POST',
                data: function(d) {
                    d.from_date = $('#from_date').val();
                    d.to_date = $('#to_date').val();
                }
            },
            columns: [{
                    data: 0
                }, // ID
                {
                    data: 1
                }, // From Payment Method
                {
                    data: 2
                }, // To Payment Method
                {
                    data: 3
                }, // Amount
                {
                    data: 4
                }, // Note
                {
                    data: 5
                }, // Transferred By
                {
                    data: 6
                }, // Transfer Date
                {
                    data: 7
                } // Created Date
            ]
        });
    });
</script>