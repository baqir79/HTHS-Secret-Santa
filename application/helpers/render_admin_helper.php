<?

function render_admin($view, $data = null, $title = null)
{
    $CI = & get_instance();

    //consolidate data from admin_config file
    $data['site_name']= $CI->config->item('site_name');
    $data['partner_date'] = new DateTime(date('Y').'-'.$CI->config->item('evt_partner_month')."-".$CI->config->item('evt_partner_day')); //generate partner assignment date
    $data['gift_date'] = new DateTime(date('Y').'-'.$CI->config->item('evt_gift_month').'-'.$CI->config->item('evt_gift_day'));//generate gift exchange date

    $CI->load->view('header', array('title' => $title, 'site_name' => $data['site_name']));
    $CI->load->view('navbar', array('site_name' => $data['site_name']));
    $CI->load->view('admin/sidebar'); //load the admin dependency
    $CI->load->view($view, $data);
    if (!in_array($view, array("index", "landing"))) { //load extra footer content if not on home page
        $CI->load->view('footer');
    }
    $CI->load->view('footer_global'); //always load the global footer (analytics, closing tags, etc)
}