<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 14:37
 * To change this template use File | Settings | File Templates.
 */

class ProjectProjectRow extends DbRow{

    public $author_id;
    public $author;
    public $status;
    public $prev_release;
    public $title;
    public $inspiration;
    public $colors;
    public $design;
    public $portfolio;
    public $accepted;
    public $description;
    public $date_added;

    public $primaryKey = 'project_id';

    public $map = array(
        'author_id' => array(
            'type' => 'int',
            'column' => 'author_id',

        ),
        'prev_release' => array(
            'type' => 'string',
            'column' => 'prev_release',

        ),
        'title' => array(
            'type' => 'string',
            'column' => 'title',

        ),
        'description' => array(
            'type' => 'string',
            'column' => 'description',

        ),
        'date_added' => array(
            'type' => 'string',
            'column' => 'date_added',

        ),
        'inspiration' => array(
            'type' => 'string',
            'column' => 'inspiration',

        ),
        'colors' => array(
            'type' => 'int',
            'column' => 'colors',

        ),
        'design' => array(
            'type' => 'string',
            'column' => 'design',

        ),
        'portfolio' => array(
            'type' => 'string',
            'column' => 'portfolio',

        ),
        'accepted' => array(
            'type' => 'int',
            'column' => 'accepted',

        ),
    );

    function __construct($row = false)
    {
        $this->accepted = $row['accepted'];
        $this->author_id = $row['author_id'];
        $this->colors = $row['colors'];
        $this->design = $row['design'];
        $this->inspiration = $row['inspiration'];
        $this->ID = $row['project_id'];
        $this->portfolio = $row['portfolio'];
        $this->prev_release = $row['prev_release'];
        $this->description = $row['description'];
        $this->date_added = $row['date_added'];

        $this->title = $row['title'];

        $this->author = isset($row['author'])?$row['author']:false;
        $this->status = isset($row['status'])?$row['status']:false;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }


}