<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/8
 * Time: 21:51
 */

namespace Qla\Crud\ModelTraits;

trait BaseModelTrait
{

    /**
     * 在add,edit时的验证规则
     * @param int $id
     * @param array $merge
     */
    public static function rules($id = 0, $merge = []){
        return [];
    }

    /**在add,edit时的验证错误信息
     * @param int $id
     * @param array $merge
     */
    public static function messages($id = 0, $merge = []){
        return [];
    }

    /**
     * 在postAdd和postEdit后要进行的操作
     * @param array $data
     */
    public function doAfterCU($data = []){

    }

    /**
     * 在del后要进行的操作
     * @param array $data
     */
    public function doAfterD($id){

    }



    /*
     * 设置Title属性进行html encode
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = htmlspecialchars($value);
    }

    /*
     * 得到Title属性时自动 html decode
     */
    public function getTitleAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }

    /*
     * 设置Name属性进行html encode
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = htmlspecialchars($value);
    }

    /*
     * 得到Name属性时自动 html decode
     */
    public function getNameAttribute($value)
    {
        return htmlspecialchars_decode($value);
    }

}