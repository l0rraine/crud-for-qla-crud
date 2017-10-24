@if (count($data['children']) > 0)
    <option value="{!! $data['id'] !!}"
            class="optionGroup {!!$data['class_layer'] >0 ?'optionChild' . $data['class_layer'] :''!!}"
            {!!  isset($pinyin_search)?'data-pinyin="'.$data['pinyin'].'"':'' !!}
    >
        {!! $data[$title_attr] !!}
    </option>
    @foreach($data['children'] as $data)
        @include('crud::partials.select_parent_selectable', $data)
    @endforeach

@else
    <option value="{!! $data['id'] !!}" class="optionChild{!!$data['class_layer']!!}"
    @if(isset($multiple))
    {!!  (isset($chosen_id) &&  in_array($data['id'], $chosen_id)) ?'selected':'' !!}
    @else
    {!! $data['id']==old($select_name) || (isset($chosen_id) && $data['id']==$chosen_id) ?'selected':'' !!}
    @endif
    {!!isset($pinyin_search)?'data-pinyin="'.$data['pinyin'].'"':''!!}
    >
        {!! $data[$title_attr] !!}</option>
@endif


