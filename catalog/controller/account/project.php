<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 14:33
 * To change this template use File | Settings | File Templates.
 */

class ControllerAccountProject extends Controller{

    private $design;
	
	public function __construct($register)
	{
			parent::__construct($register);
			
			if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/account', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
			
    	    } 
			
			$this->language->load('account/project');
	}

    public function submit()
    {
        $this->setFields(array(
            'prev_release',
            'title',
            'inspiration',
            'colors',
            'description',
            'portfolio',
            'confirm',
            'error',
            'error_title',
            'error_description',
            'error_inspiration',
            'error_prev_release',
            'error_portfolio',
            'error_confirm',

        ));

        $this->load->model('account/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post)) {


            $project = new AccountProjectRow();
            $project->accepted = 1;
            $project->author_id = $this->customer->getId();
            $project->colors = $this->request->post['colors'];
            $project->design = $this->design;
            $project->description = $this->request->post['description'];
            $date = new DateTime();
            $project->date_added = $date->format('Y-m-d h-m-s');
            $project->inspiration = $this->request->post['inspiration'];
            $project->portfolio = $this->request->post['portfolio'];
            $project->prev_release = $this->request->post['prev_release'];
            $project->title = $this->request->post['title'];



            $this->model_account_project->save($project);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('account/success'));


        }

        $this->data['author_regulamin'] = $this->url->link('information/information','&information_id='.$this->config->get('config_author_account_id'));

        $this->load->model('catalog/information');

        $info = $this->model_catalog_information->getInformation($this->config->get('config_author_account_id'));

        $this->data['regulamin'] = $info['title'];

        $this->data['action'] = $this->url->link('account/project/submit');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/project_submit.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/project_submit.tpl';
        } else {
            $this->template = 'default/template/account/project_submit.tpl';
        }

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

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

    private function validate($data)
    {

        $problem = false;

        if(strlen($data['prev_release']) > 255 )
        {
            $this->data['error_prev_release'] = $this->language->get('error_prev_release');
            $problem = true;
        }

        if(strlen($data['portfolio']) > 255 )
        {
            $this->data['error_portfolio'] = $this->language->get('error_portfolio');
            $problem = true;
        }

        if(strlen($data['title']) > 255 OR strlen($data['title']) < 2 )
        {
            $this->data['error_title'] = $this->language->get('error_title');
            $problem = true;
        }

        if(strlen($data['inspiration']) > 255 )
        {
            $this->data['error_inspiration'] = $this->language->get('error_inspiration');
            $problem = true;
        }

        if(strlen($data['description']) > 10000 OR strlen($data['description']) < 10 )
        {
            $this->data['error_description'] = $this->language->get('error_description');
            $problem = true;
        }

        if(!isset($data['confirm']))
        {
            $this->data['error_confirm'] = $this->language->get('error_confirm');
            $problem = true;
        }

        // walidacja wrzuconego pliku z wzorem
        $this->design = $this->upload();


        if(!$this->design)
        {
            $problem = true;

        }


        return $problem;
    }

    public function showList()
    {
        $this->load->model('account/project');


        $q = new DbQBuilder();
        $wh = new DbWhere();
        $wh->setColumn('author_id')
            ->setRelation('=')
            ->setValue($this->customer->getId())
            ->setAlias('pro')
            ->setType('int');

        $q->addWhere($wh);

        $res = $this->model_account_project->getMany($q);



        $repo = new AccountProjectRepo($res);
        $this->data['projects'] = $repo->projects;				$this->load->model('project/status');		 $this->data['statuses'] = $this->model_project_status->getStatuses(2);		 		

        $url = $this->url;
        $this->data['edit'] = function($project_id) use ($url){
                return $url->link('account/project/show','&project_id='.$project_id);
        };

        $this->load->model('tool/image');
        $image = $this->model_tool_image;
        $config = $this->config;

        $this->data['prepare_image'] = function($design) use ($image,$config)
        {
            return $image->resize($design, $config->get('config_image_thumb_width'), $config->get('config_image_thumb_height'));
        };

        $this->data['add'] = $this->url->link('account/project/submit');

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['back'] = $this->url->link('account/account', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/project_list.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/project_list.tpl';
        } else {
            $this->template = 'default/template/account/project_list.tpl';
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

    public function show()
    {
        $this->load->model('account/project');

        if (isset($this->request->get['project_id'])) {

            $res = $this->model_account_project->getOne($this->request->get['project_id']);

            $project = new AccountProjectRow($res[0]);

        }
        else
        {
            $project = false;
        }
		$this->load->model('project/status');		 $this->data['statuses'] = $this->model_project_status->getStatuses(2);  
        $this->data['project'] = $project;

        $this->load->model('tool/image');
        $image = $this->model_tool_image;
        $config = $this->config;

        $this->data['prepare_image'] = function($design) use ($image,$config)
        {
             return $image->resize($design, $config->get('config_image_popup_width'), $config->get('config_image_popup_height'));
        };

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['back'] = $this->url->link('account/project/showList', '', 'SSL');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/project_show.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/account/project_show.tpl';
        } else {
            $this->template = 'default/template/account/project_show.tpl';
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


            move_uploaded_file($this->request->files['file']['tmp_name'], DIR_IMAGE .'data/projects/'. $file);

            return  'data/projects/'. $file;


        }

        return false;
    }

}