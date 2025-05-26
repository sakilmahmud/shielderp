<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo getSetting('admin_title'); ?></title>

  <link rel="icon" type="image/ico" href="<?php echo base_url('favicon.ico') ?>">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- fullCalendar -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/fullcalendar/main.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/adminlte.min.css') ?>">

  <!-- Custom style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/styles.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/calc.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/chosen.min.css') ?>">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/css/responsive.bootstrap4.min.css">

  <!-- jQuery -->
  <script src="<?php echo base_url('assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap -->
  <script src="<?php echo base_url('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- jQuery UI -->
  <script src="<?php echo base_url('assets/admin/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/admin/dist/js/adminlte.min.js') ?>"></script>
  <script src="<?php echo base_url('assets/admin/dist/js/calc.js') ?>"></script>
  <script src="<?php echo base_url('assets/admin/dist/js/chosen.jquery.min.js') ?>"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/js/responsive.bootstrap4.min.js"></script>

  <style>
    .navbar {
      padding: 0px !important;
    }

    @media (min-width: 768px) {

      body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .content-wrapper,
      body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-footer,
      body:not(.sidebar-mini-md):not(.sidebar-mini-xs):not(.layout-top-nav) .main-header {
        transition: margin-left .3s ease-in-out;
        margin-left: 200px;
      }
    }

    .main-sidebar,
    .main-sidebar::before {
      transition: margin-left .3s ease-in-out, width .3s ease-in-out;
      width: 200px;
    }

    .sidebar-mini .main-sidebar .nav-link,
    .sidebar-mini-md .main-sidebar .nav-link,
    .sidebar-mini-xs .main-sidebar .nav-link {
      width: calc(200px - .5rem * 2);
      transition: width ease-in-out .3s;
    }

    .nav-sidebar .nav-link p {
      font-size: 14px;
    }

    .icon-blue {
      color: #3498db;
    }

    .icon-inventory {
      color: rgb(189, 247, 90);
    }

    .icon-purple {
      color: #8e44ad;
    }

    .icon-yellow {
      color: rgb(233, 29, 161);
    }

    .icon-invoice {
      color: rgb(246, 110, 205);
    }

    .icon-orange {
      color: #f39c12;
    }

    .icon-deeporange {
      color: #e67e22;
    }

    .icon-lightgreen {
      color: #2ecc71;
    }

    .icon-red {
      color: #e74c3c;
    }

    .icon-teal {
      color: #16a085;
    }

    .icon-darkblue {
      color: #2980b9;
    }

    .icon-gray {
      color: #7f8c8d;
    }

    .icon-bluegray {
      color: #34495e;
    }

    .icon-darkred {
      color: #c0392b;
    }

    [class*=sidebar-dark-] .sidebar a {
      color: #fff;
    }

    .product-row {
      background: #efefef;
      box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    li.list-group-item.customer-suggestion {
      padding: 5px 0 5px 10px;
      background: #ddd;
      cursor: pointer;
    }

    ul#customer_suggestions {
      position: absolute;
      z-index: 1000;
      width: 250px !important;
      left: 6px;
      top: 70px;
    }

    .last_purchase_prices {
      padding: 10px;
    }

    .last_purchase_prices ul {
      list-style: none;
      padding: 0px;
      margin: 0px;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }

    .last_purchase_prices ul li {
      display: inline-block;
      background: rgb(245, 245, 245);
      padding: 3px 5px;
      border-radius: 4px;
      border: 1px solid rgb(221, 221, 221);
      margin-bottom: -10px;
      font-size: 13px;
    }

    .form-group p {
      color: red;
    }

    [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-treeview {
      background-color: #666;
    }

    label:not(.form-check-label):not(.custom-file-label) {
      font-weight: 700;
      font-size: 13px;
    }

    .table thead th {
      font-size: 12px;
    }

    .form-control {
      font-size: 14px;
    }

    .brand-link .brand-image {
      line-height: 1 !important;
      margin: 0px !important;
      width: 20% !important;
      max-height: none !important;
      float: none !important;
    }

    .brand-link {
      display: block;
      font-size: 1.75rem;
      line-height: 1;
      padding: 4px;
      background-color: #000 !important;
      text-align: center;
    }

    .nav-link {
      padding: 5px 10px;
    }

    .table td {
      font-size: 12px !important;
      padding: 5px 7px !important;
    }

    .table td p {
      margin-bottom: 2px;
    }

    sup {
      color: red;
      font-size: 14px;
      top: -.2em;
    }

    .chosen-container-single {
      width: 100% !important;
      clear: both !important;
    }

    .form-control.error:focus {
      border: 1px solid red !important;
    }

    .error {
      border: 1px solid red !important;
    }

    /* Shake animation */
    @keyframes shake {
      0% {
        transform: translateX(0);
      }

      25% {
        transform: translateX(-5px);
      }

      50% {
        transform: translateX(5px);
      }

      75% {
        transform: translateX(-5px);
      }

      100% {
        transform: translateX(0);
      }
    }

    .shake {
      animation: shake 0.5s;
    }

    /**neww style for due list */
    .due-scroll-container {
      max-height: 350px;
      overflow-y: auto;
      padding-right: 10px;
    }

    .list-group-item {
      border: none;
      border-bottom: 1px solid #f1f1f1;
      transition: background 0.3s;
    }

    .list-group-item:hover {
      background: #f9f9f9;
    }

    .nav-tabs .nav-link {
      font-weight: 500;
      font-size: 15px;
      color: #333;
      background-color: #fff;
    }

    .nav-tabs .nav-link.active {
      background-color: #e9ecef;
      border-color: #dee2e6 #dee2e6 #fff;
    }

    .nav-tabs .badge {
      font-size: 0.875rem;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">

  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light justify-content-between">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item mt-1 d-none d-sm-inline-block">
          <b class=""><?php echo date("h:i A, jS F, Y"); ?></b>
        </li>
      </ul>
      <div class="right_section">
        <ul class="navbar-nav">
          <li class="nav-item ">
            <a href="<?php echo base_url() ?>" target="_blank" class="nav-link" title="Home"><i class="nav-icon fas fa-home"></i></a>
          </li>
          <li class="nav-item">
            <a href="javascript:void(0);" id="openCalculator" class="nav-link" title="Calculator">
              <i class="nav-icon fas fa-calculator"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('admin/password'); ?>" title="Change Password" class="nav-link <?php if ($activePage === 'password') echo 'active'; ?>">
              <i class="nav-icon fas fa-key"></i>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo base_url('logout'); ?>" class="nav-link" title="Logout">
              <i class="nav-icon fas fa-power-off icon-darkred"></i>
            </a>
          </li>
        </ul>
      </div>
    </nav>
    <!-- /.navbar -->
    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-labelledby="calculatorModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body p-0">
            <div class="calculator">
              <div class="display">
                <input type="text" id="expression" readonly />
                <input type="text" id="display" readonly />
              </div>
              <div class="calc_buttons">
                <button class="clear">C</button>
                <button class="operator">%</button>
                <button class="operator">/</button>
                <button class="delete">⌫</button>

                <button class="number">7</button>
                <button class="number">8</button>
                <button class="number">9</button>
                <button class="operator">*</button>

                <button class="number">4</button>
                <button class="number">5</button>
                <button class="number">6</button>
                <button class="operator">-</button>

                <button class="number">1</button>
                <button class="number">2</button>
                <button class="number">3</button>
                <button class="operator">+</button>

                <button class="number zero">0</button>
                <button class="number">.</button>
                <button class="equals">=</button>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <script>
      $(document).ready(function() {
        // Open the modal on clicking the calculator icon
        $('#openCalculator').on('click', function() {
          $('#calculatorModal').modal('show');
        });

        // Bind the F3 key to open the modal
        $(document).on('keydown', function(event) {
          if (event.key === 'F3') {
            event.preventDefault(); // Prevent default browser behavior
            $('#calculatorModal').modal('show');
          }
        });
      });
    </script>


    <?php
    if ($this->session->userdata('role') == 1 || $this->session->userdata('role') == 2) :
      include 'sidebar.php';
    elseif ($this->session->userdata('role') == 4) :
      include 'sidebar-clients.php';
    elseif ($this->session->userdata('role') == 3) :
      include 'sidebar-doers.php';
    else :
      echo "access denied";
    endif;
    ?>