<?php
/**
 * Abstract controller for authentication services.
 *
 * PHP version 5
 *
 * @category  Joind.in
 * @package   Controllers
 * @copyright 2009 - 2010 Joind.in
 * @license   http://github.com/joindin/joind.in/blob/master/doc/LICENSE JoindIn
 * @link      http://github.com/joindin/joind.in
 */

/**
 * Abstract controller for authentication services.
 *
 * Used as basis for the user, facebook and twitter controllers to add users
 * or do a generic login.
 *
 * @category  Joind.in
 * @package   Controllers
 * @copyright 2012 Joind.in
 * @license   http://github.com/joindin/joind.in/blob/master/doc/LICENSE JoindIn
 * @link      http://github.com/joindin/joind.in
 * @author    Mike van Riel <mike.vanriel@naenius.com>
 *
 * @property CI_Config   $config
 * @property CI_Input    $input
 * @property CI_Session  $session
 * @property CI_Loader   $load
 * @property CI_Template $template
 * @property User_model  $user_model
 */
abstract class AuthAbstract extends Controller
{
    /**
     * Contains an array with urls we don't want to forward to after login.
     * If a part of the url is in one of these items, it will forward them to
     * their main account page.
     *
     * @var Array
     */
    private $non_forward_urls = array('user/login', 'user/forgot');

    protected function _login($user)
    {
        $this->session->set_userdata((array)$user);

        //update login time
        $this->db->where('id', $user->ID);
        $this->db->update('user', array('last_login' => time()));

        // send them back to where they came from, either the referer if they
        // have one, or the flashdata
        $referer = $this->input->server('HTTP_REFERER');
        $to = $this->session->flashdata('url_after_login')
            ? $this->session->flashdata('url_after_login') : $referer;

        // List different routes we don't want to reroute to
        $bad_routes = $this->non_forward_urls;

        foreach ($bad_routes as $route)
        {
            if (strstr($to, $route))
            {
                redirect('user/main');
            }
        }

        // our $to is good, so redirect
        redirect($to);
    }

    protected function _addUser(
        $username, $password, $email, $fullname, $twitter_name
    ) {
        $arr = array(
            'username'         => $username,
            'password'         => $password,
            'email'            => $email,
            'full_name'        => $fullname,
            'twitter_username' => $twitter_name,
            'active'           => 1,
            'last_login'       => time()
        );
        $this->db->insert('user', $arr);
        return current($this->user_model->getUser($arr['username']));
    }
}
