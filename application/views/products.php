<div class="listing">
    <div class="container">
        <div class="breadcrumb_area">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item current">Products</li>
            </ol>
        </div>
        <!-- <div class="product_listing_heading">
            <h5>Gold Jewellery <span>(50)</span></h5>
        </div> -->
        <div class="row">
            <div class="col-lg-3">
                <div class="filters">
                    <div class="filters_single">
                        <h5>Category</h5>
                        <div class="check_box">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>All</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>Gold</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>Silver</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>Diamond</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>Costume Jewellery</p>
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="filters_single">
                        <h5>Purity</h5>
                        <div class="check_box">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>14k</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>18k</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>20k</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>22k</p>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="filters_single">
                        <h5>Color</h5>
                        <div class="check_box">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>Rose Gold</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>White Gold</p>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    <p>Yellow Gold</p>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="filters_single range_slider_box">
                        <h5>Price</h5>
                        <div class="range-slider">
                            <div class="range_box">
                                <span class="rangeValues"></span>
                                <span class="rangeValues2"></span>
                            </div>
                            <input value="1000" min="1000" max="20000" step="500" type="range">
                            <input value="50000" min="1000" max="20000" step="500" type="range">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="product_listing">
                    <div class="product_listing_sort">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="product_listing_sort_left">
                                    <p class="pro_numbers">
                                        Showing <span><?php echo $start; ?></span> - <span><?php echo $end; ?></span>
                                        out of <span><?php echo $total_products; ?></span> products
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <form method="get" action="" id="shoring_form">
                                    <div class="product_listing_sort_right">
                                        <div class="popular">
                                            <p>Sort By</p>
                                            <select name="sort_by" class="custom-select select-box mr-3" id="sort_by">
                                                <option value="name" <?php echo $sort_by == 'name' ? 'selected' : ''; ?>>Name</option>
                                                <option value="price" <?php echo $sort_by == 'price' ? 'selected' : ''; ?>>Price</option>
                                                <option value="created_at" <?php echo $sort_by == 'created_at' ? 'selected' : ''; ?>>Date Added</option>
                                            </select>

                                            <select name="order" class="custom-select select-box" id="order">
                                                <option value="asc" <?php echo $sort_order == 'asc' ? 'selected' : ''; ?>>Ascending</option>
                                                <option value="desc" <?php echo $sort_order == 'desc' ? 'selected' : ''; ?>>Descending</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="all_product_listing">
                        <div class="row">
                            <?php foreach ($products as $product): ?>
                                <div class="col-lg-4">
                                    <?php $this->load->view('templates/product', ['product' => $product]); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Pagination Links -->
                        <div class="pagination">
                            <?php echo $pagination_links; ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        // Trigger form submission when any select-box changes
        $('.select-box').on('change', function() {
            $('#shoring_form').submit();
        });
    });
</script>