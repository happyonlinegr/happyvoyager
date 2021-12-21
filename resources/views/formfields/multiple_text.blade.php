@php
    if(json_decode($dataTypeContent->{$row->field}) != '""') {
        if($dataTypeContent->{$row->field} == '"null"') {   
            $old_parameters = NULL;
        } else {
            $old_parameters = json_decode($dataTypeContent->{$row->field});
        }
    } else {
        $old_parameters = NULL;
	}
    $end_id = 0;
@endphp
<input type="hidden" class="form-control" id="{{ $row->field }}"  name="{{ $row->field }}" value="{{ $dataTypeContent->{$row->field} }}"/>
<div class="custom-parameters"  id="{{ $row->field }}_loop_fields">
	<input type="hidden" name="jsonKeyValue" value="{{ $row->field }}"/>
    <div class="loop-wrap">
    	@if(is_array($old_parameters))
            @foreach($old_parameters as $parameter)
    	        <div class="form-group loop-parameter-group" row-id="{{ $loop->index }}">
    	        	<label></label>
    	            <div class="form-group mt0">
    	                <input 
    	                	type="text" 
    	                	id="{{ $row->field }}_multi_text_title_{{ $loop->index }}" 
    	                	class="form-control fake_title" 
    	                	name="fake_{{ $row->field }}[{{$loop->index}}][title]" 
    	                	value="{{ $parameter->title }}" 
    	                	/>
    	            </div>
    	            <div class="form-group mt0">
    	            	<textarea 
    	                	id="{{ $row->field }}_multi_text_content_{{ $loop->index }}" 
    	            		class="form-control fake_content" 
    	            		name="fake_{{ $row->field }}[{{$loop->index}}][content]" 
    	            		>{{ $parameter->content }}</textarea>
    	            </div>
    	            <div class="form-group mt0">
    	                <button type="button" class="btn old_btn btn-xs mt0" onclick="multiple_{{ $row->id }}.remove_row(this)"><i class="voyager-trash"></i></button>
    	            </div>
    	        </div>
    	        @php 
    	            $end_id = $loop->index + 1;
    	        @endphp
    	    @endforeach
    	@endif
    </div>
    <div class="form-group mt0">
        <button type="button" onclick="multiple_{{ $row->id }}.add_row()" class="btn btn-add btn-success btn-xs mt0">
            <i class="voyager-plus"></i>
        </button>
    </div>
</div>
<style type="text/css">
	.custom-parameters {
		counter-reset: my-sec-counter;
	}
	.custom-parameters label::before {
		counter-increment: my-sec-counter;
		content: "Row " counter(my-sec-counter) ". ";
	}
    .mt0 {
        margin-top:0px;
    }
    .loop-parameter-group {
        border-bottom: dashed 1px #efefef;
    }
</style>

@section('javascript_multiple')
    <script>
        var multiple_{{ $row->id }} = {
            'add_row': function(){
                let old_id = $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').length;
                let new_id = parseInt(old_id);

                new_row  = '<div class="form-group loop-parameter-group" row-id="' + new_id + '">';
                new_row += '    <label></label>';
                new_row += '    <div class="form-group mt0">';
                new_row += '        <input type="text" id="{{ $row->field }}_multi_text_title_' + new_id + '" class="form-control fake_title" name="fake_{{ $row->field }}[' + new_id + '][title]" />';
                new_row += '    </div>';
                new_row += '    <div class="form-group mt0">';
                new_row += '        <textarea id="{{ $row->field }}_multi_text_content_' + new_id + '" class="form-control fake_content" name="fake_{{ $row->field }}[' + new_id + '][content]" ></textarea>';
                new_row += '    </div>';
                new_row += '    <div class="form-group mt0">';
                new_row += '        <button type="button" class="btn old_btn btn-xs mt0" onclick="multiple_{{ $row->id }}.remove_row(this)"><i class="voyager-trash"></i></button>';
                new_row += '    </div>';
                new_row += '</div>';

                $('#{{ $row->field }}_loop_fields .loop-wrap').append(new_row);
                multiple_{{ $row->id }}.reload_real_input();
            },
            'remove_row': function(el){
                el.parentNode.parentNode.remove();
                multiple_{{ $row->id }}.reload_real_input();
            },
            'reload_real_input': function(){
                let multiple_data = [];

                $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').each(function(){
                    multiple_data.push({
                        "title": $(this).find('.fake_title').val(),
                        "content": $(this).find('.fake_content').val(),
                    });
                });

                $('#{{ $row->field }}').val(JSON.stringify(multiple_data));
            },
            'update_fake_inputs': function(){
                $('#{{ $row->field }}_loop_fields .loop-wrap').empty();
                setTimeout(() => {
                    let multiple_data = JSON.parse($('#{{ $row->field }}').val());
                    
                    for (let index = 0; index < multiple_data.length; index++) {
                        $('#{{ $row->field }}_loop_fields .btn-add').click();
                        $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').eq(index).find('.fake_title').val(multiple_data[index].title);
                        $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').eq(index).find('.fake_content').val(multiple_data[index].content);
                    }
                    
                    multiple_{{ $row->id }}.reload_real_input();
                }, 200);
                
            }
        };

        $(document).on("change keyup blur", "#{{ $row->field }}_loop_fields input", function () {
            multiple_{{ $row->id }}.reload_real_input();
        });

        $(document).on("change keyup blur", "#{{ $row->field }}_loop_fields textarea", function () {
            multiple_{{ $row->id }}.reload_real_input();
        });
        
        $('.language-selector input').on('change', function(){
            multiple_{{ $row->id }}.update_fake_inputs();
        });
    </script>
@endsection
