<section class="contact_page_sec">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="con_form">
                    <h4>Contacts</h4>
                    <form action="" method="post" name="contact" id="contact">

                        <div class="form-group">
                            <label>Name*</label>
                            <input type="text" class="form-control" name="name" placeholder="Enter Name" value="">
                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="text" class="form-control" name="phone" placeholder="Enter Phone Number" value="">
                        </div>


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

                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="con_address">
                    <h6>Company Register Address -</h6>
                    <p>142, Ward no - 4, Arambagh, Sadarghat, Hooghly. Pin - 712601. W.B. INDIA</p>
                    <div class="con_address_boxes">
                        <div class="con_addres_single">
                            <ul>
                                <li><i class="fa-solid fa-house"></i> <span>Kotulpur (Oposite Police Station) Bankura, Pin - 722141 W.B India</span></li>
                                <li><i class="fa-solid fa-phone"></i>(03244) 240 001</li>
                                <li><i class="fa-solid fa-phone"></i>+916294788162</li>
                            </ul>
                        </div>
                        <div class="con_addres_single">
                            <ul>
                                <li><i class="fa-solid fa-house"></i> <span>Kamarpukur choti ( Arambagh Road) Hooghly. Pin- 712612</span></li>
                                <li><i class="fa-solid fa-phone"></i>(03211) 245-512</li>
                                <li><i class="fa-solid fa-phone"></i>+6290254412</li>
                            </ul>
                        </div>
                        <div class="con_addres_single">
                            <ul>
                                <li><i class="fa-solid fa-house"></i> <span>Arambagh Sadarghat Hooghly. Pin - 712601</span></li>
                                <li><i class="fa-solid fa-phone"></i>(03211) 256-583</li>
                                <li><i class="fa-solid fa-phone"></i>+6290279401</li>
                            </ul>
                        </div>
                        <div class="con_addres_single">
                            <ul>
                                <li><i class="fa-solid fa-house"></i> <span>128/12A, Bidhan Sarani, Shyambazar, Near 5 Points. Kolkata, Pin - 700029</span>
                                </li>
                                <li><i class="fa-solid fa-phone"></i>(033) 2533-5333</li>
                                <li><i class="fa-solid fa-phone"></i>6290253600</li>
                            </ul>
                        </div>
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