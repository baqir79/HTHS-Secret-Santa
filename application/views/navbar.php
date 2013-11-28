<!--NAVBAR-->
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="navbar-header">
    <button type="button" id="nav-btn" class="navbar-toggle" data-type="open" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
       <a class="navbar-brand" href="/"><img id="title-icon" src="<?php echo base_url('img/ico/apple-touch-icon-57-precomposed.png')?>" height="45px" width="45px" /><span id="title">HTHS Secret Santa</span></a>
    </div>

    <div class="navbar-collapse collapse" style="overflow: hidden;">
        <ul class="nav navbar-nav">
            <li><a href="<?php echo base_url('about')?>">About</a></li>
            <?php if ($this->session->userdata('auth') == 'true') {
            echo "<li><a href=" . base_url('discover') . ">Discover</a></li>";
            }?>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <?php
            if ($this->session->userdata('auth') == 'true') {
                echo '<li><a style="background: rgba(0,0,0,0)">Hi ' . $this->session->userdata('fname') . '!</a></li>';
                if ($this->session->userdata('admin') == 'true')
                    echo '<li><a href="' . base_url("admin") . '">Admin Panel</a>';
                echo '<li><a href="'. base_url('profile') .'">Profile</a></li>' . '<li><a href="' . base_url('logout') . '">Logout</a>';
            } else {
                echo '<li><a href="' . base_url('login') . '">Login / Register</a></li>';
            }?>
        </ul>
    </div>
</div>
