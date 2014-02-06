<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */

class ModelAccountProject extends Model{

    function __construct($registry)
    {
        parent::__construct($registry);
        $this->setId('project_id');
        $this->setTableName('project');
        $this->setTableAlias('pro');


    }

}