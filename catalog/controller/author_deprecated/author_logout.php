<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 10:55
 * To change this template use File | Settings | File Templates.
 */

class ControllerAuthorAuthorLogout extends Controller{

    public function index()
    {



        if($this->author->isLogged())
        {
            $this->author->logout();
        }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/success.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/common/success.tpl';
        } else {
            $this->template = 'default/template/common/success.tpl';
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_message'] = $this->language->get('text_message');

        $this->data['button_continue'] = $this->language->get('button_continue');

        $this->data['continue'] = $this->url->link('common/home');

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home'),
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_author_account'),
            'href'      => $this->url->link('author/author_account', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_author_logout'),
            'href'      => $this->url->link('author/author_logout', '', 'SSL'),
            'separator' => $this->language->get('text_separator')
        );


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

}

