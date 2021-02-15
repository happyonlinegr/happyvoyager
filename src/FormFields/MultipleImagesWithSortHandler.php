<?php

namespace TCG\Voyager\FormFields;

class MultipleImagesWithSortHandler extends AbstractHandler
{
    protected $codename = 'multiple_images_with_sort';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.multiple_images_with_sort', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
