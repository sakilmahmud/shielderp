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
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>chart.js/Chart.min.css">

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
  <script src="<?php echo base_url('assets/admin/plugins/') ?>chart.js/Chart.min.js"></script>
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
      updateDateTime(); // Initial call
      setInterval(updateDateTime, 1000); // Update every second
    });
  </script>


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
          <b id="live-datetime"></b>
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