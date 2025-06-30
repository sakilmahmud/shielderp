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
                <li class="nav-item">
                    <a href="<?php echo base_url() ?>" target="_blank" class="nav-link" title="Home">
                        <i class="bi bi-house-door-fill"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="javascript:void(0);" id="openCalculator" class="nav-link" title="Calculator">
                        <i class="bi bi-calculator-fill"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('admin/password'); ?>" title="Change Password" class="nav-link <?php if ($activePage === 'password') echo 'active'; ?>">
                        <i class="bi bi-key-fill"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?php echo base_url('logout'); ?>" class="nav-link logout" title="Logout">
                        <i class="bi bi-power text-danger"></i>
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
                        <span class="theme-icon-active" id="bd-theme-text"><i class="my-1"></i></span>
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
                                <i class="bi bi-circle-half me-2"></i>
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
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Calculator</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
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