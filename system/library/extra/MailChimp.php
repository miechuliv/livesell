<?php

//namespace Drewm;

/**
 * Super-simple, minimum abstraction MailChimp API v2 wrapper
 * 
 * Uses curl if available, falls back to file_get_contents and HTTP stream.
 * This probably has more comments than code.
 *
 * Contributors:
 * Michael Minor <me@pixelbacon.com>
 * Lorna Jane Mitchell, github.com/lornajane
 * 
 * @author Drew McLellan <drew.mclellan@gmail.com> 
 * @version 1.1.1
 */
class MailChimp
{
    private $api_key;
    private $api_endpoint = 'https://<dc>.api.mailchimp.com/2.0';
    private $verify_ssl   = false;

    /**
     * Create a new instance
     * @param string $api_key Your MailChimp API key
     */
    function __construct($api_key)
    {
        $this->api_key = $api_key;
        list(, $datacentre) = explode('-', $this->api_key);
        $this->api_endpoint = str_replace('<dc>', $datacentre, $this->api_endpoint);
    }

    /**
     * Call an API method. Every request needs the API key, so that is added automatically -- you don't need to pass it in.
     * @param  string $method The API method to call, e.g. 'lists/list'
     * @param  array  $args   An array of arguments to pass to the method. Will be json-encoded for you.
     * @return array          Associative array of json decoded API response.
     */
    public function call($method, $args=array())
    {
        return $this->makeRequest($method, $args);
    }

    /**
     * Performs the underlying HTTP request. Not very exciting
     * @param  string $method The API method to be called
     * @param  array  $args   Assoc array of parameters to be passed
     * @return array          Assoc array of decoded result
     */
    private function makeRequest($method, $args=array())
    {      
        $args['apikey'] = $this->api_key;

        $url = $this->api_endpoint.'/'.$method.'.json';

        if (function_exists('curl_init') && function_exists('curl_setopt')){
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');       
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($args));
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $json_data = json_encode($args);
            $result    = file_get_contents($url, null, stream_context_create(array(
                'http' => array(
                    'protocol_version' => 1.1,
                    'user_agent'       => 'PHP-MCAPI/2.0',
                    'method'           => 'POST',
                    'header'           => "Content-type: application/json\r\n".
                                          "Connection: close\r\n" .
                                          "Content-length: " . strlen($json_data) . "\r\n",
                    'content'          => $json_data,
                ),
            )));
        }

        return $result ? json_decode($result, true) : false;
    }
}

class OpenChimp
{
    private $mailChimp;

    function __construct()
    {
        $this->mailChimp = new MailChimp(MAIL_CHIMP_API);
    }

    function getList()
    {
        $res = $this->mailChimp->call('lists/list');

        var_dump($res);
    }

    function add($email,$newsletter_type , $firstname = '',$lastname = '')
    {
        $ip = false;

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $date = new DateTime();

        if($newsletter_type == 'daily')
        {
            $list_id = MAIL_CHIMP_LIST_DAILY;
        }
        else{

            $list_id = MAIL_CHIMP_LIST_OCCASIONAL;
        }
// zapisujemy dziada do mailChimp
        try{

            $result = $this->mailChimp->call('lists/subscribe', array(
                // id listy -  trzeba zaciągnąć
                'id'                => $list_id,
                'email'             => array('email'=> $email),
                'merge_vars'        => array('optin_id' => $ip, 'optin_time' => $date->format('Y-m-d h:i:s'),
                'FNAME' => $firstname, 'LNAME' => $lastname ),
                // wysłanie mail również przy update usera
                'double_optin'      => false,
                'update_existing'   => true,
                'replace_interests' => false,
                // wysyła powitalny email, musi zostać potwierdzony aby gość pojawił się na liście
                'send_welcome'      => true,
            ));



            return $result;
        }
        catch(Exception $e)
        {
            trigger_error('Błąd przy synchro z mailchimp '.$email.' '.$newsletter_type);
            return false;
        }


    }

    function remove($email,$newsletter_type,$delete)
    {

        if($newsletter_type == 'daily')
        {
            $list_id = MAIL_CHIMP_LIST_DAILY;
        }
        else{

            $list_id = MAIL_CHIMP_LIST_OCCASIONAL;
        }
// zapisujemy dziada do mailChimp
        try{
            $result = $this->mailChimp->call('lists/unsubscribe', array(
                // id listy -  trzeba zaciągnąć
                'id'                => $list_id,
                'email'             => array('email'=> $email),
                // całkowite usunięcie
                'delete_member' => $delete,
                // potwierdzenie odpisania z listy
                'send_goodbye' => true,

                // potwierdzenie (chyba) do admina
                'send_notify' => true,
            ));

            return $result;
        }
        catch(Exception $e)
        {

            trigger_error('Błąd przy synchro z mailchimp '.$email.' '.$newsletter_type);
            return false;

        }


    }
}




