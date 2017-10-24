<div class="input-group{{ $errors->has($name) ? ' has-error' : '' }}">
<span class="input-icon input-icon-right">
        {{ Form::text($name, $value, array_merge(['class' => 'form-control'], $attributes)) }}
    @if(isset($addon))
        <label>{{ $addon }}</label>
    @endif
    <span class="red"
          style="border: 0;background-color: white;width: 10%;text-align: center;line-height: 34px;margin-left: 10px;">*</span>
    @if(isset($unit))
        <i class="ace-icon" style="right:25px;">{{ $unit }}</i>
    @endif
</span>
</div>