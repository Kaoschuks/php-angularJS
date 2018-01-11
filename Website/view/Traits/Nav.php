
        <aside class="sidebar sidebar-full sidebar--hidden">
            <div class="scrollbar-inner">

                <ul class="navigation">
                    <li><a href="<?php echo $GLOBALS['config']['SITE'] ?>" class="bold text-black" title="Home Page" >Home</a></li>
                    <li><a href="<?php echo $GLOBALS['config']['SITE'] ?>News" class="bold text-black" title="News Page" >News</a></li>
                    <li><a href="<?php echo $GLOBALS['config']['SITE'] ?>Accounts" class="bold text-black" title="Accounts Page" >Accounts</a></li>
                    <li><a href="<?php echo $GLOBALS['config']['SITE'] ?>Accounts/Logout" class="bold text-black" title="Logout Page" >Logout</a></li>
                    <li><a href="<?php echo $GLOBALS['config']['SITE'] ?>Contact-Us" class="bold text-black" title="Contact Page" >Contact Us</a></li>
                </ul>
            </div>
        </aside>
        <ui-view></ui-view>

