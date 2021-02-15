@if(isset($dataTypeContent->{$row->field}))
    <?php $images = json_decode($dataTypeContent->{$row->field}); ?>
    @if($images != null)
        <div class="multiple-images">
            <div class="row">
                @foreach($images as $image)
                    <div class="multiple-images-wrap">
                        <div class="img_settings_container" data-field-name="{{ $row->field }}">
                            <div class="links">
                                <a href="#" class="remove-multi-image-ext">DELETE</a>
                            </div>
                            <div class="img-wrap">
                                <img src="{{ Voyager::image( $image->name ) }}" data-image="{{ $image->name }}" data-id="{{ $dataTypeContent->getKey() }}">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="{{ $row->field }}_ext[{{ $loop->index }}][name]" value="{{ isset($image->name) ? $image->name : "" }}"></label>
                                <label>
                                    <b>Title (GR):</b>
                                    <input class="form-control" type="text" name="{{ $row->field }}_ext[{{ $loop->index }}][title]" value="{{ isset($image->title) ? $image->title : "" }}">
                                </label>
                                <label>
                                    <b>Title (EN):</b>
                                    <input class="form-control" type="text" name="{{ $row->field }}_ext[{{ $loop->index }}][title_en]" value="{{ isset($image->title_en) ? $image->title_en : "" }}">
                                </label>
                                <label>
                                    <b>Alt:</b>
                                    <input class="form-control" type="text" name="{{ $row->field }}_ext[{{ $loop->index }}][alt]" value="{{ isset($image->alt) ? $image->alt : "" }}">
                                </label>
                                <label>
                                    <b>Sort:</b>
                                    <input class="form-control" type="number" name="{{ $row->field }}_ext[{{ $loop->index }}][sort]" value="{{ isset($image->sort) ? $image->sort : "" }}">
                                </label>
                                <label class="hidden">
                                    <b>Vimeo ID:</b>
                                    <input class="form-control" type="text" name="{{ $row->field }}_ext[{{ $loop->index }}][vimeo_id]" value="{{ isset($image->vimeo_id) ? $image->vimeo_id : "" }}">
                                </label>
                                <label class="hidden">
                                    <b>Youtube ID:</b>
                                    <input class="form-control" type="text" name="{{ $row->field }}_ext[{{ $loop->index }}][youtube_id]" value="{{ isset($image->youtube_id) ? $image->youtube_id : "" }}">
                                </label>
                            </div>                    
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@endif
<div class="clearfix"></div>
<input @if($row->required == 1) required @endif type="file" name="{{ $row->field }}[]" multiple="multiple" accept="image/*">

<script>
document.addEventListener('DOMContentLoaded', function(){
    $('.remove-multi-image-ext').on('click', function (e) {
        e.preventDefault();
        $file = $(this).parent().parent().fadeOut(300, function() { $(this).remove(); });
    });
    
    $('.show-inputs').on('click', function (e) {
        e.preventDefault();
        // $(this).parent().parent().children('.form-group').toggle();
    });    
});
</script>

<style>
.multiple-images{
    margin-bottom: 10px;
}
.multiple-images .row {
    display: flex;
    flex-wrap: wrap;
    margin-right: -10px;
    margin-left: -10px;
}
.multiple-images .links{
    justify-content: center;
    display: flex;
}
.multiple-images .links a{
    margin: 0 5px;
}
.multiple-images-wrap {
    padding: 20px 10px 0px 10px;
    display: flex;
    flex-direction: column;
    width: 350px;
    -webkit-box-flex: 0;
    -ms-flex: 0 1 33.3333%;
    flex: 0 1 33.3333%;
}
.multiple-images-wrap .form-group {
    margin-bottom: 0px;
}
.multiple-images-wrap>div{
    border: 1px solid #e4eaec;
    padding: 10px;
    margin-bottom: 0;
    position: relative;
}
.multiple-images .img-wrap {
    min-height: 170px;
    max-height: 170px;
    background: #ddd;
    border: 1px solid #ddd;
    margin-bottom: 10px;
}
.multiple-images img{
    max-width: 100%;
    height:auto;
    max-height: 150px;
    display:block;
    margin: 10px auto 0 auto;
}
.multiple-images label{
    display: block;
    margin-bottom: 6px;
}
.multiple-images label b{
    display: inline-block;
    width: 100%;
}
.multiple-images label input{
    width: 100%;
    display: inline-block;
    height: 28px;
    padding: 4px 6px;
    font-size: 13px;
}
.multiple-images .links .remove-multi-image-ext {
    position: absolute;
    top: 0;
    right: 0;
    background: #f96868;
    margin: 0;
    color: #fff;
    padding: 0 6px;
    font-size: 12px;
    outline: none;
}
.multiple-images .links .remove-multi-image-ext:hover {
    background: #a94442;
}
.voyager .multiple-images ~ input[type=file] {
    width: 100%;
    background: #efefef;
}
</style>