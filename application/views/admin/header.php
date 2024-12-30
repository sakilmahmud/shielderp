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

  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" />

  <!-- jQuery -->
  <script src="<?php echo base_url('assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap -->
  <script src="<?php echo base_url('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- jQuery UI -->
  <script src="<?php echo base_url('assets/admin/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/admin/dist/js/adminlte.min.js') ?>"></script>
  <!-- DataTables  & Plugins -->
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables/jquery.dataTables.min.js"></script>
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/js/dataTables.responsive.min.js"></script>
  <script src="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/js/responsive.bootstrap4.min.js"></script>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>
  <style>
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
      line-height: 1.6;
      padding: .8125rem .5rem;
      background-color: #000 !important;
      text-align: center;
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
  </style>
  <!-- include summernote css/js -->
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
</head>

<body class="hold-transition sidebar-mini">

  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light justify-content-between">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
          <a href="<?php echo base_url() ?>" target="_blank" class="nav-link">Home</a>
        </li>
      </ul>

      <div class="mr-2"><b><?php echo date("h:i A, jS F, Y"); ?></b></div>
    </nav>
    <!-- /.navbar -->

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