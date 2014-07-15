<!--NAVBAR-->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
    <button type="button" id="nav-btn" class="navbar-toggle" data-type="open" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
       <a class="navbar-brand" href="<?=base_url('/')?>"><img id="title-icon" src="<?php echo base_url('img/ico/apple-touch-icon-57-precomposed.png')?>" height="45px" width="45px" /><span id="title">HTHS Secret Santa</span></a>
    </div>

    <div class="navbar-collapse collapse" style="overflow: hidden;">
        <?php
        $newMessageCount = 0;
        $navbar = array(
            /**
             * Item settings
             * text: Displayed text in navbar
             * route: base_url() URL route
             * side: left/right side of navbar
             *
             * Item display constraints
             * auth: require logging in?
             * admin: require admin privileges?
             * auth_hide: hide from non-logged in users?
             */
            array('text'=>'About', 'route'=>'about', 'side'=>'left', 'auth'=>false, 'admin'=>false, 'auth_hide'=>false),
            array('text'=>'Discover', 'route'=>'discover', 'side'=>'left', 'auth'=>true, 'admin'=>false, 'auth_hide'=>false),
            array('text'=>'Messages ('.$newMessageCount.')', 'route'=>'messages', 'side'=>'right', 'auth'=>true, 'admin'=>false, 'auth_hide'=>false),
            array('text'=>'Admin Panel', 'route'=>'admin', 'side'=>'right', 'auth'=>true, 'admin'=>true, 'auth_hide'=>false),
            array('text'=>'Profile', 'route'=>'profile', 'side'=>'right', 'auth'=>true, 'admin'=>false, 'auth_hide'=>false),
            array('text'=>'Logout', 'route'=>'logout', 'side'=>'right', 'auth'=>true, 'admin'=>false, 'auth_hide'=>false),
            array('text'=>'Login / Register', 'route'=>'login', 'side'=>'right', 'auth'=>false, 'admin'=>false, 'auth_hide'=>true),
        );

        $navbar_html = array('left'=>'', 'right'=>'');

        $is_logged_in = $this->session->userdata('auth') == 'true';
        $is_admin = $this->session->userdata('admin') == 'true';

        foreach ($navbar as $item) {
            // Check if current route matches item
            $current = ($this->router->uri->uri_string == $item['route']);

            // Check auth and admin constraints
            if ((!$item['auth'] || $is_logged_in)          // require either no login, or user is logged in
             && (!$item['admin'] || $is_admin)        // require either no admin, or user has admin privileges
             && (!$item['auth_hide'] || !$is_logged_in)) { // require either not hiding to logged in, or user is not logged in
                // Append item to specified side
                $navbar_html[$item['side']] .= '<li><a ' . ($current ? 'class="active" ' : '') . 'href="' . base_url($item['route']) . '">' . $item['text'] . '</a></li>';
            }
        }
        ?>
        <ul class="nav navbar-nav">
            <?php echo $navbar_html['left']?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if ($is_logged_in) {
                echo '<li><a id="navbar-greeting">Hi ' . $this->session->userdata('fname') . '!</a></li>';
                //echo '<li><a href="' . base_url('messages') . '">Messages (' . $newMessageCount . ')</li>';
            }
            echo $navbar_html['right'];
            ?>
        </ul>
    </div>
</div>
