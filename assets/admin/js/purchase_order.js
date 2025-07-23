$(document).ready(function () {
  $("#is_gst").change();

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

    if (
      supplier_id === "" ||
      supplier_id === null ||
      supplier_id === undefined
    ) {
      let supplierInput = $("#supplier_id");

      supplierInput.addClass("error shake"); // Add error and shake classes
      supplierInput.focus(); // Keep cursor focused

      setTimeout(() => {
        supplierInput.removeClass("shake");
      }, 500);
      return false;
    }

    if (invoice_no === "" || invoice_no === null || invoice_no === undefined) {
      let invoiceInput = $("#invoice_no");

      invoiceInput.addClass("error shake"); // Add error and shake classes
      invoiceInput.focus(); // Keep cursor focused

      setTimeout(() => {
        invoiceInput.removeClass("shake");
      }, 500);
      return false;
    }

    if (product_id === "" || product_id === null || product_id === undefined) {
      let productInput = productRow.find(".product_id");
      let productInput2 = productRow.find(".chosen-single");

      productInput.addClass("error shake"); // Add error and shake classes
      productInput2.addClass("error shake"); // Add error and shake classes
      productInput.focus(); // Keep cursor focused

      setTimeout(() => {
        productInput.removeClass("shake");
      }, 500);
      return false;
    }

    if (isNaN(quantity) || quantity < 1) {
      alert("Please enter a valid quantity greater than or equal to 1.");
      return false;
    }

    if (isNaN(price) || price < 1) {
      let priceInput = productRow.find(".purchase_price");

      priceInput.addClass("error shake"); // Add error and shake classes
      priceInput.focus(); // Keep cursor focused

      setTimeout(() => {
        priceInput.removeClass("shake");
      }, 500);

      return false;
    }

    $("#is_gst, #supplier_id").prop("disabled", true);

    $("#is_gst").after(
      '<input type="hidden" name="is_gst" value="' + $("#is_gst").val() + '">'
    );
    $("#supplier_id").after(
      '<input type="hidden" name="supplier_id" value="' +
        $("#supplier_id").val() +
        '">'
    );

    addOrUpdateProductInTable();
  });

  $(document).on("click", ".remove-item", function () {
    $(this).closest("tr").remove();
    calculateTotals();
  });

  $(document).on("click", ".edit-item", function () {
    var $row = $(this).closest("tr");
    var product_id = $row.find("input[name='product_id[]']").val();
    var quantity = $row.find("input[name='qnt[]']").val();
    var price = $row.find("input[name='purchase_price[]']").val();
    var discountType = $row.find("input[name='discount_type[]']").val();
    var discountAmount = $row.find("input[name='discount[]']").val();
    var gstRate = $row.find("input[name='gst_rate[]']").val();

    var $productRow = $(".product-row");
    $productRow.find(".product_id").val(product_id).trigger("chosen:updated");
    $productRow.find(".qnt").val(quantity);
    $productRow.find(".purchase_price").val(price);
    $productRow.find(".discount_type").val(discountType).trigger("change");
    $productRow.find(".discount").val(discountAmount);
    $productRow.find(".gst_rate").val(gstRate);

    calculateAmounts($productRow);
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
  var quantity = parseFloat($row.find(".qnt").val()) || 1;
  var price = parseFloat($row.find(".purchase_price").val()) || 0;
  var discountType = $row.find(".discount_type").val();
  var discount = parseFloat($row.find(".discount").val()) || 0;
  var gstRate = parseFloat($row.find(".gst_rate").val()) || 0;

  var discountedPrice = price;
  if (discountType == "2") {
    discountedPrice -= (price * discount) / 100;
  } else if (discountType == "1") {
    discountedPrice -= discount;
  }

  var gstAmount = (discountedPrice * gstRate) / 100;
  var finalPrice = discountedPrice + gstAmount;

  var net_single_price = finalPrice;
  var sale_single_price = finalPrice + (finalPrice * 10) / 100;

  finalPrice *= quantity;

  $row.find(".net_price").html("₹" + net_single_price.toFixed(2));
  $row.find(".single_net_price").val(net_single_price.toFixed(2));
  $row.find(".sale_price").html("₹" + sale_single_price.toFixed(2));
  $row.find(".single_sale_price").val(sale_single_price.toFixed(2));
  $row.find(".gst_amount").val(gstAmount.toFixed(2));
  $row.find(".final_price").val(finalPrice.toFixed(2));

  calculateTotals();
}

function calculateTotals() {
  var subTotal = 0;
  var totalDiscount = 0;
  var totalGst = 0;
  var grandTotal = 0;

  $("#product-rows tbody tr").each(function () {
    var quantity = parseFloat($(this).find("input[name='qnt[]']").val()) || 1;
    var price =
      parseFloat($(this).find("input[name='purchase_price[]']").val()) || 0;
    var discountType = $(this).find("input[name='discount_type[]']").val();
    var discount =
      parseFloat($(this).find("input[name='discount[]']").val()) || 0;

    var discountedPrice = price;
    if (discountType == "2") {
      discountedPrice -= (price * discount) / 100;
    } else if (discountType == "1") {
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

function addOrUpdateProductInTable() {
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

  var newRow =
    "<tr data-product-id='" +
    product_id +
    "'>" +
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
    "<td class='text-center'><button type='button' class='btn btn-info btn-sm edit-item me-2'><i class='bi bi-pencil-square me-1'></i></button><button type='button' class='btn btn-danger btn-sm remove-item'><i class='bi bi-x-circle me-1'></i></button></td>" +
    hiddenFields +
    "</tr>";

  var existingRow = $("#product-rows tbody").find(
    "tr[data-product-id='" + product_id + "']"
  );

  if (existingRow.length > 0) {
    existingRow.replaceWith(newRow);
  } else {
    $("#product-rows tbody").append(newRow);
  }

  calculateTotals();

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

$(document).ready(function () {
  $(document).on("click", ".add-payment", function () {
    var newPaymentRow = $(".payment-row:first").clone();
    newPaymentRow.find("input").val("");
    newPaymentRow
      .find(".add-payment")
      .removeClass("btn-primary add-payment")
      .addClass("btn-danger remove-payment")
      .html('<i class="bi bi-dash-lg"></i>');
    $("#payment-section").append(newPaymentRow);
  });

  $(document).on("click", ".remove-payment", function () {
    $(this).closest(".payment-row").remove();
  });
});

function updateTotalBalance() {
  var totalAmount = parseFloat($("#total_amount").val()) || 0;
  var totalPaid = 0;

  $(".payment_amount").each(function () {
    var paymentAmount = parseFloat($(this).val()) || 0;
    totalPaid += paymentAmount;
  });

  var balanceAmount = totalAmount - totalPaid;

  if (parseFloat(balanceAmount) <= 0) {
    $(".add_update_payment_btn").hide();
  } else {
    $(".add_update_payment_btn").show();
  }

  $(".balance_amount").text(balanceAmount.toFixed(2));
}

$("#total_amount, #payment-section").on(
  "input",
  ".payment_amount",
  function () {
    updateTotalBalance();
  }
);

$(document).ready(function () {
  updateTotalBalance();
});

function formatNumber(num) {
  return Number(num).toLocaleString("en-US", {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
  });
}
