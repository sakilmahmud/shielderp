<footer class="main-footer">
  <div class="float-right d-none d-sm-block">
    <b>Version</b> 1.0
  </div>
  <strong><?php echo getSetting('footer_text'); ?></strong>
</footer>

<!-- Task Details Modal -->
<div class="modal fade" id="taskModal" tabindex="-1" role="dialog" aria-labelledby="taskModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p><strong>Description:</strong> <span id="taskDescription"></span></p>
        <p><strong>Category:</strong> <span id="taskCategory"></span></p>
        <p><strong>Start Time:</strong> <span id="taskStartTime"></span></p>
        <p><strong>End Time:</strong> <span id="taskEndTime"></span></p>
        <p><strong>Status:</strong> <span id="taskStatus"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
</div>

<script>
  $(document).ready(function() {
    $(".product_id, .category_id, .brand_id, .client_id, .doer_id, .payment_method_id").chosen().trigger("chosen:updated");
  });
</script>
<script src="<?php echo base_url('assets/admin/dist/js/demo.js') ?>"></script>
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
</body>

</html>