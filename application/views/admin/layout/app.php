<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo getSetting('admin_title'); ?></title>

    <link rel="icon" type="image/ico" href="<?php echo base_url('favicon.ico') ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/index.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/overlayscrollbars.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/bootstrap-icons.min.css') ?>">

    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/adminlte.min.css') ?>">

    <!-- Custom style -->
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/styles.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/calc.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/chosen.min.css') ?>">

    <!-- DataTables -->
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/dataTables.bootstrap5.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('assets/admin/css/responsive.bootstrap5.min.css') ?>">

    <!-- jQuery -->
    <script src="<?php echo base_url('assets/admin/js/jquery-3.7.1.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/admin/js/overlayscrollbars.browser.es6.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/admin/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- DataTables  & Plugins -->
    <script src="<?php echo base_url('assets/admin/js/dt/dataTables.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/admin/js/dt/dataTables.bootstrap5.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/admin/js/dt/dataTables.responsive.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/admin/js/dt/responsive.bootstrap5.min.js') ?>"></script>
    <style>
        .chosen-container-single .chosen-single {
            height: 35px;
        }

        .chosen-container-single .chosen-single {
            line-height: 34px;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

        <!-- Header -->
        <?php $this->load->view('admin/layout/header'); ?>
        <?php $this->load->view('admin/layout/sidebar'); ?>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content">
                <?php
                if (isset($view)) {
                    $this->load->view($view, $data ?? []);
                }
                ?>

            </div>
            <!--end::App Content-->
        </main>

        <!-- Footer -->
        <?php $this->load->view('admin/layout/footer'); ?>

    </div>
</body>

<script>
    let addProduct_url = "<?php echo base_url('admin/products/add-ajax'); ?>";
    let addCategory_url = "<?php echo base_url('admin/categories/add-ajax'); ?>";
    let addBrand_url = "<?php echo base_url('admin/brands/add-ajax'); ?>";
</script>

<!-- jQuery UI -->
<script src="<?php echo base_url('assets/admin/js/jquery-ui.min.js') ?>"></script>
<!-- AdminLTE App -->
<script src="<?php echo base_url('assets/admin/js/adminlte.min.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/calc.js') ?>"></script>
<script src="<?php echo base_url('assets/admin/js/chosen.jquery.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        $(".product_id, .category_id, .brand_id, .client_id, .doer_id, .payment_method_id").chosen().trigger("chosen:updated");
        // Open the modal when clicking on "Add Product"
        $(".add_product").click(function() {
            $(".category_id").chosen().trigger("chosen:updated");
            $("#addProductModal").modal("show");
        });
        // Handle the form submission
        $("#addProductForm").submit(function(e) {
            e.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url: addProduct_url, // Replace with your correct controller/method
                type: "POST",
                data: formData,
                success: function(response) {
                    response =
                        typeof response === "string" ? JSON.parse(response) : response;
                    console.log(response);
                    if (response.success) {
                        // Append the new product to the dropdown
                        var newOption = $("<option></option>")
                            .attr("value", response.product.id)
                            .text(response.product.name);
                        $(".product_id").append(newOption);

                        // Set the new product as the selected option
                        $(".product_id")
                            .val(response.product.id)
                            .chosen()
                            .trigger("chosen:updated");

                        // Close the modal
                        $("#addProductModal").modal("hide");

                        // Clear the form for the next time
                        $("#addProductForm")[0].reset();
                    } else {
                        alert("There was an error adding the product. Please try again.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                },
            });
        });

        // Show the modal when the "Add Category" link is clicked
        $(".add_category").on("click", function() {
            $("#addCategoryModal").modal("show");
        });

        // Handle the form submission for adding a new category
        $("#addCategoryForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: addCategory_url, // Replace with your correct URL
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    //response = typeof response === "string" ? JSON.parse(response) : response;

                    if (response.success) {
                        // Append the new category to the dropdown
                        var newOption = $("<option></option>")
                            .attr("value", response.category.id)
                            .text(response.category.name);
                        $(".category_id").append(newOption);

                        // Set the new category as the selected option
                        $(".category_id").val(response.category.id).trigger("chosen:updated");

                        // Close the modal
                        $("#addCategoryModal").modal("hide");

                        // Clear the form for the next time
                        $("#addCategoryForm")[0].reset();
                    } else {
                        alert("There was an error adding the category. Please try again.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                },
            });
        });

        // Show the modal when the "Add Brand" link is clicked
        $(".add_brand").on("click", function() {
            $("#addBrandModal").modal("show");
        });

        // Handle the form submission for adding a new brand
        $("#addBrandForm").on("submit", function(e) {
            e.preventDefault();

            $.ajax({
                url: addBrand_url, // Replace with your correct URL
                method: "POST",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        // Append the new brand to the dropdown
                        var newOption = $("<option></option>")
                            .attr("value", response.brand.id)
                            .text(response.brand.name);
                        $(".brand_id").append(newOption);

                        // Set the new brand as the selected option
                        $(".brand_id").val(response.brand.id).trigger("chosen:updated");
                        updateProductName();
                        // Close the modal
                        $("#addBrandModal").modal("hide");

                        // Clear the form for the next time
                        $("#addBrandForm")[0].reset();
                    } else {
                        alert("There was an error adding the brand. Please try again.");
                    }
                },
                error: function() {
                    alert("An error occurred. Please try again.");
                },
            });
        });

        $('#add_hsn_code_btn').on('click', function() {
            $('#addHsnCodeModal').modal('show');
        });

        $('#addHsnCodeForm').on('submit', function(e) {
            e.preventDefault();
            $.post('<?= base_url("admin/hsn-codes/ajax_add") ?>', $(this).serialize(), function(response) {
                if (response.success) {
                    const hsn = response.data;
                    $('#hsn_code_id').append(
                        `<option value="${hsn.id}" data-gst="${hsn.gst_rate}" selected>
                    ${hsn.hsn_code} - ${hsn.description} (${hsn.gst_rate}%)
                </option>`
                    ).trigger('change');
                    $('#addHsnCodeModal').modal('hide');
                    $('#addHsnCodeForm')[0].reset();
                } else {
                    alert("Error: " + response.message);
                }
            }, 'json');
        });


        $("#brand_id").change(function() {
            updateProductName();
        });

        $(".category_id").change(function() {
            var categoryId = $(this).val();
            if (categoryId) {
                $.ajax({
                    url: "<?php echo base_url('admin/products/getProductsByCategory'); ?>",
                    type: "POST",
                    data: {
                        category_id: categoryId,
                    },
                    success: function(response) {
                        $(".all_products_of_category").html(response);
                    },
                });
            } else {
                $(".all_products_of_category").html("");
            }
        });
    });

    function updateProductName() {
        var brandName = $("#brand_id option:selected").text();
        var productName = $.trim($("#name").val());
        $("#name").val(brandName + " ");
    }
</script>

<script>
    $(document).ready(function() {
        $('#commonTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": false,
            "responsive": true
        });
    });
</script>
<script>
    $(document).ready(function() {
        // When typing in the customer_name or customer_phone fields, search for customers
        $('#customer_name, #customer_phone').on('input', function() {
            let search_term = $(this).val(); // Get the input value

            // If the input has 1 or more characters, start the AJAX search
            if (search_term.length >= 1) {
                $.ajax({
                    url: '<?= base_url("commonController/searchCustomer") ?>', // Your search API endpoint
                    method: 'GET',
                    data: {
                        term: search_term
                    }, // Pass the search term to the server
                    success: function(response) {
                        let customers = JSON.parse(response); // Parse the JSON response
                        let suggestions = ''; // HTML string for the dropdown suggestions

                        // Loop through the results and create a list of suggestions
                        customers.forEach(function(customer) {
                            suggestions += `<li class="list-group-item customer-suggestion" data-cid="${customer.id}" data-phone="${customer.phone}" data-name="${customer.customer_name}" data-address="${customer.address}" data-gst="${customer.gst_number}">${customer.customer_name} (${customer.phone})</li>`;
                        });

                        // Show the suggestions in a dropdown
                        $('#customer_suggestions').html(suggestions).show();
                    }
                });
            } else {
                $('#customer_suggestions').hide(); // Hide suggestions if less than 3 characters
            }
        });

        // When a customer is selected from the suggestions
        $(document).on('click', '.customer-suggestion', function() {
            let cid = $(this).data('cid');
            let phone = $(this).data('phone');
            let name = $(this).data('name');
            let address = $(this).data('address');
            let gst = $(this).data('gst');

            // Fill the form fields with the selected customer data
            $('.customer_id').val(cid);
            $('#customer_phone').val(phone);
            $('#customer_name').val(name);
            $('#customer_address').val(address);
            $('#customer_gst').val(gst);

            // Hide the suggestions after selection
            $('#customer_suggestions').hide();
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('.task-link').on('click', function(e) {
            e.preventDefault();
            var taskId = $(this).data('task-id');

            $.ajax({
                url: '<?php echo site_url('admin/taskDetails'); ?>',
                type: 'POST',
                data: {
                    task_id: taskId
                },
                dataType: 'json',
                success: function(response) {
                    console.log(response);
                    $('#taskModalLabel').text(response.title);
                    $('#taskDescription').text(response.description);
                    $('#taskCategory').text(response.cat_name);
                    $('#taskStartTime').text(response.start_date);
                    $('#taskEndTime').text(response.due_date);
                    $('#taskStatus').text(response.status);
                    $('#taskModal').modal('show');
                },
                error: function() {
                    alert('Failed to fetch task details.');
                }
            });
        });
    });
</script>
<script>
    const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
    const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
    };
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (sidebarWrapper && typeof OverlayScrollbarsGlobal?.OverlayScrollbars !== 'undefined') {
            OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });
</script>
<script>
    // Color Mode Toggler
    (() => {
        'use strict';

        const storedTheme = localStorage.getItem('theme');

        const getPreferredTheme = () => {
            if (storedTheme) {
                return storedTheme;
            }

            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        };

        const setTheme = function(theme) {
            const htmlEl = document.documentElement;
            const sidebar = document.querySelector('.app-sidebar');

            if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                htmlEl.setAttribute('data-bs-theme', 'dark');
                updateSidebarTheme('dark', sidebar);
            } else {
                htmlEl.setAttribute('data-bs-theme', theme);
                updateSidebarTheme(theme, sidebar);
            }
        };

        const updateSidebarTheme = (theme, sidebar) => {
            if (!sidebar) return;

            sidebar.classList.remove('bg-light', 'bg-dark');

            if (theme === 'dark') {
                sidebar.classList.add('bg-dark');
                sidebar.setAttribute('data-bs-theme', 'dark');
            } else {
                sidebar.classList.add('bg-light');
                sidebar.setAttribute('data-bs-theme', 'light');
            }
        };

        const showActiveTheme = (theme, focus = false) => {
            const themeSwitcher = document.querySelector('#bd-theme');

            if (!themeSwitcher) {
                return;
            }

            const themeSwitcherText = document.querySelector('#bd-theme-text');
            const activeThemeIcon = document.querySelector('.theme-icon-active i');
            const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`);
            const svgOfActiveBtn = btnToActive.querySelector('i').getAttribute('class');

            for (const element of document.querySelectorAll('[data-bs-theme-value]')) {
                element.classList.remove('active');
                element.setAttribute('aria-pressed', 'false');
            }

            btnToActive.classList.add('active');
            btnToActive.setAttribute('aria-pressed', 'true');
            activeThemeIcon.setAttribute('class', svgOfActiveBtn);
            const themeSwitcherLabel = `${themeSwitcherText.textContent} (${btnToActive.dataset.bsThemeValue})`;
            themeSwitcher.setAttribute('aria-label', themeSwitcherLabel);

            if (focus) {
                themeSwitcher.focus();
            }
        };

        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
            if (storedTheme !== 'light' || storedTheme !== 'dark') {
                setTheme(getPreferredTheme());
            }
        });

        window.addEventListener('DOMContentLoaded', () => {
            setTheme(getPreferredTheme());
            showActiveTheme(getPreferredTheme());

            for (const toggle of document.querySelectorAll('[data-bs-theme-value]')) {
                toggle.addEventListener('click', () => {
                    const theme = toggle.getAttribute('data-bs-theme-value');
                    localStorage.setItem('theme', theme);
                    setTheme(theme);
                    showActiveTheme(theme, true);
                });
            }
        });
    })();
</script>
<script>
    function updateDateTime() {
        const now = new Date();

        const hours = now.getHours() % 12 || 12;
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const ampm = now.getHours() >= 12 ? 'PM' : 'AM';

        const day = now.getDate();
        const daySuffix = (d => {
            if (d > 3 && d < 21) return 'th';
            switch (d % 10) {
                case 1:
                    return 'st';
                case 2:
                    return 'nd';
                case 3:
                    return 'rd';
                default:
                    return 'th';
            }
        })(day);

        const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
        ];

        const formattedTime = `${day}${daySuffix} ${monthNames[now.getMonth()]}, ${now.getFullYear()} ${hours}:${minutes}:${seconds} ${ampm}`;
        $('#live-datetime').text(formattedTime);
    }

    $(document).ready(function() {
        $('.logout').on('click', function(e) {
            e.preventDefault(); // prevent immediate logout
            const logoutUrl = $(this).attr('href');

            if (confirm('Are you sure you want to logout?')) {
                window.location.href = logoutUrl;
            }
        });
        updateDateTime(); // Initial call
        setInterval(updateDateTime, 1000); // Update every second

        // Automatically trigger sidebar toggle after 1 second (1000ms)
        <?php if ($this->uri->segment(1) == 'admin' && ($this->uri->segment(2) == 'invoices' || $this->uri->segment(2) == 'purchase_entries')) : ?>
            setTimeout(function() {
                $('[data-lte-toggle="sidebar"]').get(0).click();
            }, 1000);
        <?php endif; ?>
    });
</script>

</html>