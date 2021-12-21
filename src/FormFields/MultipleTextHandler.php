<?php

namespace TCG\Voyager\FormFields;

class MultipleTextHandler extends AbstractHandler
{
    protected $codename = 'multiple_text';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.multiple_text', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
