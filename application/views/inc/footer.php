<footer>
    <div class="footer_top">
        <div class="container">
            <div class="row">
                <div class="col-lg-4">
                    <div class="footer_col footer_about">
                        <a href="<?php echo base_url(); ?>">
                            <img src="<?php echo getSetting('admin_logo') ? base_url(getSetting('admin_logo')) : base_url('assets/frontend/images/logo.png') ?>" class="img-fluid" alt="GC Footer Logo" /></a>
                        <!-- <p>
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
                    do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua. Ut enim ad minim veniam.
                  </p> -->
                        <ul class="footer_address">
                            <li>
                                Address: Gholar More, Usthi, South 24 Parganas<br>
                                West Bengal, India, 743375
                            </li>
                            <li>Phone: <a href="tel:+913369028204">(033) 6902 8204</a></li>
                            <li>Whatsapp: <a href="https://wa.me/9732138374" target="_blank">9732138374</a></li>
                            <li>Email: <a href="mailto:info@gcshop.in">info@gcshop.in</a>, <a href="mailto:globalcomputers19@gmail.com">globalcomputers19@gmail.com</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer_col footer_list">
                        <h5>Services</h5>
                        <ul>
                            <li><a href="#">PC Services</a></li>
                            <li><a href="#">Laptop Services</a></li>
                            <li><a href="#">Printer Services</a></li>
                            <li><a href="#">CCTV Services</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer_col footer_list">
                        <h5>Important Links</h5>
                        <ul>
                            <li><a href="#"> No Cost EMI</a></li>
                            <li><a href="#"> Billing Software</a></li>
                            <li><a href="#"> ERP Systems</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer_col footer_list">
                        <h5>About</h5>
                        <ul>
                            <li><a href="<?php echo base_url('about') ?>">About Us</a></li>
                            <li><a href="<?php echo base_url('career') ?>"> Career</a></li>
                            <li><a href="<?php echo base_url('refund-return-policy') ?>">Refund Return Policy</a></li>
                            <li><a href="<?php echo base_url('cancellation-policy') ?>">Cancellation Policy</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer_col footer_social">
                        <h5>Follow Us</h5>
                        <ul>
                            <li>
                                <a href="https://www.facebook.com/gcshop.in" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa-brands fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="#"><i class="fa-brands fa-instagram"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer_bottom">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <p>© Global Computers <?= date('Y') ?> | All Rights Reserved</p>
                </div>
                <div class="col-lg-6">
                    <ul>
                        <li><a href="<?php echo base_url('terms') ?>">Trams & Condition</a></li>
                        <li><a href="<?php echo base_url('privacy') ?>">Privacy Policy</a></li>
                        <li><a href="<?php echo base_url('contact') ?>">Contact Us</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<a href="#" class="to_top"><i class="fa-solid fa-chevron-up"></i></a>

<script src="<?php echo base_url('assets/frontend/js/bootstrap.bundle.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/aos.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/jquery.fancybox.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/owl.carousel.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/frontend/js/main.js'); ?>"></script>

</body>

</html>