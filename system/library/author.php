<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 04.02.14
 * Time: 16:22
 * To change this template use File | Settings | File Templates.
 */

class author {


    private $author;

    private $registry;

    function __get($name)
    {
        return $this->registry->get($name);


    }


    function __construct(Registry $registry)
    {

        $this->registry = $registry;

        if(isset($this->session->data['author_id']))
        {
            $this->load->model('account/author');

            $rows = $this->model_account_author->getOne($this->session->data['author_id']);
            if($rows)
            {
                $this->author = new AccountAuthorRow($rows[0]);
            }
            else
            {
                $this->author = false;
                unset($this->session->data['author_id']);
            }

        }
        else
        {
            $this->author = false;
        }
    }

    public function login($email,$password)
    {
        $author_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "author WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') ");

        if ($author_query->num_rows) {

            $this->session->data['author_id'] = $author_query->row['author_id'];
            $this->load->model('account/author');

            $rows = $this->model_account_author->getOne($author_query->row['author_id']);
            $this->author = new AccountAuthorRow($rows[0]);
            return true;
        }
        else
        {
            return false;
        }

    }

    public function logout()
    {
        unset($this->session->data['author_id']);

        $this->author = false;

    }

    public function isLogged()
    {
        if(isset($this->session->data['author_id']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

}