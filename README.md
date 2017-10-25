# Qla\Crud
Qla crud里的增删查改基础模块，需要 laravel 5.x。

## 安装

```
composer require qla/crud
php artisan vendor:publish
select the Provider and tag with qla
```

## 使用

### Model

项目中的Model应该`extends`自`BaseClassifiedModel`，并`use` `BaseModelTrait` 
例子：

```php
class Department extends BaseClassifiedModel
{
    use BaseModelTrait;
    
    public static function rules($id = 0, $merge = [])
        {
            return array_merge([
                'title' => 'required',
                'parent_id' => 'required',
            ],$merge);
        }
    
        public static function messages($id = 0, $merge = [])
        {
            return array_merge([
                'title.required' => '必须填写标题',
                'parent_id.required' => '必须选择父单位！',
            ],$merge);
        }
}

```

其中的rules和messages用于在add和edit时进行校验检查。

### Controller

CrudController中，已经实现了以下方法

- getIndex
- getIndexJson
- getAdd
- storeCrud
- getEdit
- updateCrud
- del

### Setup方法
对crud模块进行相关配置
```php
        $this->crud->route = '';
        $this->crud->permissionName = '';
        $this->crud->indexRecursive = true;
        $this->crud->title = '';
        $this->crud->viewName='';
        $this->crud->setModel('');
```
具体注释可以看`src/CrudPanel.php`

### getIndex & getIndexJson方法
- 将数据放在`$this->data`数组中
- 调用`parent::相应方法`即可

### getAdd & getEdit & storeCrud & updateCrud方法
- 将数据放在`$this->data`数组中
- 调用校验 `$this->validator = Validator::make($this->data, Department::rules(), Department::messages())`
- 调用`parent::相应方法`

### del 方法
- 调用`parent::相应方法`

## View

### Index
使用[bootstrap table](http://bootstrap-table.wenzhixin.net.cn/)进行列表显示，具体应用请去官方网站查找。

#### Html
- 一般来说这样就可以了：

```html
<table id="table" style="padding-top: 2em;"
   data-toggle="table"
   data-show-refresh="true"
   data-pagination="false"
   data-buttons-align="left"
   data-buttons-class="primary"
   data-toolbar="#myToolbar"
   data-unique-id="id"
   data-search="true"
   data-escape="true"
   data-url="{{ route($crud->route . '.indexJson') }}"

   data-id-field="id"
   {{--data-editable-mode="inline"--}}
   data-editable-emptytext="空"
>
    <thead>
        <tr>
            <th data-field="state" data-checkbox="true"></th>
            <th data-formatter="rownumberFormatter" data-align="center">序号</th>
            <th data-field="……">单位名称</th>
            …………
            …………
            </th>
            <th data-formatter="actionFormatter">动作</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
```

- `data-field="state"`用来选取记录
- `rownumberFormatter`已写好
- 额外的按钮要写在`@push('stick_buttons')`中

#### Javascript
- 请写在`@push('js')`中
- `actionFormatter`中写动作按钮
- 请在`bindLink`方法中写动作按钮的具体动作

### Add

定义字段即可，如下：
```html
<div class="form-group">
    {!! Form::label('parent_id', '父单位', ['class'=>'col-sm-2 control-label']) !!}
    <div class="col-sm-8">
        @include('crud::partials.select_form', ['select_name'=>'parent_id','datas'=>$deps, 'pinyin_search'=>1, 'parent_selectable'=>1 ,'select_width'=>300,'dropdown_height'=>300])
    </div>
</div>
```

### Edit

同上
