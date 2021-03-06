<?php

/**
 * helper to inject additional data and framework views to view to be rendered
 * @param $view
 * @param null $data
 * @param null $title
 */
function render($view, $data = null, $title = null)
{
    $CI = & get_instance();
    $CI->load->model('datamod');


    //consolidate data from admin_config file
    $g_vars = $CI->datamod->getGlobalVar();

    //ensure that setup script has been run
    if ($g_vars['setup'] == true){
        $data['first_year'] = $g_vars['first_year'];
        $data['site_name'] = $g_vars['site_name'];
        $data['partner_date'] = new DateTime(date('Y') . '-' . $g_vars['evt_partner_date'][0] . "-" . $g_vars['evt_partner_date'][1]); //generate partner assignment date
        $data['gift_date'] = new DateTime(date('Y') . '-' . $g_vars['evt_gift_date'][0] . '-' . $g_vars['evt_gift_date'][1]);//generate gift exchange date

        $CI->load->view('header', array('title' => $title, 'site_name' => $data['site_name']));
        $CI->load->view('navbar', array('site_name' => $data['site_name']));
    }
    else {
        $CI->load->view('header', array('title' => $title, 'site_name' => 'Secret Santa'));
    }

    $CI->load->view($view, $data);
    if (!in_array($view,array("index","landing"))){//load extra footer content if not on home page
        $CI->load->view('footer');
    }
    $CI->load->view('footer_global');//always load the global footer (analytics, closing tags, etc)
}
