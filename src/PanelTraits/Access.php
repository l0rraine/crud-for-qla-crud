<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/26
 * Time: 15:39
 */

namespace Qla\Crud\PanelTraits;

trait Access
{
    /*
    |--------------------------------------------------------------------------
    |                                   CRUD ACCESS
    |--------------------------------------------------------------------------
    */

    /**
     * Check if a permission is enabled for a Crud Panel. Fail if not.
     *
     * @param  [string] Permission.
     * @param string $permission
     *
     * @return bool|null
     */
    public function hasAccessOrFail()
    {
        if (! empty($this->permissionName) && \Auth::user()->can($this->permissionName)) {

        }else{
            abort(403, '无访问权限');
        }
    }
}