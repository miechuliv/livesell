<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 14:33
 * To change this template use File | Settings | File Templates.
 */

class ControllerProjectProject extends Controller{

    private $design;
    private $baseUrl;

    function __construct($registry)
    {
        parent::__construct($registry);
        $this->baseUrl = $this->remainGet(array(
            'date_start',
            'date_end',
            'author',
            'status',


        ));

        $this->load->language('project/project');

    }

    public function addNote()
    {
        error_reporting(E_ALL);
ini_set('display_errors', '1');

        $note = $this->request->post['note'];
        $project_id = $this->request->post['project_id'];

        $this->load->model('project/note');

        $project_note_id = $this->model_project_note->insert(array(
            'note' => $note,
            'project_id' => $project_id
        ));

        echo $project_note_id;
    }

    public function updateNote()
    {
        $note = $this->request->post['note'];

        $note_id = $this->request->post['note_id'];

        $this->load->model('project/note');

        $this->model_project_note->update(array(
            'note' => $note,

        ),$note_id);
    }

    public function deleteNote()
    {

        $note_id = $this->request->post['note_id'];

        $this->load->model('project/note');

        $this->model_project_note->delete($note_id);
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

        $this->load->model('project/project');


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post)) {


            $project = new ProjectProjectRow();
            $project->accepted = 0;
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



            $this->model_project_project->save($project);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('project/success'));


        }

        $this->data['action'] = $this->url->link('project/project/submit',$this->baseUrl);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/project/project_submit.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/project/project_submit.tpl';
        } else {
            $this->template = 'default/template/project/project_submit.tpl';
        }

        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');
        $this->data['back'] = $this->url->link('project/project', '', 'SSL');

        $this->children = array(

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






        return $problem;
    }

    public function delete()
    {
        $this->load->model('project/project');

        if (isset($this->request->get['project_id'])) {

            $res = $this->model_project_project->getOne($this->request->get['project_id'],false,true);


        }

        $this->redirect($this->url->link('project/project/showList', $this->baseUrl.'&token='.$this->session->data['token'], 'SSL'));

    }

    public function showList()
    {
        $this->load->model('project/project');

        // filtracja

        $this->setFields(array(
                'date_start',
                'date_end',
                'status',
                'author',
            ),
            false,
            'get');




        if(isset($this->request->get['page']))
        {
            $page = $this->request->get['page'];
        }
        else
        {
            $page = 1;
        }


        $total_projects = $this->model_project_project->getTotals($this->request->get);


        $this->request->get['start'] = ($page-1)*20;
        $this->request->get['limit'] = $page*20;

        $res = $this->model_project_project->getProjects($this->request->get);


        $repo = new ProjectProjectRepo($res);
        $this->data['projects'] = $repo->projects;



        $url = $this->url;
        $params = $this->baseUrl;
        $session = $this->session;
        $this->data['edit'] = function($project_id) use ($url,$params,$session){
             return $url->link('project/project/show','&project_id='.$project_id.$params.'&token='.$session->data['token']);
        };

        $this->data['delete'] = function($project_id) use ($url,$params,$session){
            return $url->link('project/project/delete','&project_id='.$project_id.$params.'&token='.$session->data['token']);
        };

        $this->data['campaign'] = function($project_id) use ($url,$params,$session){
            return $url->link('project/campaign/insert','&project_id='.$project_id.$params.'&token='.$session->data['token']);
        };

        $this->load->model('tool/image');
        $image = $this->model_tool_image;
        $config = $this->config;

        $this->data['prepare_image'] = function($design) use ($image,$config)
        {
            return $image->resize($design, $config->get('config_image_thumb_width'), $config->get('config_image_thumb_height'));
        };

       // $this->data['add'] = $this->url->link('project/project/submit');

        $this->data['button_continue'] = $this->language->get('button_continue',$this->baseUrl);
        $this->data['button_back'] = $this->language->get('button_back',$this->baseUrl);
        $this->data['back'] = $this->url->link('project/project', $this->baseUrl.'&token='.$this->session->data['token'], 'SSL');

        $this->load->model('project/status');

        $this->data['statuses'] = $this->model_project_status->getStatuses(2);

        $pagination = new Pagination();
        $pagination->total = $total_projects;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('project/project/showList', 'token=' . $this->session->data['token'] . $this->baseUrl . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->template = 'project/project_list.tpl';

        $this->children = array(

            'common/footer',
            'common/header'
        );


        $this->response->setOutput($this->render());
    }

    public function show()
    {
        $this->load->model('project/project');


        $this->setFields(array(
            'status',
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
            'error_status'

        ));

        if (isset($this->request->get['project_id'])) {

            $q = new DbQBuilder();
            $wh = new DbWhere();
            $q->setSelect('pro.*, cu.firstname as author, pro.accepted as status, cu.email as email ');
            $wh->setAlias('pro')
                ->setRelation('=')
                ->setValue($this->request->get['project_id'])
                ->setColumn('project_id')
                ->setType('int');

            $l = new DbLimit();
            $l->setStart(0)
                ->setStop(1);

            $q->addWhere($wh);
            $q->setLimit($l);

            $res = $this->model_project_project->getMany($q);


            $project = new ProjectProjectRow($res[0]);


            $this->setFields(array(
                'status',
                'prev_release',
                'title',
                'inspiration',
                'design',
                'author',
                'email',
                'colors',
                'description',
                'portfolio',
                'confirm',


            ),$project);

            $this->load->model('project/note');

            $this->data['notes'] = $this->model_project_note->getByProject($project->ID);

            $this->load->model('user/user');

            $this->data['token'] = $this->session->data['token'];



            foreach($this->data['notes']  as $key => $note)
            {
                $user = $this->model_user_user->getUser($note['user_id']);
                $this->data['notes'][$key]['user'] = $user['username'];
            }





        }
        else
        {
            $project = new ProjectProjectRow();
        }



        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post)) {


            $project->accepted = $this->request->post['status'];

            $project->colors = $this->request->post['colors'];

            $project->description = $this->request->post['description'];
            $project->inspiration = $this->request->post['inspiration'];
            $project->portfolio = $this->request->post['portfolio'];
            $project->prev_release = $this->request->post['prev_release'];
            $project->title = $this->request->post['title'];



            $this->model_project_project->save($project);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('project/project/showList', $this->baseUrl.'&token='.$this->session->data['token'], 'SSL'));


        }




        $this->data['action'] =  $this->url->link('project/project/show',$this->baseUrl.'&token='.$this->session->data['token'].'&project_id='.$this->request->get['project_id'], 'SSL');


        $this->load->model('project/status');

        $this->data['statuses'] = $this->model_project_status->getStatuses(2);

        $this->data['project'] = $project;

        $this->load->model('tool/image');
        $image = $this->model_tool_image;
        $config = $this->config;

        $this->data['prepare_image'] = function($design) use ($image,$config)
        {
             return $image->resize($design, $config->get('config_image_popup_width'), $config->get('config_image_popup_height'));
        };

        $url = $this->url;
        $params = $this->baseUrl;
        $session = $this->session;

        $this->data['campaign'] = function($project_id) use ($url,$params,$session){
            return $url->link('project/campaign/insert','&project_id='.$project_id.$params.'&token='.$session->data['token']);
        };

        $this->data['button_continue'] = $this->language->get('button_continue',$this->baseUrl);
        $this->data['button_back'] = $this->language->get('button_back',$this->baseUrl);
        $this->data['back'] = $this->url->link('project/project/showList',$this->baseUrl.'&token='.$this->session->data['token'], 'SSL');

        $this->template = 'project/project_form.tpl';

        $this->children = array(

            'common/footer',
            'common/header'
        );



        $this->response->setOutput($this->render());
    }



}