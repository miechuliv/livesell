<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 11:18
 * To change this template use File | Settings | File Templates.
 */

class ControllerModuleAuthor {

    public function index()
    {

        $this->data['logged'] = $this->customer->isLogged();
        $this->data['register'] = $this->url->link('author/register', '', 'SSL');
        $this->data['login'] = $this->url->link('author/login', '', 'SSL');
        $this->data['logout'] = $this->url->link('author/logout', '', 'SSL');
        $this->data['forgotten'] = $this->url->link('author/forgotten', '', 'SSL');
        $this->data['account'] = $this->url->link('author/account', '', 'SSL');
        $this->data['edit'] = $this->url->link('author/edit', '', 'SSL');
        $this->data['password'] = $this->url->link('author/password', '', 'SSL');

        $this->data['projects'] = $this->url->link('author/projects', '', 'SSL');
        $this->data['project_submit'] = $this->url->link('author/project/submit', '', 'SSL');



        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/author.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/author.tpl';
        } else {
            $this->template = 'default/template/module/author.tpl';
        }

        $this->render();
    }

}