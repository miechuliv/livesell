<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 04.02.14
 * Time: 14:45
 * To change this template use File | Settings | File Templates.
 */

class ControllerAuthorAuthorRegister extends Controller{

    public function index()
    {

        $this->setFields(array(
            'name',
            'password',
            'confirm_password',
            'email',
            'confirm_email',
            'error_name',
            'error_email',
            'error_confirm_email',
            'error_password',
            'error_confirm_password',
        ));


        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post)) {

            $this->load->model('author/author');
            $this->model_author_author->addAuthor($this->request->post);

            $this->author->login($this->request->post['email'], $this->request->post['password']);

            // wysylka maila
            $subject = sprintf($this->language->get('text_subject'), $this->config->get('config_name'));
            $message = sprintf($this->language->get('text_welcome'), $this->config->get('config_name')) . "\n\n";

            $message .= $this->url->link('author/author_login', '', 'SSL') . "\n\n";
            $message .= $this->language->get('text_services') . "\n\n";

            $message .= $this->language->get('text_thanks') . "\n";
            $message .= $this->config->get('config_name');

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->hostname = $this->config->get('config_smtp_host');
            $mail->username = $this->config->get('config_smtp_username');
            $mail->password = $this->config->get('config_smtp_password');
            $mail->port = $this->config->get('config_smtp_port');
            $mail->timeout = $this->config->get('config_smtp_timeout');
            $mail->setTo($this->request->post['email']);
            $mail->setFrom($this->config->get('config_email'));
            $mail->setSender($this->config->get('config_name'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
            $mail->send();

            // do admina
            if ($this->config->get('config_account_mail')) {
                $message  = $this->language->get('text_author_signup') . "\n\n";
                $message .= $this->language->get('text_website') . ' ' . $this->config->get('config_name') . "\n";

                $message .= $this->language->get('text_name') . ' ' . $this->request->post['lastname'] . "\n";

                $message .= $this->language->get('text_email') . ' '  .  $$this->request->post['email'] . "\n";


                $mail->setTo($this->config->get('config_email'));
                $mail->setSubject(html_entity_decode($this->language->get('text_new_author'), ENT_QUOTES, 'UTF-8'));
                $mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
                $mail->send();

                // Send to additional alert emails if new account email is enabled
                $emails = explode(',', $this->config->get('config_alert_emails'));

                foreach ($emails as $email) {
                    if (strlen($email) > 0 && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
                        $mail->setTo($email);
                        $mail->send();
                    }
                }
            }


            $this->redirect($this->url->link('account/success'));
        }



        $this->data['action'] = $this->url->link('author/author_register');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/author/author_register.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/author/author_register.tpl';
        } else {
            $this->template = 'default/template/author/author_register.tpl';
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

    private function validate($data)
    {
        $problem = false;

          if(strlen($data['name']) < 2 OR strlen($data['name']) > 255)
          {
              $this->data['error_name'] = 'Name to long or short';
              $problem = true;
          }

        if(strlen($data['password']) < 2 OR strlen($data['password']) > 255)
        {
            $this->data['error_password'] = 'Password to long or short';
            $problem = true;
        }

        if($data['password'] != $data['confirm_password'])
        {
            $this->data['error_confirm_password'] = 'Passwords do not match';
            $problem = true;
        }

        if(strlen($data['email']) < 2 OR strlen($data['email']) > 255 OR  !filter_var($data['email'], FILTER_VALIDATE_EMAIL))
        {
            $this->data['error_email'] = 'Email bad';
            $problem = true;
        }

        if($data['email'] != $data['confirm_email'])
        {
            $this->data['error_confirm_email'] = 'Email do not match';
            $problem = true;
        }

        return $problem;
    }
}