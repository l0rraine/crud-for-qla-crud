<div id="saveActions" class="form-group" style="margin-top:10px;">
    <input type="hidden" name="save_action" value="{{ $crud->saveActions['active']['value'] }}">

    <div class="btn-group" style="margin-left: 20px;">
        <button type="submit" class="btn btn-primary btn-sm">
            <span class="fa fa-save" role="presentation" aria-hidden="true"></span> &nbsp;
            <span data-value="{{ $crud->saveActions['active']['value'] }}">{{ $crud->saveActions['active']['label'] }}</span>
        </button>
        <button data-toggle="dropdown" class="btn btn-primary btn-sm dropdown-toggle" aria-expanded="false">
            <span class="ace-icon fa fa-caret-down icon-only"></span>
        </button>
        <ul class="dropdown-menu">
            @foreach( $crud->saveActions['options'] as $value => $label)
                <li><a href="javascript:void(0);" data-value="{{ $value }}">{{ $label }}</a></li>
            @endforeach
        </ul>
    </div>
    <a class="btn btn-danger btn-sm" href="{{ $crud->getIndexUrl() }}" style="margin-left:5px;"><span
                class="fa fa-ban"></span> &nbsp;取消</a>

</div>