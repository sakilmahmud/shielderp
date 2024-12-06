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
		var productRow = $(this).closest(".product-row");

		var product_id = productRow.find(".product_id").val();
		var quantity = parseFloat(productRow.find(".qnt").val());
		var price = parseFloat(productRow.find(".purchase_price").val());

		var supplier_id = $("#supplier_id").val();

		// supplier
		if (
			supplier_id === "" ||
			supplier_id === null ||
			supplier_id === undefined
		) {
			alert("Please enter supplier.");
			return false;
		}

		// Validate product_id
		if (product_id === "" || product_id === null || product_id === undefined) {
			alert("Please select a product.");
			return false;
		}

		// Validate quantity
		if (isNaN(quantity) || quantity < 1) {
			alert("Please enter a valid quantity greater than or equal to 1.");
			return false;
		}

		// Validate price
		if (isNaN(price) || price < 1) {
			alert("Please enter a valid price greater than or equal to 1.");
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

	// Open the modal when clicking on "Add Product"
	$(".add_product").click(function () {
		$(".category_id").chosen().trigger("chosen:updated");
		$("#addProductModal").modal("show");
	});

	// Handle the form submission
	$("#addProductForm").submit(function (e) {
		e.preventDefault();

		var formData = $(this).serialize();

		$.ajax({
			url: addProduct_url, // Replace with your correct controller/method
			type: "POST",
			data: formData,
			success: function (response) {
				response =
					typeof response === "string" ? JSON.parse(response) : response;
				console.log(response);
				if (response.success) {
					// Append the new product to the dropdown
					var newOption = $("<option></option>")
						.attr("value", response.product.id)
						.text(response.product.name);
					$(".product_id").append(newOption);

					// Set the new product as the selected option
					$(".product_id")
						.val(response.product.id)
						.chosen()
						.trigger("chosen:updated");

					// Close the modal
					$("#addProductModal").modal("hide");

					// Clear the form for the next time
					$("#addProductForm")[0].reset();
				} else {
					alert("There was an error adding the product. Please try again.");
				}
			},
			error: function () {
				alert("An error occurred. Please try again.");
			},
		});
	});

	// Show the modal when the "Add Category" link is clicked
	$(".add_category").on("click", function () {
		$("#addCategoryModal").modal("show");
	});

	// Handle the form submission for adding a new category
	$("#addCategoryForm").on("submit", function (e) {
		e.preventDefault();

		$.ajax({
			url: addCategory_url, // Replace with your correct URL
			method: "POST",
			data: $(this).serialize(),
			dataType: "json",
			success: function (response) {
				console.log(response);

				//response = typeof response === "string" ? JSON.parse(response) : response;

				if (response.success) {
					// Append the new category to the dropdown
					var newOption = $("<option></option>")
						.attr("value", response.category.id)
						.text(response.category.name);
					$(".category_id").append(newOption);

					// Set the new category as the selected option
					$(".category_id").val(response.category.id).trigger("chosen:updated");

					// Close the modal
					$("#addCategoryModal").modal("hide");

					// Clear the form for the next time
					$("#addCategoryForm")[0].reset();
				} else {
					alert("There was an error adding the category. Please try again.");
				}
			},
			error: function () {
				alert("An error occurred. Please try again.");
			},
		});
	});

	// Show the modal when the "Add Brand" link is clicked
	$(".add_brand").on("click", function () {
		$("#addBrandModal").modal("show");
	});

	// Handle the form submission for adding a new brand
	$("#addBrandForm").on("submit", function (e) {
		e.preventDefault();

		$.ajax({
			url: addBrand_url, // Replace with your correct URL
			method: "POST",
			data: $(this).serialize(),
			dataType: "json",
			success: function (response) {
				console.log(response);

				if (response.success) {
					// Append the new brand to the dropdown
					var newOption = $("<option></option>")
						.attr("value", response.brand.id)
						.text(response.brand.name);
					$(".brand_id").append(newOption);

					// Set the new brand as the selected option
					$(".brand_id").val(response.brand.id).trigger("chosen:updated");
					updateProductName();
					// Close the modal
					$("#addBrandModal").modal("hide");

					// Clear the form for the next time
					$("#addBrandForm")[0].reset();
				} else {
					alert("There was an error adding the brand. Please try again.");
				}
			},
			error: function () {
				alert("An error occurred. Please try again.");
			},
		});
	});

	$("#brand_id").change(function () {
		updateProductName();
	});

	$(".category_id").change(function () {
		var categoryId = $(this).val();
		if (categoryId) {
			$.ajax({
				url: "<?php echo base_url('admin/products/getProductsByCategory'); ?>",
				type: "POST",
				data: {
					category_id: categoryId,
				},
				success: function (response) {
					$(".all_products_of_category").html(response);
				},
			});
		} else {
			$(".all_products_of_category").html("");
		}
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

function updateProductName() {
	var brandName = $("#brand_id option:selected").text();
	var productName = $.trim($("#name").val());
	$("#name").val(brandName + " ");
}

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

	$("#sub_total").val(subTotal.toFixed(2));
	$("#total_discount").val(totalDiscount.toFixed(2));
	$("#total_gst").val(totalGst.toFixed(2));
	$("#total_amount").val(grandTotal.toFixed(2));
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
