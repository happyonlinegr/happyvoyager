<?php

namespace TCG\Voyager\Http\Controllers\ContentTypes;

class MultipleText extends BaseType
{
    /**
     * @return null|string
     */
    public function handle()
    {
        $value = $this->request->input($this->row->field);
        $value = json_decode($value);        

        if (isset($this->options->null)) {
            return $value == $this->options->null ? null : $value;
        }

        return $value;
    }
}
