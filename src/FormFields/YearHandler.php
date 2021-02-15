<?php

namespace TCG\Voyager\FormFields;

class YearHandler extends AbstractHandler
{
    protected $codename = 'year';

    public function createContent($row, $dataType, $dataTypeContent, $options)
    {
        return view('voyager::formfields.year', [
            'row'             => $row,
            'options'         => $options,
            'dataType'        => $dataType,
            'dataTypeContent' => $dataTypeContent,
        ]);
    }
}