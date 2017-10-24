<?php
/**
 *
 * 自定义的select组件
 * 必要参数包括：
 * select_name                  select的name属性
 * datas                        数据
 * 编辑时需提供：
 * chosen_id                    当前选中的id
 *
 * 可选参数有：
 * select_id                    select的id属性
 * cssClass                     select的css属性
 * placeholder                  初始时显示的占位符，默认为 '请选择...'
 * select_height                select的高度
 * select_width                 select的宽度，默认为 300
 * dropdown_height              select的下拉列表的高度
 * select2                      是否使用select2，默认为true
 * parent_selectable            是否有父子关系，注意：父子关系的数据库有要求 *
 * multiple                     是否多选
 *
 * search                       是否可以搜索
 * pinyin_search                是否使用拼音搜索
 *
 * title_attr                   option的标题所使用的字段，默认为 'title'
 *
 */
$select_width = isset($select_width) ? $select_width : 300;
$placeholder = isset($placeholder) ? $placeholder : '请选择...';
$select2 = isset($select2) ? $select2 : true;

$select_width = isset($select_width) ? $select_width : 300;

$select_id = isset($select_id) ? $select_id : random_int(1000000,9990999);

if (!isset($search) && count($datas)>7) $search = true;
if (!isset($search) && count($datas)<=7) $search = false;

if(isset($pinyin_search)) $search = true;

$title_attr = isset($title_attr) ? $title_attr : 'title';

?>


<select id="{!!$select_id!!}" name="{!!  isset($multiple)?$select_name . '[]':$select_name !!}"
        {!!  isset($multiple)?'multiple="multiple"':'' !!}
>
    @if (!$select2)
        <option value="-1">{!! $placeholder !!}</option>
    @else
        <option{!!isset($pinyin_search)?' data-pinyin=""':''!!}></option>
    @endif
    @if(isset($parent_selectable))
        @foreach ($datas as $data)
            @include('crud::partials.select_parent_selectable', $data)
        @endforeach
    @else
        @foreach ($datas as $data)
            @include('crud::partials.select_normal', $data)
        @endforeach
    @endif

</select>

@if ($select2)
    @push('css')
    <style type="text/css">
        @if(isset($select_height))
        #select2-{!!$select_id!!}-container {
            height: {!! $select_height !!}px;
            line-height: {!! $select_height !!}px;
        }
        @endif

        @if(isset($dropdown_height))
        #select2-{!!$select_id!!}-results {
            max-height: {!!($search ? $dropdown_height -50:$dropdown_height)!!}px !important;
        }

        @endif
    </style>
    @endpush

    @push('js')


    <script type="text/javascript">
        @if(isset($pinyin_search) && $search)
        function matchStart(term, text, opt) {
            return text.toUpperCase().indexOf(term.toUpperCase()) >= 0
                || $(opt.element).data('pinyin').toUpperCase().indexOf(term.toUpperCase()) >= 0;
        }
        @endif

        $(document).ready(function () {
            var doc = top.document.body;
            $.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
                $("#{!!$select_id!!}").select2({
                    width: '{!!$select_width!!}'
                    ,placeholder: "{!! $placeholder!!}"
//                    ,allowClear: true

                    @if(isset($dropdown_height))
                    ,dropdownCss: {height: "{!!$dropdown_height!!}px"}
                    @endif

                    @if(!$search)
                    ,minimumResultsForSearch: Infinity
                    @endif


                    @if(isset($pinyin_search) && $search)
                    ,matcher: oldMatcher(matchStart)
                    @endif
                    ,templateResult: function (data, container) {
                        if (data.element) {
                            $(container).addClass($(data.element).attr("class"));
                        }
                        return data.text;
                    }
                    ,containerCss: {
                        "font-size": ""
                        @if(isset($select_height))
                        ,"height": "{!! $select_height !!}px"
                        ,"line-height": "{!! $select_height !!}px"
                        @endif
                        @if($errors->has($select_name))
                        , "border-color": "#a94442"
                        , "border-weight": "1px"
                        , "border-style": "solid"
                        @endif
                    }
                })
            });

            $("#{!!$select_id!!}").val({!! $chosen_id or old($select_name) !!});

        });
    </script>
    @endpush
@endif

@push('css')
<style type="text/css">
    .optionGroup {
        font-weight: bold !important;
        font-style: italic !important;
    }

    .optionChild1 {
        padding-left: 15px !important;
    }

    .optionChild2 {
        padding-left: 30px !important;
    }

    .optionChild3 {
        padding-left: 45px !important;
    }

    .optionChild4 {
        padding-left: 60px !important;
    }

    .optionChild5 {
        padding-left: 75px !important;
    }
</style>
@endpush
