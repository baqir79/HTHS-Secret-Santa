<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Class Login
 */
class Login extends CI_Controller
{
    /**
     * controller index
     */
    public function index()
    {

        require(APPPATH . 'classes/openid.php');

        $openid = new LightOpenID($_SERVER['HTTP_HOST']);
        if (!$openid->mode) {
            // Didn't get login info from the OpenID provider yet / came from the login link
        $openid->identity = 'https://www.google.com/accounts/o8/id';
        $openid->required = array('namePerson/first', 'namePerson/last', 'contact/email');
        header('Location: ' . $openid->authUrl());
        } else if ($openid->mode == 'cancel') {
            // The user decided to cancel logging in, so we'll redirect to the home page instead
            redirect('/');
        } else {
            // The user has logged in and the user's info is ready
            if (!$openid->validate()) {
                // Authentication failed, try logging in again
                $this->login_failure('Authentication failed, try logging in again.');
            } else {
                // Authentication was successful

                // Get user attributes:
                $user_data = $openid->getAttributes();

                // Check to make sure that the user is logging in using a @ctemc.org account or email exception:
                $this->load->model('datamod');
                $domain_restriction = $this->datamod->getGlobalVar('domain_restriction');
                if ($domain_restriction == '' || (preg_match($domain_restriction, $user_data['contact/email'])) || $this->datamod->checkAllowedEmailException($user_data['contact/email'])) {
                    //echo "Welcome, " . " ` . $user_data['namePerson/first'] . ' ' . $user_data['namePerson/last'];

                    $fname = $user_data['namePerson/first'];
                    $lname = $user_data['namePerson/last'];
                    $email = $user_data['contact/email'];

                    // Load user ID if it exists
                    $user_id = $this->datamod->getUserId($email);
                    if ($user_id == false) {
                        $this->datamod->addUser($fname . " " . $lname, $email);
                        $user_id = $this->datamod->getUserId($email);//@todo addUser should return id
                    }
                    //check for admin permissions
                    if (in_array($user_data['contact/email'],$this->datamod->getGlobalVar('admin_users'))) //check against imported admin_users.config file
                        $admin = 'true';
                    else
                        $admin = 'false';

                    //set session info
                    $this->session->set_userdata(array('auth' => 'true', 'admin' => $admin, 'fname' => $fname, 'lname' => $lname,'email' => $email, 'id' => $user_id));

                    //if ($this->datamod->getPrivKey($user_id) == false)
                    //redirect(base_url('secretsanta/survey'));
                    redirect(base_url('/profile'));
                } else {
                    $this->login_failure('Please log in using an authorized email account or contact an administrator.');
                }

            }
        }
    }

    /**
     * Oauth2 compatible login
     * Rename to index after openid is deprecated
     */
    public function oauth2()
    {
        $this->load->model('datamod');
        $this->config->load('oauth');

        $provider = new League\OAuth2\Client\Provider\Google(array(
        'clientId'  =>  $this->config->item('google_client_id'),
        'clientSecret'  =>  $this->config->item('google_client_secret'),
        'redirectUri'   =>  $this->config->item('google_redirect_uri'),
        'scopes' => array('email'),
    ));

        if ( ! isset($_GET['code'])) {

            // If we don't have an authorization code then get one
            header('Location: '.$provider->getAuthorizationUrl());
            exit;
            //redirect('/');

        } else {

            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET['code']
            ]);


            try {

                // We got an access token, let's now get the user's details
                $userDetails = $provider->getUserDetails($token);
                $fname = $userDetails->firstName;
                $lname = $userDetails->lastName;
                $email = $userDetails->email;
                $image = $userDetails->imageUrl;
                //var_dump($userDetails);

                //check that user is in domain restriction or whitelisted
                $domain_restriction = $this->datamod->getGlobalVar('domain_restriction');
                if ($domain_restriction == '' || (preg_match($domain_restriction, $email)) || $this->datamod->checkAllowedEmailException($email)) {

                    // Load user ID if it exists
                    $user_id = $this->datamod->getUserId($email);
                    if ($user_id == false) {
                        $this->datamod->addUser($fname . " " . $lname, $email);
                        $user_id = $this->datamod->getUserId($email);//@todo addUser should return id
                    }
                    //check for admin permissions
                    if (in_array($email,$this->datamod->getGlobalVar('admin_users'))) //check against admin users config
                        $admin = 'true';
                    else
                        $admin = 'false';

                    //set session info
                    $this->session->set_userdata(array('auth' => 'true', 'admin' => $admin, 'fname' => $fname, 'lname' => $lname,'email' => $email, 'id' => $user_id, 'image' =>$image));


                    redirect(base_url('/profile'));
                } else {
                    $this->login_failure('Please log in using an authorized email account or contact an administrator.');
                }


            } catch (Exception $e) {

                // Failed to get user details
                $this->login_failure('Authentication failed, try logging in again.');
            }

            // Use this to interact with an API on the users behalf
            //echo $token->accessToken;

            // Use this to get a new access token if the old one expires
           // echo $token->refreshToken;

            // Number of seconds until the access token will expire, and need refreshing
            //echo $token->expires;
        }
    }


    /**
     * page to render if login fails
     * @param string $message
     */
    private function login_failure($message = 'Login failure')
    {
        //echo $message;
        render("landing",array("icon"=>"&#xf071;","header"=>"Login failure","subheader"=>$message));
    }

    /**
     * login timeout
     */
    public function timeout(){
        render("landing",array("icon"=>"&#xf071;","header"=>"Oops! You don't have permission to view this page.","subheader"=>"Your session has expired, or you are not logged in. Please <a href='/login'>login</a> to continue."));
    }

    /**
     * logout
     */
    public function logout()
    {
        $this->session->sess_destroy();
        render("landing",array("icon"=>"&#xf058;","header"=>"Logout success!","subheader"=>"You have successfully been logged out of your account. Come back soon!"));
    }

}