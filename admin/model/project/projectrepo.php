<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 16:19
 * To change this template use File | Settings | File Templates.
 */

class ProjectProjectRepo {

    public $projects;

    function __construct($rows)
    {
        foreach($rows as $row)
        {
            $this->projects[] = new ProjectProjectRow($row);
        }
    }

    public function addCategory(ProjectProjectRow $project)
    {
        $this->projects[] = $project;
    }

}