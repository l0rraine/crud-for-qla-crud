<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/26
 * Time: 9:59
 */

namespace Qla\Crud\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Qla\Crud\Controllers\Features\SaveActions;
use Qla\Crud\CrudPanel;


class CrudController extends BaseController
{
    use SaveActions;

    /**
     * @var \Qla\Crud\CrudPanel
     */
    public $crud;

    /*
     * @var array
     */
    public $data;

    /*
     * crud操作后要处理的数据
     * @var array | mixed
     */
    public $doAfterCrudData;


    /*
     * @var \Illuminate\Validation\Validator
     */
    public $validator;

    public function __construct()
    {
        if (! $this->crud) {
            $this->crud = app()->make(CrudPanel::class);

            // call the setup function inside this closure to also have the request there
            // this way, developers can use things stored in session (auth variables, etc)
            $this->middleware(function ($request, $next) {
                $this->request = $request;
                $this->crud->request = $request;
                $this->setup();

                return $next($request);
            });

            $this->crud->saveActions = $this->getSaveAction();
        }
    }

    public function setup()
    {
    }

    public function getIndex()
    {
        $this->data['crud'] = $this->crud;

        return view('crud::list', $this->data);
    }

    public function getIndexJson()
    {
        return json_encode($this->data);
    }

    public function getAdd()
    {
        $this->data['crud'] = $this->crud;

        return view('crud::create', $this->data);
    }

    public function storeCrud(Request $request = null)
    {
        if (isset($this->validator)) {
            if ($this->validator->fails()) {
                return redirect()->to($this->getRedirectUrl())->withInput()->withErrors($this->validator)->withInput();
            }
        }

        $model = $this->crud->model->newInstance();
        $saved = $model->fill($this->data)->save();
        if ($saved) {
            $id = $model->id;
            $model->doAfterCU($this->doAfterCrudData);

            return $this->performSaveAction($id);
        } else {
            return redirect()->to($this->getRedirectUrl())->withInput()->withErrors(['0' => '新建'.$this->crud->title.'时出现错误，请联系管理员'], $this->errorbag());
        }
    }

    public function getEdit($id)
    {
        $this->data['crud'] = $this->crud;

        return view('crud::update', $this->data);
    }


    public function updateCrud(Request $request = null)
    {
        if (isset($this->validator)) {
            if ($this->validator->fails()) {
                return redirect()->to($this->getRedirectUrl())->withInput()->withErrors($this->validator)->withInput();
            }
        }

        $model = $this->crud->model->find($this->data['id']);
        $saved = $model->update($this->data);
        if ($saved) {
            $id = $model->id;
            $model->doAfterCU($this->doAfterCrudData);

            return $this->performSaveAction($id);
        } else {
            return redirect()->to($this->getRedirectUrl())->withInput()->withErrors(['0' => '修改'.$this->crud->title.'信息时出现错误，请联系管理员'], $this->errorbag());
        }
    }

    public function getCustomEdit($id)
    {
        $this->data['crud'] = $this->crud;

        return view('crud::custom-update', $this->data);
    }

    public function postCustomEdit($id = null){

        return $this->performSaveAction();
    }

    public function del($selectionJson)
    {
        $data = json_decode($selectionJson);
        $success = true;
        $successCount = 0;
        $failureCount = 0;
        $failId = [];
        foreach ($data as $id) {

            try {
                if ($this->crud->model->find($id)->delete()) {
                    $this->crud->model->doAfterD($id);
                    $successCount += 1;
                } else {
                    $failureCount += 1;
                    $failId[] = $id;
                    $success = false;
                }
            } catch (\Throwable $e) {
                $failureCount += 1;
                $failId[] = $id;
                $success = false;
            }
        }
        if ($failureCount == 0) {
            $message = '删除'.$successCount.'条记录成功。';
        } else {
            if ($successCount == 0) {
                $message = '删除'.count($data).'条记录失败，请联系管理员。';
            } else {
                $message = '删除'.$successCount.'条记录成功，有'.$failureCount.'条记录失败，请联系管理员。';
            }
        }

        return json_encode(['success' => $success, 'message' => $message]);
    }
}