<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 13:27
 * To change this template use File | Settings | File Templates.
 */

class ControllerAccountAvatar extends Controller{

	public function __construct($register)
	{
			parent::__construct($register);
			
			if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/avatar', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
			
    	    } 
	}

    public function index()
    {

        $this->load->model('account/customer');
        $this->load->model('tool/image');
		
		$this->language->load('account/avatar');

        $this->data['error'] = false;

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($avatar = $this->validate())!=false) {

            $this->model_account_customer->addAvatar($avatar);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('account/account', '', 'SSL'));
        }

        $customer = $this->model_account_customer->getCustomer($this->customer->getId());

        if($customer['avatar'])
        {
            $this->data['avatar'] = $this->model_tool_image->resize($customer['avatar'], $this->config->get('config_image_thumb_width'), $this->config->get('config_image_thumb_height'));
        }
        else
        {
            $this->data['avatar'] = false;
        }

        $this->data['action'] = $this->url->link('account/avatar');


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/avatar.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/avatar.tpl';
        } else {
            $this->template = 'default/template/account/avatar.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render());
    }

    private function validate()
    {
		if(!isset($this->request->post['default_avatar']))		{			return $this->upload();		}		else		{			return 'data/avatars/default_avatar.jpg';		}
        
    }

    private function upload() {



        if (!empty($this->request->files['file']['name'])) {
            $filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));

            if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
                $this->data['error'] = $this->language->get('error_filename');
            }

            // Allowed file extension types
            $allowed = array();

            $filetypes = explode("\n", $this->config->get('config_file_extension_allowed'));

            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

            if (!in_array(substr(strrchr($filename, '.'), 1), $allowed)) {
                $this->data['error'] = $this->language->get('error_filetype');
            }

            // Allowed file mime types
            $allowed = array();

            $filetypes = explode("\n", $this->config->get('config_file_mime_allowed'));



            foreach ($filetypes as $filetype) {
                $allowed[] = trim($filetype);
            }

         
            if (!in_array($this->request->files['file']['type'], $allowed)) {

                $this->data['error'] = $this->language->get('error_filetype');
            }

            if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
                $this->data['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
            }
        } else {
            $this->data['error'] = $this->language->get('error_upload');
        }



        if (!$this->data['error'] && is_uploaded_file($this->request->files['file']['tmp_name']) && file_exists($this->request->files['file']['tmp_name'])) {
            $file = uniqid().basename($filename);


            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_IMAGE .'data/avatars/'. $file);

            return 'data/avatars/'. $file;

        }

        return false;
    }

}