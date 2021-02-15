@extends('voyager::master')

@section('page_title', __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular)

@section('css')
    <style>
        .voyager .panel.panel-danger,
        .voyager .panel.panel-success 
        .voyager .panel.fresh-color.panel-danger,
        .voyager .panel.fresh-color.panel-success {
            border: 0;
        }
        .side-body [class*=" voyager-"]:before, 
        .side-body [class^=voyager-]:before, 
        .side-body [data-icon]:before {
            display: inline-block;
            float: left;
            margin-right: 5px;
        }
        .panel .mce-panel {
            border-left-color: #fff;
            border-right-color: #fff;
        }
        .panel .mce-edit-area,
        .panel .mce-edit-area iframe,
        .panel .mce-edit-area iframe html {
            min-height: 350px;
        }
        .mce-content-body {
            color: #555;
            font-size: 14px;
        }
        .panel.is-fullscreen .mce-statusbar {
            position: absolute;
            bottom: 0;
            width: 100%;
            z-index: 200000;
        }
        .panel.is-fullscreen .mce-tinymce {
            height:100%;
        }
        .panel.is-fullscreen .mce-edit-area,
        .panel.is-fullscreen .mce-edit-area iframe,
        .panel.is-fullscreen .mce-edit-area iframe html {
            height: 100%;
            position: absolute;
            width: 99%;
            overflow-y: scroll;
            overflow-x: hidden;
            min-height: 100%;
        }
        .form-control.is-invalid {
            border-color: #f55145;
        }
    </style>
@stop

@section('page_header')
    <h1 class="page-title">
        <i class="{{ $dataType->icon }}"></i>
        {{ __('voyager::generic.'.(isset($dataTypeContent->id) ? 'edit' : 'add')).' '.$dataType->display_name_singular }}
    </h1>
    @include('voyager::multilingual.language-selector')
@stop

@section('content')
    <div class="page-content container-fluid">
        <form class="form-edit-add" role="form" action="@if(isset($dataTypeContent->id)){{ route('voyager.projects.update', $dataTypeContent->id) }}@else{{ route('voyager.projects.store') }}@endif" method="POST" enctype="multipart/form-data">
            <!-- PUT Method if we are editing -->
            @if(isset($dataTypeContent->id))
                {{ method_field("PUT") }}
            @endif
            {{ csrf_field() }}

            <div class="row">
                <div class="col-md-8">
                    <!-- ### TITLE ### -->
                    <div class="panel">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-character"></i> {{ __('voyager::project.title') }}
                                <span class="badge badge-info language-label js-language-label"></span>
                                <span class="panel-desc"> {{ __('voyager::project.title_sub') }}</span>
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @include('voyager::multilingual.input-hidden', [
                                '_field_name'  => 'title',
                                '_field_trans' => get_field_translations($dataTypeContent, 'title')
                            ])
                            @php
                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                                $row = $dataTypeRows->where('field', 'title')->first();
                            @endphp
                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                        </div>
                    </div>

                    <!-- ### CONTENT ### -->
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-file-text"></i> {{ __('voyager::project.description') }}
                                <span class="badge badge-info language-label js-language-label"></span>
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-resize-full" data-toggle="panel-fullscreen" aria-hidden="true"></a>
                            </div>
                        </div>

                        <div class="panel-body">
                            @include('voyager::multilingual.input-hidden', [
                                '_field_name'  => 'description',
                                '_field_trans' => get_field_translations($dataTypeContent, 'description')
                            ])
                            @php
                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                                $row = $dataTypeRows->where('field', 'description')->first();
                            @endphp
                            {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                        </div>
                    </div><!-- .panel -->

                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-images"></i> {{ __('voyager::project.gallery') }}
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @php
                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                                $include = ['gallery'];
                            @endphp

                            @foreach($dataTypeRows as $row)
                                @if(in_array($row->field, $include))
                                    @php
                                        $display_options = $row->details->display ?? NULL;
                                    @endphp
                                    @if (isset($row->details->formfields_custom))
                                        @include('voyager::formfields.custom.' . $row->details->formfields_custom)
                                    @else
                                        <div class="form-group @if($row->type == 'hidden') hidden @endif @if(isset($display_options->width)){{ 'col-md-' . $display_options->width }}@endif" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                            {{ $row->slugify }}
                                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                                            @if($row->type == 'relationship')
                                                @include('voyager::formfields.relationship', ['options' => $row->details])
                                            @else
                                                {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                            @endif

                                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>
                <div class="col-md-4">
                    <!-- ### DETAILS ### -->
                    <div class="panel panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-info-circled"></i> {{ __('voyager::project.details') }}
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @php
                                $dataTypeRows = $dataType->{(isset($dataTypeContent->id) ? 'editRows' : 'addRows' )};
                                $exclude = ['title', 'description', 'image', 'meta_description', 'meta_keywords', 'meta_title', 'sort_order', 'gallery'];
                            @endphp

                            @foreach($dataTypeRows as $row)
                                @if(!in_array($row->field, $exclude))
                                    @php
                                        $display_options = $row->details->display ?? NULL;
                                    @endphp
                                    @if (isset($row->details->formfields_custom))
                                        @include('voyager::formfields.custom.' . $row->details->formfields_custom)
                                    @else
                                        <div class="form-group @if($row->type == 'hidden') hidden @endif @if(isset($display_options->width)){{ 'col-md-' . $display_options->width }}@endif" @if(isset($display_options->id)){{ "id=$display_options->id" }}@endif>
                                            {{ $row->slugify }}
                                            <label for="name">{{ $row->display_name }}</label>
                                            @include('voyager::multilingual.input-hidden-bread-edit-add')
                                            @if($row->type == 'relationship')
                                                @include('voyager::formfields.relationship', ['options' => $row->details])
                                            @else
                                                {!! app('voyager')->formField($row, $dataType, $dataTypeContent) !!}
                                            @endif

                                            @foreach (app('voyager')->afterFormFields($row, $dataType, $dataTypeContent) as $after)
                                                {!! $after->handle($row, $dataType, $dataTypeContent) !!}
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- ### IMAGE ### -->
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-images"></i> {{ __('voyager::project.image') }}
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            @if(isset($dataTypeContent->image))
                                <img src="{{ filter_var($dataTypeContent->image, FILTER_VALIDATE_URL) ? $dataTypeContent->image : Voyager::image( $dataTypeContent->image ) }}" style="width:100%" />
                            @endif
                            <input type="file" name="image">
                        </div>
                    </div>

                    <!-- ### SEO CONTENT ### -->
                    <div class="panel panel-bordered">
                        <div class="panel-heading">
                            <h3 class="panel-title">
                                <i class="voyager-terminal"></i> {{ __('voyager::project.seo_content') }}
                            </h3>
                            <div class="panel-actions">
                                <a class="panel-action voyager-angle-down" data-toggle="panel-collapse" aria-hidden="true"></a>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="seo_title">{{ __('voyager::project.meta_title') }} <span class="badge badge-info language-label js-language-label"></span></label>
                                @include('voyager::multilingual.input-hidden', [
                                    '_field_name'  => 'meta_title',
                                    '_field_trans' => get_field_translations($dataTypeContent, 'meta_title')
                                ])
                                <textarea class="form-control" name="meta_title">{{ $dataTypeContent->meta_title ?? '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="meta_description">{{ __('voyager::project.meta_description') }} <span class="badge badge-info language-label js-language-label"></span></label>
                                @include('voyager::multilingual.input-hidden', [
                                    '_field_name'  => 'meta_description',
                                    '_field_trans' => get_field_translations($dataTypeContent, 'meta_description')
                                ])
                                <textarea class="form-control" name="meta_description">{{ $dataTypeContent->meta_description ?? '' }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="meta_keywords">{{ __('voyager::project.meta_keywords') }} <span class="badge badge-info language-label js-language-label"></span></label>
                                @include('voyager::multilingual.input-hidden', [
                                    '_field_name'  => 'meta_keywords',
                                    '_field_trans' => get_field_translations($dataTypeContent, 'meta_keywords')
                                ])
                                <textarea class="form-control" name="meta_keywords">{{ $dataTypeContent->meta_keywords ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="buttons pull-right1 text-right">
                @if(isset($dataTypeContent->id))
                    <input type="hidden" id="save_and_stay" name="save_and_stay" value="0" />
                    <button type="submit" onclick="$('#save_and_stay').val(1);" class="btn btn-success mr-2">
                        {{ __('Update & Stay') }}
                    </button>
                @endif
                <button type="submit" class="btn btn-primary">
                    @if(isset($dataTypeContent->id)){{ __('voyager::project.update') }}@else <i class="icon wb-plus-circle"></i> {{ __('voyager::project.new') }} @endif
                </button>
            </div>
        </form>

        <iframe id="form_target" name="form_target" style="display:none"></iframe>
        <form id="my_form" action="{{ route('voyager.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0px;height:0;overflow:hidden">
            {{ csrf_field() }}
            <input name="image" id="upload_file" type="file" onchange="$('#my_form').submit();this.value='';">
            <input type="hidden" name="type_slug" id="type_slug" value="{{ $dataType->slug }}">
        </form>
    </div>
    <div class="modal fade modal-danger" id="confirm_delete_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title"><i class="voyager-warning"></i> {{ __('voyager::generic.are_you_sure') }}</h4>
                </div>
                <div class="modal-body">
                    <h4>{{ __('voyager::generic.are_you_sure_delete') }} '<span class="confirm_delete_name"></span>'</h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                    <button type="button" class="btn btn-danger" id="confirm_delete">{{ __('voyager::generic.delete_confirm') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('javascript')
    <script>
        $('document').ready(function () {
            $('#slug').slugify();

        @if ($isModelTranslatable)
            $('.side-body').multilingual({"editing": true});
        @endif

        $('.side-body input[data-slug-origin]').each(function(i, el) {
               $(el).slugify();
           });
            $('.form-group').on('click', '.remove-multi-image', function (e) {
               e.preventDefault();
               $image = $(this).siblings('img');
                params = {
                   slug:   '{{ $dataType->slug }}',
                   image:  $image.data('image'),
                   id:     $image.data('id'),
                   field:  $image.parent().data('field-name'),
                   _token: '{{ csrf_token() }}'
               }
                $('.confirm_delete_name').text($image.data('image'));
               $('#confirm_delete_modal').modal('show');
           });
            $('#confirm_delete').on('click', function(){
                $.post('{{ route('voyager.'.$dataType->slug.'.media.remove') }}', params, function (response) {
                   if ( response
                       && response.data
                       && response.data.status
                       && response.data.status == 200 ) {
                        toastr.success(response.data.message);
                       $image.parent().fadeOut(300, function() { $(this).remove(); })
                   } else {
                       toastr.error("Error removing image.");
                   }
               });
                $('#confirm_delete_modal').modal('hide');
           });
           $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@stop
