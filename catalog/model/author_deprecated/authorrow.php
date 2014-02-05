<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 04.02.14
 * Time: 15:29
 * To change this template use File | Settings | File Templates.
 */

class AuthorAuthorRow extends DbRow{

    public $name;
    public $password;
    public $email;
    public $date_registered;
    public $confirmed;
    public $salt;
    public $ID;

    public $primaryKey = 'author_id';

    public $map = array(
        'name' => array(
            'type' => 'string',
            'column' => 'name',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),
        'password' => array(
            'type' => 'string',
            'column' => 'password',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),
        'salt' => array(
            'type' => 'string',
            'column' => 'salt',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),
        'date_registered' => array(
            'type' => 'string',
            'column' => 'date_registered',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),
        'email' => array(
            'type' => 'string',
            'column' => 'email',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),
        'confirmed' => array(
            'type' => 'int',
            'column' => 'confirmed',
            'relation' => false,
            'foreignTable' => false,
            'required' => true,
        ),

    );

    function __construct($row = false)
    {
        $this->name = $row['name'];
        $this->confirmed = $row['confirmed'];
        $this->date_registered = $row['date_registered'];
        $this->password = $row['password'];
        $this->email = $row['email'];
        $this->salt = $row['salt'];
        $this->ID = $row['author_id'];
    }
}