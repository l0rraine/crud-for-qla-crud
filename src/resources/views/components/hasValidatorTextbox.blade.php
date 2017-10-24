<div class="input-group{{ $errors->has($name) ? ' has-error' : '' }}">
    @if(isset($unit))<span class="input-icon input-icon-right">@endif
        {{ Form::text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
        @if(isset($addon))
            <label>{{ $addon }}</label>
        @endif
        @if(isset($unit))
            <i class="ace-icon">{{ $unit }}</i>
        @endif
        @if(isset($unit))</span>@endif
</div>