$(document).ready(function () {
  /* while update purchase*/
  $("#is_gst").change();

  /* setTimeout(function () {
		calculateTotals();
	}, 2500); */

  /* end while update purchase*/

  $(".product-row").on("input", "input, select", function () {
    var $row = $(this).closest(".product-row");
    calculateAmounts($row);
  });

  $(".product-row").on("click", ".add-product", function () {
    $(".error").removeClass("error"); // removes from entire form/page
    var productRow = $(this).closest(".product-row");

    var product_id = productRow.find(".product_id").val();
    var quantity = parseFloat(productRow.find(".qnt").val());
    var price = parseFloat(productRow.find(".purchase_price").val());

    var supplier_id = $("#supplier_id").val();
    var invoice_no = $("#invoice_no").val();

    // supplier
    if (
      supplier_id === "" ||
      supplier_id === null ||
      supplier_id === undefined
    ) {
      let supplierInput = $("#supplier_id");

      supplierInput.addClass("error shake"); // Add error and shake classes
      supplierInput.focus(); // Keep cursor focused

      // Remove shake class after animation ends so it can re-trigger next time
      setTimeout(() => {
        supplierInput.removeClass("shake");
      }, 500);
      return false;
    }

    // invoice_no
    if (invoice_no === "" || invoice_no === null || invoice_no === undefined) {
      let invoiceInput = $("#invoice_no");

      invoiceInput.addClass("error shake"); // Add error and shake classes
      invoiceInput.focus(); // Keep cursor focused

      // Remove shake class after animation ends so it can re-trigger next time
      setTimeout(() => {
        invoiceInput.removeClass("shake");
      }, 500);
      return false;
    }

    // Validate product_id
    if (product_id === "" || product_id === null || product_id === undefined) {
      let productInput = productRow.find(".product_id");
      let productInput2 = productRow.find(".chosen-single");

      productInput.addClass("error shake"); // Add error and shake classes
      productInput2.addClass("error shake"); // Add error and shake classes
      productInput.focus(); // Keep cursor focused

      // Remove shake class after animation ends so it can re-trigger next time
      setTimeout(() => {
        productInput.removeClass("shake");
      }, 500);
      return false;
    }

    // Validate quantity
    if (isNaN(quantity) || quantity < 1) {
      alert("Please enter a valid quantity greater than or equal to 1.");
      return false;
    }

    // Validate price
    if (isNaN(price) || price < 1) {
      let priceInput = productRow.find(".purchase_price");

      priceInput.addClass("error shake"); // Add error and shake classes
      priceInput.focus(); // Keep cursor focused

      // Remove shake class after animation ends so it can re-trigger next time
      setTimeout(() => {
        priceInput.removeClass("shake");
      }, 500);

      return false;
    }

    // Set the fields to disabled
    $("#is_gst, #supplier_id").prop("disabled", true);

    // Add hidden inputs to hold the values
    $("#is_gst").after(
      '<input type="hidden" name="is_gst" value="' + $("#is_gst").val() + '">'
    );
    $("#supplier_id").after(
      '<input type="hidden" name="supplier_id" value="' +
        $("#supplier_id").val() +
        '">'
    );

    // If all validations pass, add the product to the table
    addProductToTable();
  });

  // Event: Remove product from table
  $(document).on("click", ".remove-item", function () {
    $(this).closest("tr").remove();
    calculateTotals();
  });

  // Initial calculation for existing products
  $(".product-row").each(function () {
    ///calculateTotals();
    //calculateAmounts($(this));
  });
});

$(document).on("change", ".discount_type", function () {
  var discountField = $(this).closest(".product-row").find(".discount");

  if ($(this).val()) {
    discountField.removeAttr("readonly");
  } else {
    discountField.attr("readonly", "readonly").val("0");
  }
});

// Handle GST/Non-GST change
$("#is_gst").on("change", function () {
  let is_gst = $(this).val();
  let gstRate = is_gst == 1 ? 18 : 0;

  if (is_gst == 0) {
    $(".gst_rate").prop("disabled", true);
  } else {
    $(".gst_rate").removeAttr("disabled");
  }

  $(".product-row").each(function () {
    $(this).find(".gst_rate").val(gstRate);
    calculateAmounts($(this));
  });
});

function calculateAmounts($row) {
  var quantity = parseFloat($row.find(".qnt").val()) || 1; // Default to 1 if empty
  var price = parseFloat($row.find(".purchase_price").val()) || 0;
  var discountType = $row.find(".discount_type").val();
  var discount = parseFloat($row.find(".discount").val()) || 0;
  var gstRate = parseFloat($row.find(".gst_rate").val()) || 0;

  var discountedPrice = price;
  if (discountType == "2") {
    // Percentage
    discountedPrice -= (price * discount) / 100;
  } else if (discountType == "1") {
    // Flat
    discountedPrice -= discount;
  }

  var gstAmount = (discountedPrice * gstRate) / 100;
  var finalPrice = discountedPrice + gstAmount;

  var net_single_price = finalPrice;
  var sale_single_price = finalPrice + (finalPrice * 10) / 100;

  finalPrice *= quantity; // Multiply by quantity
  //gstAmount *= quantity; // Multiply by quantity
  console.log("here before");
  $row.find(".net_price").html("₹" + net_single_price.toFixed(2));
  $row.find(".single_net_price").val(net_single_price.toFixed(2));
  $row.find(".sale_price").html("₹" + sale_single_price.toFixed(2));
  $row.find(".single_sale_price").val(sale_single_price.toFixed(2));
  $row.find(".gst_amount").val(gstAmount.toFixed(2));
  $row.find(".final_price").val(finalPrice.toFixed(2));
  console.log("here after");

  calculateTotals();
}

function calculateTotals() {
  //alert("hello");
  var subTotal = 0;
  var totalDiscount = 0;
  var totalGst = 0;
  var grandTotal = 0;
  var tbody = $("#product-rows tbody tr");
  console.log(tbody);
  $("#product-rows tbody tr").each(function () {
    var quantity = parseFloat($(this).find("input[name='qnt[]']").val()) || 1;
    console.log("quantity", quantity);
    var price =
      parseFloat($(this).find("input[name='purchase_price[]']").val()) || 0;
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
    totalGst += gstAmount * quantity;
    grandTotal += finalPrice;
  });

  var roundedTotal = Math.round(grandTotal);
  var roundOff = roundedTotal - grandTotal;
  $("#round_off").val(roundOff.toFixed(2));

  $("#sub_total").val(subTotal.toFixed(2));
  $("#total_discount").val(totalDiscount.toFixed(2));
  $("#total_gst").val(totalGst.toFixed(2));
  $("#total_amount").val(roundedTotal.toFixed(2));
  updateTotalBalance();
}

function addProductToTable() {
  var productRow = $(".product-row");
  var product_id = parseFloat(productRow.find(".product_id").val());
  var product = productRow.find(".product_id option:selected").text();
  var quantity = parseFloat(productRow.find(".qnt").val());
  var price = parseFloat(productRow.find(".purchase_price").val());
  var discountType = productRow.find(".discount_type").val();
  var discountAmount = parseFloat(productRow.find(".discount").val()) || 0;
  var gstRate = parseFloat(productRow.find(".gst_rate").val()) || 0;
  var gst_amount = parseFloat(productRow.find(".gst_amount").val()) || 0;
  var total = parseFloat(productRow.find(".final_price").val());
  var single_net_price = parseFloat(productRow.find(".single_net_price").val());
  var single_sale_price = parseFloat(
    productRow.find(".single_sale_price").val()
  );

  var discountText =
    discountType === "1" ? "₹" + discountAmount : discountAmount + "%";

  var hiddenFields =
    '<input type="hidden" name="product_id[]" value="' +
    product_id +
    '"><input type="hidden" name="qnt[]" value="' +
    quantity +
    '"><input type="hidden" name="purchase_price[]" value="' +
    price +
    '"><input type="hidden" name="discount_type[]" value="' +
    discountType +
    '"><input type="hidden" name="discount[]" value="' +
    discountAmount +
    '"><input type="hidden" name="gst_rate[]" value="' +
    gstRate +
    '"><input type="hidden" name="gst_amount[]" value="' +
    gst_amount +
    '"><input type="hidden" name="final_price[]" value="' +
    total +
    '"><input type="hidden" name="single_net_price[]" value="' +
    single_net_price +
    '"><input type="hidden" name="sale_price[]" value="' +
    single_sale_price +
    '">';

  // Create a new row in the table
  var newRow =
    "<tr>" +
    "<td>" +
    product +
    " x <b>" +
    quantity +
    "</b></td>" +
    "<td>₹" +
    price.toFixed(2) +
    "</td>" +
    "<td>₹" +
    single_net_price.toFixed(2) +
    "</td>" +
    "<td>" +
    discountText +
    "</td>" +
    "<td>₹" +
    gst_amount +
    " (" +
    gstRate +
    "%)" +
    "</td>" +
    "<td>₹" +
    total.toFixed(2) +
    "</td>" +
    '<td width="5%"><button type="button" class="btn btn-danger btn-sm remove-item">X</button></td>' +
    hiddenFields +
    "</tr>";

  // Append the new row to the table body
  $("#product-rows tbody").append(newRow);

  // Update the totals
  calculateTotals();

  // Reset the form fields for new entry
  productRow.find("input, select").val("");
  productRow.find(".qnt").val("1");
  let is_gst = $("#is_gst").val();
  if (is_gst == 0) {
    productRow.find(".gst_rate").val("0");
  } else {
    productRow.find(".gst_rate").val("18");
  }
  productRow.find(".discount").attr("readonly", "readonly").val("0");
  productRow.find(".product_id").chosen().trigger("chosen:updated");
}

/** add payment section */
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
