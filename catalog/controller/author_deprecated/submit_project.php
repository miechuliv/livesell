<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 04.02.14
 * Time: 14:18
 * To change this template use File | Settings | File Templates.
 */

class ControllerAuthorSubmitProject extends Controller{

    public function index()
    {

        // zapisujemy formularz

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/author/submit_project.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/author/submit_project.tpl';
        } else {
            $this->template = 'default/template/author/submit_project.tpl';
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
}