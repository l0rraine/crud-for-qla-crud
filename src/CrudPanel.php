<?php

namespace Qla\Crud;

use Qla\Crud\PanelTraits\Access;

class CrudPanel
{
    use Access;

    /*
     * 路由起始名，如，Role.edit 的$route='Role'
     * @var string
     */
    public $route;

    /*
     * 路由的后缀，形成如Asset.own.index的路由
     * @var string
     */
    public $routeSuffix = '';

    public $indexUrl = '';

    /*
     * 标题，用于form_header中的显示
     * @var string
     */
    public $title;

    /*
     * 权限的起始名
     * @var string
     */
    public $permissionName;

    /*
     * create和update中的保存按钮默认的动作
     * @var string
     */
    public $saveActions;

    /*
     * 页面内content_header里的描述内容
     * @var string
     */
    public $description;

    /*
     * index页面table的记录是否带有parent_id和list_classes
     * @var bool
     */
    public $indexRecursive = false;

    /*
     * 自行规定view的前缀，实际路径为 manager.pages.$viewName.['create','update','list']
     * @var string
     */
    public $viewName = '';

    /*
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model = "";

    public $request;

    /**
     * Set the route for this CRUD.
     * Ex: manager/article.
     *
     * @param [string] Route name.
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * Set the route for this CRUD using the route name.
     * Ex: manager.article.
     *
     * @param [string] Route name.
     * @param [array] Parameters.
     */
    public function setRouteName($route, $parameters = [])
    {
        $complete_route = $route.'.index';

        if (! \Route::has($complete_route)) {
            throw new \Exception('There are no routes for this route name.', 404);
        }

        $this->route = route($complete_route, $parameters);
    }

    public function getFullRoute()
    {
        if ($this->routeSuffix != '') {
            return $this->route.'.'.$this->routeSuffix;
        } else {
            return $this->route;
        }
    }

    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Get the corresponding Eloquent Model for the CrudController, as defined with the setModel() function;.
     *
     * @return [Eloquent Collection]
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * This function binds the CRUD to its corresponding Model (which extends Eloquent).
     * All Create-Read-Update-Delete operations are done using that Eloquent Collection.
     *
     * @param [string] Full model namespace. Ex: App\Models\Article
     */
    public function setModel($model_namespace)
    {
        if (! class_exists($model_namespace)) {
            $model_namespace = "\\App\\Models\\".$model_namespace;
            if (! class_exists($model_namespace)) {
                throw new \Exception('This model does not exist.', 404);
            }
        }

        $this->model = new $model_namespace();
    }

    public function getIndexUrl()
    {
        return empty($this->indexUrl) ? route($this->getFullRoute().'.index') : $this->indexUrl;
    }

    /**
     * @param mixed $permissionName
     */
    public function setPermissionName($permissionName)
    {
        $this->permissionName = $permissionName;
        $this->hasAccessOrFail($permissionName);
    }
}
