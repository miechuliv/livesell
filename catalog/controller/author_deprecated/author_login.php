<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 09:47
 * To change this template use File | Settings | File Templates.
 */

class ControllerAuthorAuthorLogin extends Controller{

    public function index(){


        $this->setFields(array(

            'password',
            'email',
            'error_email',
            'error_password',
            'error_login',
            'error_confirmed',

        ));

        if ($this->author->isLogged()) {
            $this->redirect($this->url->link('author/author_account', '', 'SSL'));
        }

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post)) {


            $this->redirect($this->url->link('author/author_account', '', 'SSL'));

        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/author/author_login.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/author/author_login.tpl';
        } else {
            $this->template = 'default/template/author/author_login.tpl';
        }

        $this->data['action'] = $this->url->link('author/author_login');
        $this->data['register'] = $this->url->link('author/author_register');

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

        if (!$this->author->login($data['email'], $data['password'])) {
            $this->data['error_login'] = $this->language->get('error_login');
            $problem = true;
        }

        if ($this->author->isLogged() AND (!$this->author->author OR !$this->author->author->confirmed)) {
            $this->data['error_confirmed'] = $this->language->get('error_approved');
            $problem = true;
        }

        return $problem;
    }
}