<?php

/**
 * 表单类
 * @filename  From.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017/8/8 14:58
 */

namespace APP\Helper;

class From
{

    /**
     * 下拉选择框
     */
    public static function select($array = array(), $id = 0, $str = '', $default_option = '',$select2_name='')
    {

        $string = '<select ' . $str . '>';
        $default_selected = (empty($id) && $default_option) ? 'selected' : '';
        if ($default_option){
            $string .= "<option value='' $default_selected>$default_option</option>";
        }

        $ids = array();
        if (isset($id)) {
            $ids = explode(',', $id);
        }
        if(is_array($array)){
            foreach ($array as $key => $value) {
                $selected = in_array($key, $ids) ? 'selected' : '';
                $string .= '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
            }
        }

        $string .= '</select>';
        if($select2_name){
            $string.="<script>$('select[name=\"".$select2_name."\"]').select2();</script>";
        }

        echo $string;
    }

    /**
     * 复选框
     *
     * @param $array 选项 二维数组
     * @param $id 默认选中值，多个用 '逗号'分割
     * @param $str 属性
     * @param $defaultvalue 是否增加默认值 默认值为 -99
     * @param $width 宽度
     * @return string
     */
    public static function checkbox($array = array(), $id = '', $str = '', $defaultvalue = '', $width = 5, $field = '')
    {
        $string = '';
        $id = trim($id);
        if ($id != ''){
            $id = strpos($id, ',') ? explode(',', $id) : array($id);
        }
        if ($defaultvalue){
            $string .= '<input type="hidden" ' . $str . ' value="-99">';
        }
        $i = 1;
        foreach ($array as $key => $value) {
            $key = trim($key);
            $checked = ($id && in_array($key, $id)) ? 'checked' : '';
            if ($width){
                $string .= '<label class="checkbox-inline" >';
            }
            $string .= '<input type="checkbox" ' . $str . ' id="' . $field . '_' . $i . '" ' . $checked . ' value="' . $key . '"> ' . $value;
            if ($width){
                $string .= '</label>';
            }
            $i++;
        }
        echo $string;
    }

    /**
     * 单选框
     *
     * @param $array 选项 二维数组
     * @param $id 默认选中值
     * @param $str 属性
     */
    public static function radio($array = array(), $id = 0, $str = '', $width = 1, $field = '')
    {
        $string = '';
        foreach ($array as $key => $value) {
            $checked = trim($id) == trim($key) ? 'checked' : '';
            if ($width)
                $string .= '<label class="ib">';
            $string .= '<input type="radio" ' . $str . 'style="margin-top:12px" id="' . $field . '_' . $key . '" ' . $checked . ' value="' . $key . '"> ' . $value.'&nbsp;&nbsp;&nbsp;&nbsp;';
            if ($width)
                $string .= '</label>';
        }
        echo $string;
    }

}
