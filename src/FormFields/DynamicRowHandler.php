<?php

namespace TCG\Voyager\FormFields;

class DynamicRowHandler extends AbstractHandler
{
    protected $codename = 'dynamic_row';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.dynamic_row', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}
