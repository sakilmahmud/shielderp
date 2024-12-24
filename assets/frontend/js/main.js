$(document).ready(function () {
  /***************************Scrol Top Events************************/
  $(window).scroll(function (e) {
    e.preventDefault();
    if ($(this).scrollTop() > 100) {
      $(".to_top").fadeIn("slow");
    } else {
      $(".to_top").fadeOut("slow");
    }
  });

  $(window).scroll(function (e) {
    e.preventDefault();
    if ($(this).scrollTop() > 100) {
      $("header").addClass("active");
    } else {
      $("header").removeClass("active");
    }
  });

  /***************************Scrol To Top Events************************/

  $(".to_top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 2000);
    return false;
  });

  /*****************************Tab Events***************************/

  $("ul.tabs li").click(function () {
    var tab_id = $(this).attr("data-tab");
    $("ul.tabs li").removeClass("current");
    $(".tab-content").removeClass("current");
    $(this).addClass("current");
    $("#" + tab_id).addClass("current");
  });

  $(".home_banner_all").owlCarousel({
    items: 1,
    autoplay: true,
    smartSpeed: 700,
    autoplayHoverPause: true,
    autoHeight: true,
    nav: true,
    dots: true,
    navText: [
      "<img src='assets/images/arrow_left.png' class='img-fluid' alt=''>",
      "<img src='assets/images/arrow_right.png' class='img-fluid' alt=''>",
    ],
  });

  $(".new_arrivals_content_all").owlCarousel({
    items: 5,
    autoplay: true,
    smartSpeed: 700,
    autoplayHoverPause: true,
    autoHeight: true,
    nav: true,
    margin: 20,
    loop: true,
    dots: true,
    responsive: {
      0: {
        items: 1,
      },
      480: {
        items: 1,
      },
      992: {
        items: 4,
      },
    },
    navText: [
      "<i class='fa-solid fa-circle-chevron-left'></i>",
      "<i class='fa-solid fa-circle-chevron-right'></i>",
    ],
  });

  $(".branches_content_all").owlCarousel({
    items: 1,
    autoplay: true,
    smartSpeed: 700,
    autoplayHoverPause: true,
    loop: true,
    autoHeight: true,
    nav: true,
    dots: true,
    navText: [
      "<i class='fa-solid fa-circle-chevron-left'></i>",
      "<i class='fa-solid fa-circle-chevron-right'></i>",
    ],
  });

  $(".similar_products_all").owlCarousel({
    items: 5,
    autoplay: true,
    smartSpeed: 700,
    autoplayHoverPause: true,
    margin: 24,
    loop: true,
    navText: [
      "<i class='fa-solid fa-circle-chevron-left'></i>",
      "<i class='fa-solid fa-circle-chevron-right'></i>",
    ],
  });

  $(".image-clickable").click(function () {
    var src = $(this).attr("src");
    $("#img-holder").css("background-image", "url(" + src + ")");
  });

  var image1Src = "assets/images/lineheart.png";
  var image2Src = "assets/images/col_heart.png";
  var isImage1 = true;

  $(".heart_clickable").click(function () {
    if (isImage1) {
      $(this).attr("src", image2Src);
      isImage1 = false;
    } else {
      $(this).attr("src", image1Src);
      isImage1 = true;
    }
  });

  $("#lightSlider").lightSlider({
    gallery: true,
    item: 1,
    loop: true,
    slideMargin: 0,
    thumbItem: 6,
  });

  /*Quantity Value*/
  $("#increase").on("click", function () {
    var value = parseInt($("#number").val(), 10);
    value = isNaN(value) ? 1 : value;
    value++;
    $("#number").val(value);
  });

  $("#decrease").on("click", function () {
    var value = parseInt($("#number").val(), 10);
    value = isNaN(value) ? 1 : value;
    value > 1 ? value-- : (value = 1);
    $("#number").val(value);
  });
});

/* Price Range Slider*/
function getVals() {
  // Get slider values
  let parent = this.parentNode;
  let slides = parent.getElementsByTagName("input");
  let slide1 = parseFloat(slides[0].value);
  let slide2 = parseFloat(slides[1].value);
  // Neither slider will clip the other, so make sure we determine which is larger
  if (slide1 > slide2) {
    let tmp = slide2;
    slide2 = slide1;
    slide1 = tmp;
  }

  let displayElement = parent.getElementsByClassName("rangeValues")[0];
  let displayElement2 = parent.getElementsByClassName("rangeValues2")[0];
  displayElement.innerHTML = "₹ " + slide1;
  displayElement2.innerHTML = "₹ " + slide2;
}

window.onload = function () {
  // Initialize Sliders
  let sliderSections = document.getElementsByClassName("range-slider");
  for (let x = 0; x < sliderSections.length; x++) {
    let sliders = sliderSections[x].getElementsByTagName("input");
    for (let y = 0; y < sliders.length; y++) {
      if (sliders[y].type === "range") {
        sliders[y].oninput = getVals;
        // Manually trigger event first time to display values
        sliders[y].oninput();
      }
    }
  }
};
