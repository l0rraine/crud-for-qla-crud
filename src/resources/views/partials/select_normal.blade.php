@if (isset($data['children']) && count($data['children']) > 0)
    <optgroup label="{!! $data[$title_attr] !!}"
            {!!isset($pinyin_search)?'data-pinyin="'.$data['pinyin'].'"':''!!}
    >
        @foreach($data['children'] as $data)
            @include('crud::partials.select_normal', $data)
        @endforeach
    </optgroup>
@else
    <option value="{!! $data['id'] !!}"
            {!!isset($pinyin_search)?' data-pinyin="'.$data['pinyin'].'"':''!!}
    >
        {!! $data[$title_attr] !!}
    </option>
@endif


