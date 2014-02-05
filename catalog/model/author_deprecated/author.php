<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 04.02.14
 * Time: 15:39
 * To change this template use File | Settings | File Templates.
 */

class ModelAuthorAuthor extends Model{

    function __construct($registry)
    {
        parent::__construct($registry);
        $this->setId('author_id');
        $this->setTableName('author');
        $this->setTableAlias('a');


    }

    function addAuthor($data)
    {
        $author = new AccountAuthorRow();
        $author->name = $data['name'];
        $author->email = $data['email'];
        $author->salt = $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9));
        $author->password = $this->db->escape(sha1($salt . sha1($salt . sha1($data['password']))));
        $author->confirmed = 0;
        $date = new DateTime();
        $author->date_registered = $date->format('Y-m-d h-m-s');

        $this->save($author);

    }

}