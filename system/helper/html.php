<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 28.01.14
 * Time: 10:07
 * To change this template use File | Settings | File Templates.
 */

function generateDropDown($dataList,$valueKey = false,$textKey = false,$defaultValue = false,$currentValue = false,$name = false,$emptyFirst = false,$id = false,$class = false, $optionClass = false)
{
    $html = '';

    $html .= '<select name="'.$name.'" ';
    if($id)
    {
        $html .= ' id="'.$id.'" ';
    }

    if($class)
    {

        if(is_array($class))
        {

            $html .= ' class="';

             foreach($class as $c)
             {
                 $html .= $c.' ' ;
             }

            $html .= '"';

        }
        else
        {
            $html .= ' class="'.$class.'" ';
        }
    }

    $html .= ' >';

    if($emptyFirst)
    {
        $html .= '<option ></option>';
    }

    foreach($dataList as $key => $value)
    {

        if(!$valueKey)
        {



            $html .= '<option value="'.$value.'" ';

            if($optionClass)
            {
                $html .= ' class="'.$optionClass.'" ';
            }

            if($defaultValue AND $value==$defaultValue)
            {
                $html .= ' selected="selected" ';
            }


            if($currentValue AND ((!is_array($currentValue) AND $value==$currentValue) OR (is_array($currentValue) AND in_array($value,$currentValue))) )
            {
                $html .= ' selected="selected" ';
            }
        }
        elseif(isset($value[$valueKey]))
        {
            $html .= '<option value="'.$value[$valueKey].'" ';

            if($optionClass)
            {
                $html .= ' class="'.$optionClass.'" ';
            }

            if($defaultValue AND $value[$valueKey]==$defaultValue)
            {
                $html .= ' selected="selected" ';
            }

            if($currentValue AND ((!is_array($currentValue) AND $value[$valueKey]==$currentValue) OR (is_array($currentValue) AND in_array($value[$valueKey],$currentValue))) )
            {
                $html .= ' selected="selected" ';
            }
        }

        if(!$textKey)
        {
            $html .= ' >'.$value.'</option>';
        }
        elseif(isset($value[$textKey]))
        {
            $html .= ' >'.$value[$textKey].'</option>';
        }



    }

    $html .= '</select>';

    return $html;
}
