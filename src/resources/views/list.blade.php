{{-- 增加和删除的路由为 $crud->route . ['.add','.del'] --}}
{{-- index的路由为 $crud->getFullRoute() . '.index'--}}
{{-- index子页面的javascript中必须有bindLink()方法 --}}

@extends('crud::base.empty')
@section('content_header')
    @include('crud::base.content_header',['page'=>
    [
            'name' => $crud->title,
            'describe' => isset($crud->description)? $crud->description:  '查看所有' . $crud->title,
            'breadcrumb' => [
                [
                    'title' => $crud->title,
                    'url' => $crud->getIndexUrl()
                ],
                [
                    'title' => '列表',
                    'url' => ''
                ]
            ]
        ]])
@endsection
@push('css')
<style type="text/css">
    .fixed-table-toolbar .btn {
        margin-top: 11px !important;
        height: 44px;
    }

    td {
        vertical-align: middle !important;
    }

    td .btn {
        margin-right: 5px;
    }
</style>
@endpush
@section('content')
    <div id="tableContainer">
        @include($crud->viewName . '.index')
    </div>
    <div id="myToolbar" class="btn-toolbar" role="toolbar">

        <div class="btn-group" style="margin-right: 5px;">
            {{--@permission('create.' . $crud->permissionName)--}}
            @if(Route::has($crud->route . '.add'))
                <button name="addBtn" class="btn btn-danger" style="margin-top: 10px;" data-url="{{ route($crud->route . '.add') }}">
                    <span class="fa fa-plus" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span>增加</span>
                </button>
            @endif
            {{--@endpermission--}}
            @stack('stick_buttons')
        </div>


        <div class="btn-group btn-corner hidden check-to-toggle" style="margin-left: 2px;margin-right: 5px;">
            {{--@permission('create.' . $crud->permissionName)--}}
            @if(Route::has($crud->route . '.del'))
                <button name="delBtn" class="btn btn-purple" style="margin-top: 10px;" data-url="{{ route($crud->route . '.del',':id') }}">
                    <span class="fa fa-trash" role="presentation" aria-hidden="true"></span> &nbsp;
                    <span>删除{{ $crud->title }}</span>
                </button>
            @endif
            {{--@endpermission--}}
            @stack('select_show_buttons')
        </div>

    </div>
@endsection
@push('js')
<script type="text/javascript">
    var table = $('#table');
    $(document).ready(function () {
        $('button[name="addBtn"]').click(function () {
            window.location.href = $(this).data('url');
        });





        @if(Route::has($crud->route . '.del'))
        $('button[name=delBtn]').click(function () {
            var that = $(this);
            var selections = table.bootstrapTable('getSelections').map(function(a) {return a.id;});;
            $.ajax({
                url: '{{ route($crud->route . '.del',['id'=>':id']) }}'.replace(':id', JSON.stringify(selections)),
                method: 'POST',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function (d) {
                    var data = $.parseJSON(d);
                    if (data.success) {
                        $.gritter.add({
                            title: '成功',
                            text: data.message,
                            class_name: 'gritter-success',
                            time: 1000,
                            fade_out_speed: 2000
                        });
                        //
                    } else {
                        $.gritter.add({
                            title: '失败',
                            text: data.message,
                            time: 3000,
                            class_name: 'gritter-error'

                        });
                    }
                    table.bootstrapTable('refresh', null);
                    table.bootstrapTable('uncheckAll');

                }

            });
        });
                @endif



        var togglableItem = $('.check-to-toggle');

        function showContentAfterSelect() {
            if ($('button', togglableItem).size() > 0)
                togglableItem.removeClass('hidden');
        }

        function hideContent() {
            togglableItem.addClass('hidden');
        }


        table.on('check-all.bs.table', function (row) {
            showContentAfterSelect();
        });


        table.on('uncheck-all.bs.table', function (row) {
            hideContent();
        });

        @if($crud->indexRecursive == true)
        function onCheck(row, ele) {
            table.off('check.bs.table');
            var data = table.bootstrapTable('getData');
            var arr = [];
            for (var i = 0; i < data.length; i++) {
                if (data[i].class_list.indexOf(',' + ele.id + ',') !== -1 && data[i].class_layer > ele.class_layer) {
                    arr.push(data[i].id);
                }
            }
            table.bootstrapTable('checkBy', {field: 'id', values: arr});

            showContentAfterSelect();

            table.on('check.bs.table', function (row, ele) {
                onCheck(row, ele);
            });
        }

        function onUnCheck(row, ele) {
            table.off('uncheck.bs.table');
            var data = table.bootstrapTable('getData');
            var arr = [];
            for (var i = 0; i < data.length; i++) {
//                TODO: class_list动态？
                if (data[i].class_list.indexOf(',' + ele.id + ',') !== -1 && data[i].class_layer > ele.class_layer) {
                    arr.push(data[i].id);
                }
            }
            table.bootstrapTable('uncheckBy', {field: 'id', values: arr});

            if (table.bootstrapTable('getSelections', null).length === 0)
                hideContent();
            table.on('uncheck.bs.table', function (row, ele) {
                onUnCheck(row, ele);
            });
        }

        table.on('check.bs.table', function (row, ele) {
            onCheck(row, ele);
        });


        table.on('uncheck.bs.table', function (row, ele) {
            onUnCheck(row, ele);
        });
        @else
         table.on('check.bs.table', function (row, ele) {
            showContentAfterSelect();
        });

        table.on('uncheck.bs.table', function (row, ele) {
            if (table.bootstrapTable('getSelections', null).length === 0)
                hideContent();
        });
        @endif


        table.on('post-body.bs.table', function (data) {
            table.show();
            bindLink();
            resizeFrame();
        });

    });
</script>
@endpush
@push('css')
<style>
    #table {
        display:none;
    }
    .fixed-table-toolbar .btn {
        margin-top: 11px !important;
        height: 44px;
    }

    .folder-line {
        display: inline-block;
        margin-right: 2px;
        width: 20px;
        height: 20px;
        background: url({{ asset('vendor/qla/img/skin_icons.png') }}) -80px -196px no-repeat;
        vertical-align: middle;
        text-indent: -999em;
        *text-indent: 0;
    }

    .folder-open {
        display: inline-block;
        margin-right: 2px;
        width: 20px;
        height: 20px;
        background: url({{ asset('vendor/qla/img/skin_icons.png') }}) -40px -196px no-repeat;
        vertical-align: middle;
        text-indent: -999em;
        *text-indent: 0;
    }
</style>
@endpush

@push('css')
<link rel="stylesheet" href="{{ asset('vendor/qla/js/bootstrap-table-1.11.1/bootstrap-table.css') }}"/>
<link rel="stylesheet" href="{{ asset('vendor/qla/js/jquery-gritter/jquery.gritter.min.css') }}"/>
@endpush

@push('js')
<script src="{{ asset('vendor/qla/js/jquery-gritter/jquery.gritter.min.js') }}"></script>
@endpush
@push('pre_js')
<script src="{{ asset('vendor/qla/js/bootstrap-table-1.11.1/bootstrap-table.js') }}"></script>
<script src="{{ asset('vendor/qla/js/bootstrap-table-1.11.1/locale/bootstrap-table-zh-CN.min.js') }}"></script>
@endpush
