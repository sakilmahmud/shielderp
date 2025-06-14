<section class="contact_page_sec">
    <div class="container">
        <div class="row">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="con_form">
                    <h2>Contacts</h2>
                    <form action="" method="post" name="contact" id="contact">
                        <div class="form-group">
                            <label>Name*</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name" required>
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number">
                        </div>
                        <div class="form-group">
                            <label>Email*</label>
                            <input type="email" class="form-control" name="email" placeholder="Enter Email" required>
                        </div>
                        <div class="form-group">
                            <label>Message*</label>
                            <textarea name="msg" id="msg" class="form-control" placeholder="Enter Message" required></textarea>
                        </div>
                        <div class="align">
                            <button type="submit" id="submitBtn" class="text-white fw-medium btn bg_btn">Submit</button>
                            <div id="formMsg" class="mt-2"></div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Contact Details and Map -->
            <div class="col-lg-6">
                <div class="con_address mb-4">
                    <h4>GLOBAL COMPUTERS</h4>
                    <p>Gholar More, Usthi, South 24 Parganas<br>
                        West Bengal, India, 743375</p>
                    <p>
                        <strong>Phone:</strong>
                        <a href="tel:+913369028204">(033) 6902 8204</a> |
                        <strong> WhatsApp:</strong>
                        <a href="https://wa.me/919732138374" target="_blank">9732138374</a>
                    </p>
                    <p>
                        <strong>Email:</strong>
                        <a href="mailto:info@gcshop.in">info@gcshop.in</a> | <a href="mailto:globalcomputers19@gmail.com">globalcomputers19@gmail.com</a>
                    </p>
                </div>

                <!-- Google Map Embed -->
                <div class="map-responsive">
                    <iframe
                        src="https://www.google.com/maps?q=Global+Computers+Gholar+More+Usthi&output=embed"
                        width="100%" height="300" frameborder="0" style="border:1px solid #ccc;" allowfullscreen=""
                        loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Responsive map style -->
<style>
    .map-responsive {
        overflow: hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
    }

    .map-responsive iframe {
        left: 0;
        top: 0;
        height: 100%;
        width: 100%;
        position: absolute;
    }

    #formMsg {
        font-size: 14px;
    }

    .contact_page_sec {
        padding: 60px 0px;
        margin-top: 0px !important;
        background: #e0f7ec;
    }
</style>

<!-- AJAX Script -->
<script>
    $('#contact').on('submit', function(e) {
        e.preventDefault();

        const $btn = $('#submitBtn');
        const $msg = $('#formMsg');
        $btn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: "<?= base_url('contact/submit') ?>",
            method: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response) {
                $msg.removeClass().addClass('text-success').text(response.message);
                $('#contact')[0].reset();
            },
            error: function() {
                $msg.removeClass().addClass('text-danger').text("Something went wrong. Please try again.");
            },
            complete: function() {
                $btn.prop('disabled', false).text('Submit');
            }
        });
    });
</script>