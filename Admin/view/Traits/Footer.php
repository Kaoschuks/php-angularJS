
        </main>

        <!-- Older IE warning message -->
        <!--[if IE]>
            <div class="ie-warning">
                <h1>Warning!!</h1>
                <p>You are using an outdated version of Internet Explorer, please upgrade to any of the following web browsers to access this website.</p>

                <div class="ie-warning__downloads">
                    <a href="http://www.google.com/chrome">
                        <img src="img/browsers/chrome.png" alt="">
                    </a>

                    <a href="https://www.mozilla.org/en-US/firefox/new">
                        <img src="img/browsers/firefox.png" alt="">
                    </a>

                    <a href="http://www.opera.com">
                        <img src="img/browsers/opera.png" alt="">
                    </a>

                    <a href="https://support.apple.com/downloads/safari">
                        <img src="img/browsers/safari.png" alt="">
                    </a>

                    <a href="https://www.microsoft.com/en-us/windows/microsoft-edge">
                        <img src="img/browsers/edge.png" alt="">
                    </a>

                    <a href="http://windows.microsoft.com/en-us/internet-explorer/download-ie">
                        <img src="img/browsers/ie.png" alt="">
                    </a>
                </div>
                <p>Sorry for the inconvenience!</p>
            </div>
        <![endif]-->        
        
        <!-- Javascript -->
        <!-- Vendors -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/tether/dist/js/tether.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/Waves/dist/waves.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/jquery.scrollbar/jquery.scrollbar.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/jquery-scrollLock/jquery-scrollLock.min.js"></script>

        <!-- Vendors: Data tables -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/jszip/dist/jszip.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/vendors/bower_components/datatables.net-buttons/js/buttons.html5.min.js"></script>

        <!-- App functions and actions -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/js/Chart.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/bootstrap/js/bootstrap-notify.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>assets/js/app.min.js"></script>

        <!-- Angular Js -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/angular/angular.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/angular/angular-ui-router.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/angular/angular-local-storage.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/angular/angular-datatable.min.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>libs/angular/angular-sanitize.js"></script>
        <!-- Angular Core App -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/general.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/app.js"></script>
        <!-- Controllers -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/controllers/controller.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/controllers/blog.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/controllers/account.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/controllers/pages.js"></script>
        <!-- Services -->
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/services/request.js"></script>
        <script src="<?php echo $GLOBALS['config']['CDN']?>app/services/services.js"></script>
    </body>
</html>