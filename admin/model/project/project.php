<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 05.02.14
 * Time: 15:11
 * To change this template use File | Settings | File Templates.
 */

class ModelProjectProject extends Model{

    function __construct($registry)
    {
        parent::__construct($registry);
        $this->setId('project_id');
        $this->setTableName('project');
        $this->setTableAlias('pro');

        $join = new DbJoin();
        $join->setTableName('project_status')
            ->setAlias('pros')
            ->setKey('project_status_id')
            ->setKeyLeft('accepted')
            ->setType('LEFT');

        $this->addDefaultJoins($join);

        $join2 = new DbJoin();
        $join2->setTableName('customer')
            ->setAlias('cu')
            ->setKey('customer_id')
            ->setKeyLeft('author_id')
            ->setType('LEFT');

        $this->addDefaultJoins($join2);

        $wh = new DbWhere();
        $wh->setRelation('=')
            ->setColumn('language_id')
            ->setAlias('pros')
            ->setType('int')
            ->setValue(2);

        $this->addDefaultWheres($wh);

    }

    private function addConditions($data,DbQBuilder &$q)
    {
        if(isset($data['date_start']))
        {
            $wh = new DbWhere();
            $wh->setAlias('pro')
                ->setValue($data['date_start'])
                ->setType('string')
                ->setColumn('date_added')
                ->setRelation('>');

            $q->addWhere($wh);
        }

        if(isset($data['date_end']))
        {
            $wh = new DbWhere();
            $wh->setAlias('pro')
                ->setValue($data['date_end'])
                ->setType('string')
                ->setColumn('date_added')
                ->setRelation('<');

            $q->addWhere($wh);
        }

        if(isset($data['author']))
        {
            $wh = new DbWhere();
            $wh->setAlias('cu')
                ->setValue($data['author'])
                ->setType('string')
                ->setColumn('firstname')
                ->setRelation('LIKE');

            $q->addWhere($wh);
        }

        if(isset($data['status']))
        {
            $wh = new DbWhere();
            $wh->setAlias('pros')
                ->setValue($data['status'])
                ->setType('int')
                ->setColumn('project_status_id')
                ->setRelation('=');

            $q->addWhere($wh);
        }

        if(isset($data['start']) AND isset($data['limit']))
        {

            $limit = new DbLimit();
            $limit->setStart($data['start'])
                ->setStop($data['limit']);
            $q->setLimit($limit);
        }
        else
        {
            $limit = new DbLimit();
            $limit->setStart(0)
                ->setStop(20);
            $q->setLimit($limit);
        }



    }

    function getProjects($data)
    {


        $q = new DbQBuilder();

        $q->setSelect('pro.* , pros.name as status , cu.firstname as author, cu.email as email ');

        $this->addConditions($data,$q);



        $res = $this->getMany($q);


        return $res;
    }

    function getTotals($data)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $q = new DbQBuilder();

        $q->setSelect(' COUNT(DISTINCT(project_id)) as count ');

        $this->addConditions($data,$q);

        $res = $this->getMany($q);

        return $res[0]['count'];
    }




}