<input type="number"
       class="form-control  @error($row->field) is-invalid @enderror"
       min="1970"
       max="2050"
       step="1"
       name="{{ $row->field }}"
       data-name="{{ $row->display_name }}"
       @if($row->required == 1) required @endif
       placeholder="{{ isset($options->placeholder)? old($row->field, $options->placeholder): $row->display_name }}"
       value="@if(isset($dataTypeContent->{$row->field})){{ old($row->field, $dataTypeContent->{$row->field}) }}@else{{old($row->field)}}@endif">