// Unified handler for modal_price
$(document).on("input change keyup", "#modal_price", function () {
  const price = parseFloat($(this).val()) || 0;
  const gstRate = parseFloat($(".gst_rate").val()) || 0;
  const netPrice = price + (price * gstRate) / 100;

  $("#modal_net_price").val(netPrice.toFixed(2));
});

// Handle net price input
$(document).on("input change keyup", "#modal_net_price", function () {
  const netPrice = parseFloat($(this).val()) || 0;
  const gstRate = parseFloat($(".gst_rate").val()) || 0;
  const price = netPrice / (1 + gstRate / 100);

  // Update the price field
  $(".price").val(price.toFixed(2));
  var productRow = $(".product-row");
  calculateTotalForRow(productRow);
});

$(document).ready(function () {
  $("#is_gst").change();

  // Event: Calculate total when inputs change
  $(".product-row input, .product-row select").on("input change", function () {
    var row = $(this).closest(".product-row");
    calculateTotalForRow(row);
  });

  // Event: Add product to table with button
  $(".add-product").on("click", function () {
    handleAddProduct();
  });

  // Event: Add product to table with Ctrl + A
  $(document).on("keydown", function (e) {
    if (e.ctrlKey && e.key.toLowerCase() === "a") {
      e.preventDefault();
      handleAddProduct();
    }
  });

  // Validation & Add function
  function handleAddProduct() {
    let productRow = $(".product-row");
    let productId = productRow.find(".product_id").val();
    let quantity = parseFloat(productRow.find(".quantity").val());
    let price = parseFloat(productRow.find(".price").val());

    let isValid = true;

    // Validation: Product
    if (!productId) {
      shakeField(productRow.find(".product_id").closest(".form-group"));
      isValid = false;
    }

    // Validation: Quantity
    if (isNaN(quantity) || quantity < 1) {
      shakeField(productRow.find(".quantity").closest(".form-group"));
      isValid = false;
    }

    // Validation: Price
    if (isNaN(price) || price < 1) {
      shakeField(productRow.find(".price").closest(".form-group"));
      isValid = false;
    }

    if (!isValid) return;

    // All good → Add product
    $("#product-rows, .total_amount_section, .final_btn_section").show();
    addOrUpdateProductInTable();
    $(".product_id").chosen().trigger("chosen:updated");
    $(".product_details").hide();
    $(".last_purchase_prices").html("");
  }

  // Helper: Shake field
  function shakeField($el) {
    $el.addClass("shake border border-danger");
    setTimeout(() => {
      $el.removeClass("shake border border-danger");
    }, 600);
  }

  // Function to add or update a product in the table
  function addOrUpdateProductInTable() {
    let productRow = $(".product-row");

    // Get data from the form fields
    let product_id = productRow.find(".product_id").val();
    let product = productRow.find(".product_id option:selected").text();
    let quantity = parseFloat(productRow.find(".quantity").val());
    let price = parseFloat(productRow.find(".price").val());
    let discountType = productRow.find(".discount_type").val();
    let discountAmount =
      parseFloat(productRow.find(".discount_amount").val()) || 0;
    let hsn_code = productRow.find(".hsn_code_val").val() || 0;
    let gstRate = parseFloat($(".gst_rate").val()) || 0;
    let gst_amount = parseFloat(productRow.find(".gst_amount").val()) || 0;
    let total = parseFloat(productRow.find(".total").val());
    let product_descriptions = productRow.find(".product_descriptions").val();

    let discountText =
      discountType === "1" ? "₹" + discountAmount : discountAmount + "%";

    // Hidden fields for the product
    let hiddenFields =
      '<input type="hidden" name="product_id[]" value="' +
      product_id +
      '">' +
      '<input type="hidden" name="product_descriptions[]" value="' +
      product_descriptions +
      '">' +
      '<input type="hidden" name="qnt[]" value="' +
      quantity +
      '">' +
      '<input type="hidden" name="purchase_price[]" value="' +
      price +
      '">' +
      '<input type="hidden" name="discount_type[]" value="' +
      discountType +
      '">' +
      '<input type="hidden" name="discount[]" value="' +
      discountAmount +
      '"><input type="hidden" name="gst_rate[]" value="' +
      gstRate +
      '">' +
      '<input type="hidden" name="gst_amount[]" value="' +
      gst_amount +
      '">' +
      '<input type="hidden" name="final_price[]" value="' +
      total +
      '">';

    // Create the table row
    let newRow =
      "<tr data-product-id='" +
      product_id +
      "'>" +
      "<td><b>" +
      product +
      "</b><p>" +
      product_descriptions +
      "</p></td><td>" +
      quantity +
      "</td>" +
      "<td>₹" +
      price.toFixed(2) +
      "</td>" +
      "<td>" +
      discountText +
      "</td>" +
      "<td>₹" +
      gst_amount +
      " (" +
      gstRate +
      "%)</td><td><b>₹" +
      formatNumber(parseFloat(price + gst_amount)) +
      "</b></td>" +
      "<td>₹" +
      total.toFixed(2) +
      "</td>" +
      "<td class='text-center'><button type='button' class='btn btn-info btn-sm edit-item me-2'><i class='bi bi-pencil-square me-1'></i></button><button type='button' class='btn btn-danger btn-sm remove-item'><i class='bi bi-x-circle me-1'></i></button></td>" +
      hiddenFields +
      "</tr>";

    // Check if the product already exists in the table
    let existingRow = $("#product-rows tbody").find(
      "tr[data-product-id='" + product_id + "']"
    );

    if (existingRow.length > 0) {
      // If the product exists, replace the row with updated data
      existingRow.replaceWith(newRow);
    } else {
      // If the product doesn't exist, append the new row
      $("#product-rows tbody").append(newRow);
    }

    // Update totals
    updateTotals();

    // Reset the form fields
    productRow.find("input, select").val("");
    productRow.find(".quantity").val("1");
    productRow.find(".product_extra_section").hide();

    let is_gst = $("#is_gst").val();
    if (is_gst == 0) {
      productRow.find(".gst_rate").val("0");
    }

    productRow.find(".discount_amount").attr("readonly", "readonly").val("0");
    productRow.find(".net_price_section").hide();
  }

  // Event: Remove product from table
  $(document).on("click", ".remove-item", function () {
    $(this).closest("tr").remove();
    updateTotals();
  });
});

$(document).on("change", ".discount_type", function () {
  var discountField = $(this).closest(".product-row").find(".discount_amount");
  var discount_amount_sec = $(this)
    .closest(".product-row")
    .find(".discount_amount_sec");

  if ($(this).val() === "0") {
    discount_amount_sec.hide();
    discountField.attr("readonly", "readonly").val("0");
  } else {
    discount_amount_sec.show();
    discountField.removeAttr("readonly");
  }
});

// Handle GST/Non-GST change
$("#is_gst").on("change", function () {
  let is_gst = $(this).val();
  let gstRate = is_gst == 1 ? default_gst_rate : 0;

  if (is_gst == 0) {
    $(".hide_non_gst").hide();
    $(".gst_rate").prop("disabled", true);
  } else {
    $(".hide_non_gst").show();
    $(".gst_rate").removeAttr("disabled");
  }

  $(".product-row").each(function () {
    $(this).find(".gst_rate").val(gstRate);
    calculateTotalForRow($(this));
  });
});

// Function to calculate the total for a row
function calculateTotalForRow(row) {
  var quantity = parseFloat(row.find(".quantity").val());
  var price = parseFloat(row.find(".price").val());
  var discountType = row.find(".discount_type").val();
  var discountAmount = parseFloat(row.find(".discount_amount").val()) || 0;
  var gstRate = parseFloat(row.find(".gst_rate").val()) || 0;

  // Calculate discount
  var discount = 0;
  if (discountType === "1") {
    // Flat discount
    discount = discountAmount;
  } else if (discountType === "2") {
    // Percent discount
    discount = (price * discountAmount) / 100;
  }

  // Calculate total before GST
  var totalBeforeGST = price - discount;

  // Calculate GST amount
  var gstAmount = (totalBeforeGST * gstRate) / 100;

  // Calculate final total
  var total = totalBeforeGST + gstAmount;

  var net_single_price = total;

  total *= quantity; // Multiply by quantity

  row.find(".net_price").html(net_single_price.toFixed(2));
  row.find(".net_price_section").show();
  row.find(".gst_amount").val(gstAmount.toFixed(2));
  // Update the total input field
  row.find(".total").val(Math.round(total));
}

// Function to update totals in the footer
function updateTotals() {
  var subTotal = 0;
  var totalDiscount = 0;
  var totalGST = 0;
  var grandTotal = 0;

  $("#product-rows tbody tr").each(function () {
    var price =
      parseFloat($(this).find("input[name='purchase_price[]']").val()) || 0;
    var quantity = parseFloat($(this).find("input[name='qnt[]']").val()) || 1;
    /* var quantity = parseFloat($(this).find("td:eq(1)").text()); */
    var discountType = $(this).find("input[name='discount_type[]']").val();
    var discount =
      parseFloat($(this).find("input[name='discount[]']").val()) || 0;
    var gstRate =
      parseFloat($(this).find("input[name='gst_rate[]']").val()) || 0;

    var discountedPrice = price;
    if (discountType == "2") {
      // Percentage
      discountedPrice -= (price * discount) / 100;
    } else if (discountType == "1") {
      // Flat
      discountedPrice -= discount;
    }

    var gstAmount =
      parseFloat($(this).find("input[name='gst_amount[]']").val()) || 0;
    var finalPrice =
      parseFloat($(this).find("input[name='final_price[]']").val()) || 0;

    subTotal += price * quantity;
    totalDiscount += (price - discountedPrice) * quantity;
    totalGST += gstAmount * quantity;
    grandTotal += finalPrice;
  });

  var roundedTotal = Math.round(grandTotal);
  var roundOff = roundedTotal - grandTotal;
  $("#round_off").val(roundOff.toFixed(2));

  $("#sub_total").val(subTotal.toFixed(2));
  $("#total_discount").val(totalDiscount.toFixed(2));
  $("#total_gst").val(totalGST.toFixed(2));
  $("#total_amount").val(roundedTotal.toFixed(2));
  updateTotalBalance();
}

$(document).on("click", ".show_details", function () {
  $("#stock-details-table").toggle();
  return false;
});

$(document).ready(function () {
  $(".product_id").on("change", function () {
    let product_id = $(this).val();
    let customer_id = $(".customer_id").val();
    var productRow = $(".product-row");
    if (customer_id && product_id) {
      $.ajax({
        url: latestSalePricesUrl, // Set the correct URL
        type: "POST",
        data: { customer_id: customer_id, product_id: product_id },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            let lastPurchaseList = $(".last_purchase_prices");
            lastPurchaseList.empty(); // Clear previous entries

            let ul = $("<ul>");

            $.each(response.data, function (index, purchase) {
              let li = $("<li>").text(
                `₹${parseFloat(
                  (purchase.final_price / purchase.quantity).toFixed(2)
                )} (${purchase.invoice_date})`
              );

              ul.append(li);
            });

            lastPurchaseList.html("<h4>Last Sales Prices: </h4>").append(ul);
          } else {
            $(".last_purchase_prices").html(
              "<span style='color: red;'>No previous purchases found</span>"
            );
          }
        },
      });
    }

    if (product_id) {
      $.ajax({
        url: productDetailsUrl,
        type: "POST",
        data: {
          product_id: product_id,
        },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            let product_details = response.data;
            //console.log(product_details);
            let is_gst = $("#is_gst").val();

            let gstRate = 0;

            if (is_gst == 1) {
              if (
                product_details.gst_rate !== null &&
                product_details.gst_rate !== undefined
              ) {
                gstRate = parseFloat(product_details.gst_rate);
              } else {
                gstRate = default_gst_rate;
              }
            }

            productRow.find(".gst_rate").val(gstRate);
            productRow.find("#modal_net_price").val(product_details.sale_price);
            productRow.find(".unit").val(product_details.symbol);

            productRow
              .find(".product_unit")
              .text("(" + product_details.symbol + ")");
            productRow.find(".hsn_code").text(product_details.hsn_code);
            productRow.find(".hsn_code_val").val(product_details.hsn_code);
            productRow
              .find(".highlight_text")
              .text(product_details.highlight_text);
            productRow.find(".product_extra_section").show();
            //productRow.find(".price").change();
            productRow.find("#modal_net_price").change();
            calculateTotalForRow(productRow);
          }
        },
      });

      $.ajax({
        url: getLastestStocksUrl,
        type: "POST",
        data: {
          product_id: product_id,
        },
        dataType: "json",
        success: function (response) {
          if (response.status === "success") {
            let tableBody = $("#stock-details-table tbody");
            tableBody.empty(); // Clear any existing rows

            let totalStock = 0;

            $.each(response.data, function (index, stock) {
              totalStock += parseFloat(stock.available_stock);

              let row = `<tr>
								<td>${stock.supplier_name}</td>
								<td>${stock.available_stock}</td>
								<td>${stock.purchase_price}</td>
								<td>${stock.purchase_date}</td>
								<td>${stock.batch_no}</td>
							</tr>`;
              tableBody.append(row);
            });

            $(".in_stock").html(response.current_stock + " pcs");
            $("#total_stocks").val(totalStock);
            $(".product_details").show(); // Show the stock details section
            $(".no_stocks").hide();
          } else {
            $(".net_price_section").hide();
            $(".no_stock_txt").html(response.message);
            $(".no_stocks").show(); // Show the stock details section
            $(".product_details").hide(); // Show the stock details section
            /* alert(response.message); */
          }
        },
      });
    } else {
      let is_gst = $("#is_gst").val();
      if (is_gst == 0) {
        productRow.find(".gst_rate").val("0");
      }

      productRow.find(".discount_amount").attr("readonly", "readonly").val("0");
      productRow.find(".net_price_section").hide();

      $(".product_details").hide(); // Hide if no product is selected
    }
  });
});

/** Edit Product from table row */
$(document).on("click", ".edit-item", function () {
  // Find the parent row of the clicked button
  var $row = $(this).closest("tr");
  $(".product_extra_section").show();
  // Get the product ID from the hidden input
  var productId = $row.find('input[name="product_id[]"]').val();
  var productDescriptions = $row
    .find('input[name="product_descriptions[]"]')
    .val();

  // Update the dropdown with the selected product
  $(".product_id").val(productId).chosen().trigger("chosen:updated");

  // Get other fields from the hidden inputs
  var quantity = $row.find('input[name="qnt[]"]').val();
  var price = $row.find('input[name="purchase_price[]"]').val();
  var discountType = $row.find('input[name="discount_type[]"]').val();
  var discount = $row.find('input[name="discount[]"]').val();
  var hsn_code = $row.find('input[name="hsn_code[]"]').val();
  var gstRate = $row.find('input[name="gst_rate[]"]').val();

  $(".price").val(price).trigger("change");

  // Update other fields immediately
  $(".product_descriptions").val(productDescriptions);
  $(".quantity").val(quantity);
  $(".discount_type").val(discountType).trigger("change");
  $(".discount_amount").val(discount);
  $(".gst_rate").val(gstRate).trigger("change");
  $(".hsn_code_val").val(hsn_code);
  $(".hsn_code").text(hsn_code);
});

/** Add Payment Section */
$(document).ready(function () {
  // Add payment row on clicking plus icon
  $(document).on("click", ".add-payment", function () {
    // Clone the existing payment row
    var newPaymentRow = $(".payment-row:first").clone();

    // Reset the cloned row's input values
    newPaymentRow.find("input").val("");
    //newPaymentRow.find("select").val("");

    // Change the plus button to a minus button for removing
    newPaymentRow
      .find(".add-payment")
      .removeClass("btn-primary add-payment")
      .addClass("btn-danger remove-payment")
      .html('<i class="bi bi-dash-lg"></i>');

    // Append the cloned payment row to the payment section
    $("#payment-section").append(newPaymentRow);
  });

  // Remove payment row
  $(document).on("click", ".remove-payment", function () {
    $(this).closest(".payment-row").remove();
  });
});

// Function to calculate and update the balance amount
function updateTotalBalance() {
  // Get the total amount from the total_amount input field
  var totalAmount = parseFloat($("#total_amount").val()) || 0;
  //alert(totalAmount);
  // Initialize the total paid amount
  var totalPaid = 0;

  // Loop through each payment_amount input field to sum the paid amounts
  $(".payment_amount").each(function () {
    var paymentAmount = parseFloat($(this).val()) || 0;
    totalPaid += paymentAmount;
  });

  //alert(totalPaid);

  // Calculate the balance amount
  var balanceAmount = totalAmount - totalPaid;

  if (parseFloat(balanceAmount) <= 0) {
    //alert("btn hide");
    $(".add_update_payment_btn").hide();
  } else {
    //alert("btn show");
    $(".add_update_payment_btn").show();
  }

  // Update the balance_amount span with the calculated balance, ensuring it has 2 decimal places
  $(".balance_amount").text(balanceAmount.toFixed(2));
}

// Trigger balance update when total_amount or any payment_amount changes
$("#total_amount, #payment-section").on(
  "input",
  ".payment_amount",
  function () {
    updateTotalBalance();
  }
);

// Initially calculate balance on page load
$(document).ready(function () {
  updateTotalBalance();
});

function formatNumber(num) {
  return Number(num).toLocaleString("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}
