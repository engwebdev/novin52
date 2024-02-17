<?php

namespace App\Traits;

trait RequestAuthorize
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if (in_array('user', $this->attributes->get('roles'))) {
            return true;
        }
        elseif (in_array('admin', $this->attributes->get('roles'))) {
            return true;
        }
        else {
            return false;
        }
    }
}
