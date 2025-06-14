<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $title; ?></title>
    <link
        rel="stylesheet"
        href="<?php echo base_url('assets/frontend/fontawesome-free-6.5.2-web/css/all.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/animate.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/aos.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/bootstrap.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/jquery.fancybox.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/owl.carousel.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/owl.theme.default.min.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/frontend/css/style.css') ?>" />

    <script src="<?php echo base_url('assets/frontend/js/jquery3.7.1.min.js'); ?>"></script>
</head>

<body>
    <header class="fixed-top web_header">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header_top">
                        <div class="container">
                            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                                <img src="<?php echo getSetting('admin_logo') ? base_url(getSetting('admin_logo')) : base_url('assets/frontend/images/logo.png') ?>" class="img-fluid" alt="logo" /></a>
                            <div class="header_search">
                                <input type="text" placeholder="Search here..." />
                                <div class="header_search_btn">
                                    <img src="<?php echo base_url('assets/frontend/images/search.png') ?>" class="img-fluid" alt="search" />
                                </div>
                            </div>
                            <div class="header_links">
                                <ul>
                                    <li>
                                        <a href="<?php echo base_url('login'); ?>"><img src="<?php echo base_url('assets/frontend/images/user.png') ?>" alt="user" />Account</a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="<?php echo base_url('assets/frontend/images/heart.png') ?>" alt="heart" />Wihlist</a>
                                    </li>
                                    <li>
                                        <a href="#"><img src="<?php echo base_url('assets/frontend/images/cart.png') ?>" alt="cart" />Cart</a>
                                    </li>
                                </ul>
                            </div>
                            <button
                                class="navbar-toggler"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#navbarSupportedContent"
                                aria-controls="navbarSupportedContent"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <img class="since_img" src="<?php echo base_url('assets/frontend/images/trusted_seller.png') ?>" class="img-fluid" alt="Nondan">
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="header_bottom">
                        <div class="container">
                            <div
                                class="collapse navbar-collapse"
                                id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo ($this->uri->segment(1) == '') ? 'active' : ''; ?>" aria-current="page" href="<?php echo base_url(); ?>">Home</a>
                                    </li>
                                    <?php
                                    $get_product_types = get_product_types();
                                    $current_slug = $this->uri->segment(2);

                                    if (!empty($get_product_types)) :
                                        foreach ($get_product_types as $get_product_type):
                                            $active_class = ($this->uri->segment(1) == 'product-type' && $current_slug == $get_product_type['slug']) ? 'active' : '';

                                            // Display the parent category
                                            echo '<li class="nav-item"><a class="nav-link ' . $active_class . '" href="' . base_url('product-type/') . $get_product_type['slug'] . '">' . $get_product_type['product_type_name'] . '</a>';

                                            // Check if there are child categories and display them as a dropdown or submenu
                                            if (!empty($get_product_type['children'])) {
                                                echo '<ul class="sub-menu">'; // Sub-menu class for child categories
                                                foreach ($get_product_type['children'] as $child) {
                                                    $child_active_class = ($current_slug == $child['slug']) ? 'active' : '';
                                                    echo '<li class="nav-item"><a class="nav-link ' . $child_active_class . '" href="' . base_url('product-type/') . $child['slug'] . '">' . $child['product_type_name'] . '</a></li>';
                                                }
                                                echo '</ul>';
                                            }

                                            echo '</li>';
                                        endforeach;
                                    endif;
                                    ?>

                                    <li class="nav-item">
                                        <a href="<?php echo base_url('contact'); ?>" class="nav-link <?php echo ($this->uri->segment(1) == 'contact') ? 'active' : ''; ?>">Contact Us</a>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <header class="mobile_header">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <a class="navbar-brand" href="<?php echo base_url(); ?>">
                            <img src="<?php echo getSetting('admin_logo') ? base_url(getSetting('admin_logo')) : base_url('assets/frontend/images/logo.png') ?>" class="img-fluid" alt="logo" />
                        </a>
                    </div>
                    <div class="col-2">
                        <button
                            class="navbar-toggler"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#navbarSupportedContent"
                            aria-controls="navbarSupportedContent"
                            aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                    </div>
                    <div class="col-10">
                        <div class="header_search">
                            <input type="text" class="form-control product_search_input" placeholder="Search here..." autocomplete="off" />
                            <div class="search_suggestion_box d-none">
                                <ul class="search_results list-unstyled"></ul>
                            </div>

                            <div class="header_search_btn">
                                <img
                                    src="<?php echo base_url('assets/frontend/images/search.png') ?>"
                                    class="img-fluid"
                                    alt="search" />
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="header_bottom">
                            <div
                                class="collapse navbar-collapse"
                                id="navbarSupportedContent">
                                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo ($this->uri->segment(1) == '') ? 'active' : ''; ?>" aria-current="page" href="<?php echo base_url(); ?>">Home</a>
                                    </li>
                                    <?php
                                    $get_product_types = get_product_types();
                                    $current_slug = $this->uri->segment(2);

                                    if (!empty($get_product_types)) :
                                        foreach ($get_product_types as $get_product_type):
                                            $active_class = ($this->uri->segment(1) == 'product-type' && $current_slug == $get_product_type['slug']) ? 'active' : '';

                                            // Display the parent category
                                            echo '<li class="nav-item"><a class="nav-link dropdown-toggle' . $active_class . '" data-bs-toggle="dropdown" aria-expanded="false" href="' . base_url('product-type/') . $get_product_type['slug'] . '">' . $get_product_type['product_type_name'] . '</a>';

                                            // Check if there are child categories and display them as a dropdown or submenu
                                            if (!empty($get_product_type['children'])) {
                                                echo '<ul class="sub-menu dropdown-menu">'; // Sub-menu class for child categories
                                                foreach ($get_product_type['children'] as $child) {
                                                    $child_active_class = ($current_slug == $child['slug']) ? 'active' : '';
                                                    echo '<li><a class=" dropdown-item' . $child_active_class . '" href="' . base_url('product-type/') . $child['slug'] . '">' . $child['product_type_name'] . '</a></li>';
                                                }
                                                echo '</ul>';
                                            }

                                            echo '</li>';
                                        endforeach;
                                    endif;
                                    ?>

                                    <li class="nav-item">
                                        <a href="<?php echo base_url('contact'); ?>" class="nav-link <?php echo ($this->uri->segment(1) == 'contact') ? 'active' : ''; ?>">Contact Us</a>
                                    </li>
                                </ul>

                            </div>

                        </div>
                    </div>
                </div>
            </nav>
        </div>

    </header>
    <style>
        .nav-item {
            list-style: none;
        }

        .nav-item .sub-menu {
            display: none;
            /* Hide submenu initially */
            padding-left: 15px;
        }



        .search_suggestion_box {
            position: absolute;
            background: #fff;
            z-index: 1000;
            width: 350px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            margin-top: 5px;
            top: 65px;
        }

        .search_suggestion_box ul li {
            padding: 5px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .search_suggestion_box ul li img {
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-right: 10px;
        }

        .search_suggestion_box ul li:hover {
            background: #f9f9f9;
        }

        @media screen and (max-width: 768px) {
            .search_suggestion_box {
                width: 300px !important;
                top: 110px !important;
            }
        }
    </style>

    <script>
        $(document).ready(function() {
            $(".product_search_input").on("keyup", function() {
                let query = $(this).val();
                if (query.length > 1) {
                    $.ajax({
                        url: "<?php echo base_url('product/searchProducts'); ?>",
                        method: "POST",
                        data: {
                            keyword: query
                        },
                        dataType: "json",
                        success: function(res) {
                            if (res.status === "success") {
                                let list = "";
                                $.each(res.products, function(i, product) {
                                    let image = product.featured_image ? product.featured_image : 'default.png';
                                    list += `<li class="show_product_modal" data-id="${product.id}">
                                <img src="<?php echo base_url('uploads/products/'); ?>${image}" />
                                <span>${product.name}</span>
                            </li>`;
                                });
                                $(".search_results").html(list);
                                $(".search_suggestion_box").removeClass('d-none');
                            } else {
                                $(".search_results").html('<li>No products found</li>');
                                $(".search_suggestion_box").removeClass('d-none');
                            }
                        },
                    });
                } else {
                    $(".search_suggestion_box").addClass('d-none');
                }
            });

            // Optional: hide on click outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.header_search').length) {
                    $('.search_suggestion_box').addClass('d-none');
                }
            });
        });
    </script>