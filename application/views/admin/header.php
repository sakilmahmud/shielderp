<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo getSetting('admin_title'); ?></title>

  <link rel="icon" type="image/ico" href="<?php echo base_url('favicon.ico') ?>">

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" />

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/styles/overlayscrollbars.min.css" />

  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/adminlte.min.css') ?>">


  <!-- Custom style -->
  <!-- <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/styles.css') ?>"> -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/calc.css') ?>">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/dist/css/chosen.min.css') ?>">

  <!-- DataTables -->
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="<?php echo base_url('assets/admin/plugins/') ?>chart.js/Chart.min.css">

  <!-- jQuery -->
  <script src="<?php echo base_url('assets/admin/plugins/jquery/jquery.min.js') ?>"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.10.1/browser/overlayscrollbars.browser.es6.min.js"></script>
  <script
    src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
  <script src="<?php echo base_url('assets/admin/dist/js/bootstrap.min.js') ?>"></script>

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

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

  <div class="app-wrapper">
    <!-- Navbar -->
    <nav class="app-header navbar navbar-expand bg-body justify-content-between">
      <div class="container-fluid">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
              <i class="bi bi-list"></i>
            </a>
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
            <li class="nav-item dropdown">
              <button
                class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                id="bd-theme"
                type="button"
                aria-expanded="false"
                data-bs-toggle="dropdown"
                data-bs-display="static">
                <span class="theme-icon-active"> <i class="my-1"></i> </span>
                <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
              </button>
              <ul
                class="dropdown-menu dropdown-menu-end"
                aria-labelledby="bd-theme-text"
                style="--bs-dropdown-min-width: 8rem">
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center active"
                    data-bs-theme-value="light"
                    aria-pressed="false">
                    <i class="bi bi-sun-fill me-2"></i>
                    Light
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="dark"
                    aria-pressed="false">
                    <i class="bi bi-moon-fill me-2"></i>
                    Dark
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
                <li>
                  <button
                    type="button"
                    class="dropdown-item d-flex align-items-center"
                    data-bs-theme-value="auto"
                    aria-pressed="true">
                    <i class="bi bi-circle-fill-half-stroke me-2"></i>
                    Auto
                    <i class="bi bi-check-lg ms-auto d-none"></i>
                  </button>
                </li>
              </ul>
            </li>
          </ul>
        </div>
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