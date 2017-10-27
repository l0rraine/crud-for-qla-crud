{{-- index的路由为 $crud->getIndexUrl()--}}
@extends('crud::base.empty')
@section('content_header')
    @include('crud::base.content_header',['page'=>
    [
            'name' => isset($crud->description)? $crud->description : '编辑' . $crud->title,
            'describe' => '',
            'breadcrumb' => [
                [
                    'title' => $crud->title,
                    'url' => $crud->getIndexUrl()
                ],
                [
                    'title' => '编辑',
                    'url' => ''
                ]
            ]
        ]])
@endsection

@section('content')
    <div class="col-md-8 col-md-offset-2">
        <a href="{{$crud->getIndexUrl() }}"><i class="fa fa-angle-double-left"></i>
            返回{{$crud->title}}列表</a>
        <br><br>
        <div class="widget-box">
            <div class="widget-header">
                <h5 class="widget-title smaller">{{ '编辑' . $crud->title . '信息' }}</h5>
            </div>

            <div class="widget-body">
                <div class="widget-main padding-6" style="margin-top: 20px;">
                    {!! Form::model($model,['url' => route($crud->route . '.edit.post'),'class'=>'form-horizontal']) !!}
                    <fieldset>
                        @include($crud->viewName . '.edit')
                    </fieldset>
                    <div class="col-sm-offset-1">
                        @include('crud::buttons.form_save_buttons')
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script type="text/javascript">
    $(document).ready(function () {
        var saveActions = $('#saveActions'),
            crudForm = saveActions.parents('form'),
            saveActionField = $('[name="save_action"]');

        saveActions.on('click', '.dropdown-menu a', function () {
            var saveAction = $(this).data('value');
            saveActionField.val(saveAction);
            crudForm.submit();
        });

                @if(isset($errors) && count($errors))
        var msg = '';
        @foreach($errors->all() as $err)
            msg = msg + '{!! $err !!}' + '<br>';
        @endforeach

        $.gritter.add({
            title: '错误！',
            text: msg,
            time: 1500,
            class_name: 'gritter-error'
        });
        @endif

resizeFrame();

    });
</script>
@endpush
@push('css')
<link href="{{ asset('vendor/qla/js/select2-4.0.3/css/select2.css') }}" rel="stylesheet"/>
<link rel="stylesheet" href="{{ asset('vendor/qla/js/jquery-gritter/jquery.gritter.min.css') }}"/>
@endpush

@push('js')
<script>window.Select2 || document.write('<script src="{{ asset('vendor/qla/js/select2-4.0.3/js/select2.full.min.js') }}"><\/script><script src="{{ asset('vendor/qla/js/select2-4.0.3/js/i18n/zh-CN.js') }}"><\/script>')</script>
<script src="{{ asset('vendor/qla/js/jquery-gritter/jquery.gritter.min.js') }}"></script>
@endpush