
<div class="breadcrumbs ace-save-state" style="margin-left:20px;margin-right:20px;margin-top:-20px;">

    <h2 style="padding-top:12px;padding-left:20px;">{{ $page['name'] }}<small>{{ $page['describe'] }}</small></h2>


    <ul class="breadcrumb" style="float:right;margin-top:-35px;">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
        </li>
        @foreach($page['breadcrumb'] as $k=>$v)
            @if($v['url']!='')
                <li>
                    <a href="{{ $v['url'] }}">{{ $v['title'] }}</a>
                </li>
            @else
                <li class="active">
                    {{ $v['title'] }}
                </li>
            @endif
        @endforeach
    </ul><!-- /.breadcrumb -->
</div>