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
    <header class="fixed-top">
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header_top">
                        <div class="container">
                            <a class="navbar-brand" href="<?php echo base_url(); ?>"><img
                                    src="<?php echo base_url('assets/frontend/images/logo.png') ?>"
                                    class="img-fluid"
                                    alt="logo" /></a>
                            <div class="header_search">
                                <input type="text" placeholder="Search here..." />
                                <div class="header_search_btn">
                                    <img
                                        src="<?php echo base_url('assets/frontend/images/search.png') ?>"
                                        class="img-fluid"
                                        alt="search" />
                                </div>
                            </div>
                            <div class="header_links">
                                <ul>
                                    <li>
                                        <a href="<?php echo base_url('login'); ?>"><img
                                                src="<?php echo base_url('assets/frontend/images/user.png') ?>"
                                                alt="user" />Account</a>
                                    </li>
                                    <li>
                                        <a href="#"><img
                                                src="<?php echo base_url('assets/frontend/images/heart.png') ?>"
                                                alt="heart" />Wihlist</a>
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
                                    //$get_product_types = get_product_types();
                                    /* echo "<pre>";
                                    print_r($get_product_types);
                                    die; */
                                    /*  $current_slug = $this->uri->segment(2);

                                    if (!empty($get_product_types)) :
                                        foreach ($get_product_types as $get_product_type):
                                            $active_class = ($this->uri->segment(1) == 'product-type' && $current_slug == $get_product_type['slug']) ? 'active' : '';
                                            echo '<li class="nav-item"><a class="nav-link ' . $active_class . '" href="' . base_url('product-type/') . $get_product_type['slug'] . '">' . $get_product_type['product_type_name'] . '</a></li>';
                                        endforeach;
                                    endif; */
                                    ?>
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
    <style>
        .nav-item {
            list-style: none;
        }

        .nav-item .sub-menu {
            display: none;
            /* Hide submenu initially */
            padding-left: 15px;
        }

        .nav-item:hover .sub-menu {
            display: block;
            /* Show submenu on hover */
        }
    </style>