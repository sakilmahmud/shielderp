<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Bulk Upload Products</h2>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="<?php echo base_url('admin/products'); ?>" class="btn btn-primary">All Products</a>
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
                            <form action="<?php echo base_url('admin/products/process-bulk-upload'); ?>" method="post" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="csv_file">Upload CSV File</label>
                                    <input type="file" name="csv_file" id="csv_file" class="form-control" accept=".csv" required>
                                </div>
                                <button type="submit" class="btn btn-success">Upload</button>
                            </form>
                            <hr>
                            <p><strong>CSV Format:</strong></p>
                            <pre>
name,slug,regular_price,sale_price,description,category_id,brand_id,product_type_id
Product 1,product-1,100,90,Description 1,1,1,1
Product 2,product-2,200,180,Description 2,2,2,2
                            </pre>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>