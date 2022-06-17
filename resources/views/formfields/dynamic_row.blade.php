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
@endphp
<input type="hidden" class="form-control" id="{{ $row->field }}"  name="{{ $row->field }}" value="{{ $dataTypeContent->{$row->field} }}"/>
<div class="custom-parameters"  id="{{ $row->field }}_loop_fields">
	<input type="hidden" name="jsonKeyValue" value="{{ $row->field }}"/>
    <div class="loop-wrap">
    	@if(is_array($old_parameters))
            @foreach($old_parameters as $index => $parameter)
            
    	        <div class="form-group loop-parameter-group" row-id="{{ $index }}">
    	            <div class="form-group mt0">
                        <div class="control-label">Title</div>
    	                <input 
    	                	type="text" 
    	                	id="{{ $row->field }}_dynamic_row_title_{{ $index }}" 
    	                	class="form-control fake_title" 
    	                	name="fake_{{ $row->field }}[{{ $index }}][title]" 
    	                	value="{{ $parameter->title }}" 
    	                	/>
    	            </div>
                    <div class="form-group mt0">
                        <div class="control-label">Type</div>
    	                <input 
    	                	type="text" 
    	                	id="{{ $row->field }}_dynamic_row_class_{{ $index }}" 
    	                	class="form-control fake_class" 
    	                	name="fake_{{ $row->field }}[{{ $index }}][class]" 
    	                	value="{{ $parameter->class }}" 
    	                	/>
    	            </div>
    	            <div class="form-group mt0">
                            <div class="control-label">Content</div>
                            <div class="d-flex flex-column dynamic_list bg-info p-3">
                                @foreach($parameter->content as $content_index => $content)
                                <div class="row dynamic_item" data-index="{{ $content_index }}">
                                    <div class="col-sm-10">
                                        <textarea 
                                        id="{{ $row->field }}_dynamic_row_content_{{ $index }}_{{ $content_index }}" 
                                        class="form-control fake_content" 
                                        name="fake_{{ $row->field }}[{{ $index }}][content][{{ $content_index }}]" 
                                        >{{ $content }}</textarea>
                                    </div>
                                    <div class="col-sm-2">
                                        <button class="btn btn-primary btn-sm dynamic_item_remove_button m-1" type="button"><i class="voyager-trash"></i></button>
                                        <button class="btn btn-primary btn-sm dynamic_item_add_button m-1" type="button"><i class="voyager-plus"></i></button>
                                    </div>
                                </div>
                                @endforeach
                            </div>
    	            </div>
    	            <div class="form-group mt0">
    	                <button type="button" class="btn old_btn btn-xs mt0" onclick="multiple_{{ $row->id }}.remove_row(this)"><i class="voyager-trash"></i></button>
    	            </div>
    	        </div>
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
        
        var richTextEditorSettings = {
            menubar: false,
            skin_url: $('meta[name="assets-path"]').attr('content')+'?path=js/skins/voyager',
            min_height: 300,
            resize: 'vertical',
            plugins: 'link, image, code, table, textcolor, lists, paste',
            extended_valid_elements : 'input[id|name|value|type|class|style|required|placeholder|autocomplete|onclick]',
            file_browser_callback: function(field_name, url, type, win) {
                    if(type =='image'){
                    $('#upload_file').trigger('click');
                    }
                },
            toolbar: 'styleselect bold italic underline | forecolor backcolor | alignleft aligncenter alignright | bullist numlist outdent indent | link image table | code | paste',
            convert_urls: false,
            image_caption: true,
            image_title: true,
            paste_as_text: true,
            paste_auto_cleanup_on_paste : true,
            paste_remove_styles: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: true,
            init_instance_callback: function (editor) {
                if (typeof tinymce_init_callback !== "undefined") {
                    tinymce_init_callback(editor);
                }
            },
            setup: function (editor) {
                if (typeof tinymce_setup_callback !== "undefined") {
                    tinymce_setup_callback(editor);
                }

                editor.on('Paste Change input Undo Redo', function () {
                    tinyMCE.triggerSave();
                    multiple_{{ $row->id }}.reload_real_input();
                });
            }
        };
        
        window['last_item_index_'+{{ $row->id }}] = 0;
        
        var multiple_{{ $row->id }} = {
            'add_row': function() {
                let old_id = $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').length;
                let new_id = parseInt(old_id);

                new_row  = '<div class="form-group loop-parameter-group" row-id="' + new_id + '">';

                new_row += `
                <div class="form-group mt0">
                    <div class="control-label">Title</div>
                    <input 
                        type="text" 
                        id="{{ $row->field }}_dynamic_row_title_` + new_id + `" 
                        class="form-control fake_title" 
                        name="fake_{{ $row->field }}[` + new_id + `][title]" 
                        value="" 
                        />
                </div>
                <div class="form-group mt0">
                    <div class="control-label">Type</div>
                    <input 
                        type="text" 
                        id="{{ $row->field }}_dynamic_row_class_` + new_id + `" 
                        class="form-control fake_class" 
                        name="fake_{{ $row->field }}[` + new_id + `][class]" 
                        value="" 
                        />
                </div>
                <div class="form-group mt0">
                    <div class="control-label">Content</div>
                    <div class="d-flex flex-column dynamic_list bg-info p-3">
                        <div class="row dynamic_item" data-index="0">
                            <div class="col-sm-10">
                                <textarea 
                                id="{{ $row->field }}_dynamic_row_content_` + new_id + `_0" 
                                class="form-control fake_content" 
                                name="fake_{{ $row->field }}[` + new_id + `][content][0]" 
                                ></textarea>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-primary btn-sm dynamic_item_remove_button m-1" type="button"><i class="voyager-trash"></i></button>
                                <button class="btn btn-primary btn-sm dynamic_item_add_button m-1" type="button"><i class="voyager-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                `;

                new_row += '    <div class="form-group mt0">';
                new_row += '        <button type="button" class="btn old_btn btn-xs mt0" onclick="multiple_{{ $row->id }}.remove_row(this)"><i class="voyager-trash"></i></button>';
                new_row += '    </div>';
                new_row += '</div>';

                $('#{{ $row->field }}_loop_fields .loop-wrap').append(new_row);
                multiple_{{ $row->id }}.reload_real_input();

                tinymce.init(Object.assign({selector: 'textarea#dynamic_rows_dynamic_row_content_' + new_id + '_0'}, richTextEditorSettings));
            },
            'remove_row': function(el){
                el.parentNode.parentNode.remove();
                multiple_{{ $row->id }}.reload_real_input();
            },
            'reload_real_input': function(){
                let multiple_data = [];

                $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').each(function(){
                    
                    let title = '';
                    let _class = '';
                    let content = [];
                    $(this).find('input, textarea').each(function() {
                        if ($(this).hasClass('fake_title')) title = $(this).val();
                        if ($(this).hasClass('fake_class')) _class = $(this).val();
                        if ($(this).hasClass('fake_content')) content.push($(this).val());
                        
                    });
                    var real_inputs = {
                        'title': title,
                        'class': _class,
                        'content': content
                    };
                    //console.log(real_inputs);
                    multiple_data.push(real_inputs);
                });

                $('#{{ $row->field }}').val(JSON.stringify(multiple_data));
            },
            'update_fake_inputs': function(){
                setTimeout(() => {
                    let multiple_data = $('#{{ $row->field }}').val() ? JSON.parse($('#{{ $row->field }}').val()) : {};
                    
                    if(multiple_data){
                        for (let index = 0; index < multiple_data.length; index++) {
                            $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').eq(index).find('.fake_title').val(multiple_data[index].title);
                            $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').eq(index).find('.fake_class').val(multiple_data[index].class);
                            $('#{{ $row->field }}_loop_fields .loop-wrap .loop-parameter-group').eq(index).find('.fake_content').each(function(i, item) {
                                $(this).val(multiple_data[index].content[i]); 
                                tinymce.get($(this).attr('id')).setContent(multiple_data[index].content[i]);
                            });
                        }
                    }
                    
                    multiple_{{ $row->id }}.reload_real_input();
                }, 200);
                multiple_{{ $row->id }}.reload_real_input();
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

        //Initialize existing editors
        let existing_textarea_ids = [];
        $('.dynamic_item').find('textarea').each(function() {
            existing_textarea_ids.push($(this).attr('id'));
        });
        existing_textarea_ids.forEach(function (item, index) {
            tinymce.init(Object.assign({selector: '#'+item}, richTextEditorSettings)); 
        });

        //Add and remove editors
        function addDynamicItem(dynamic_list, row_id, content) {
            let new_row = dynamic_list.find('.dynamic_item').last().clone();
            
            let parent_index = $(this).closest('.loop-parameter-group').attr('row-id');
            let item_index = window['last_item_index_'+row_id]+1;
            let new_content_id = 'dynamic_rows_dynamic_row_content_'+parent_index+'_'+item_index;
            let textarea_name = 'fake_dynamic_rows['+parent_index+'][content]['+item_index+']';

            new_row.find('textarea').attr('id', new_content_id);
            new_row.attr('data-index', item_index);
            new_row.find('textarea').val(content).css('display', 'block');
            new_row.find('textarea').attr('name', textarea_name);
            new_row.find('.mce-tinymce').remove();
            window['last_item_index_'+row_id]++;

            dynamic_list.append(new_row);

            tinymce.init(Object.assign({selector: '#'+new_content_id}, richTextEditorSettings)); 
        }
        $(document).on('click', '.dynamic_item_add_button', function() {
            let dynamic_list = $(this).closest('.dynamic_list');
            addDynamicItem(dynamic_list, {{ $row->id }}, '');
        });
        $(document).on('click', '.dynamic_item_remove_button', function() {
            $(this).closest('.dynamic_item').remove();
        });

    </script>
@append
