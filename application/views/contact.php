<section class="contact">
    <div class="addtocart_sec">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="cartlist">
                        <h4>Contacts</h4>
                        <form action="" method="post" name="contact" id="contact">
                            <div class="form_column">
                                <div class="form-group">
                                    <label>Name*</label>
                                    <input type="text" class="form-control" name="name" placeholder="Enter Name" value="">
                                </div>
                                <div class="form-group">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number" value="">
                                </div>
                            </div>
                            <div class="form_column">
                                <div class="form-group">
                                    <label>Email*</label>
                                    <input type="email" class="form-control" name="email" placeholder="Enter Email" value="">
                                </div>

                                <div class="form-group">
                                    <label>Message*</label>
                                    <textarea name="msg" id="msg" class="form-control" placeholder="Enter Message"></textarea>
                                </div>
                                <div class="align">
                                    <button type="submit" class="text-white fw-medium btn bg_btn">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    // Send a message to the parent window that the contact page is loaded
    window.parent.postMessage("/contact", "*");
</script>