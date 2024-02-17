<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestAuthorize;

class TaskStoreRequest extends FormRequest
{
    use RequestAuthorize;
//    public function authorize(): bool
//    {
//        if ($this->user_id == auth()->id()) {
//            return true;
//        }
//        elseif (in_array('admin', $this->attributes->get('roles'))) {
//            return true;
//        }
//        else {
//            return false;
//        }
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['integer', 'exists:users,id'],
            'task_name' => ['required', 'string'],
            'task_description' => ['required', 'string'],
            'task_priority' => ['required', 'string'],
            'task_due' => ['required', 'date_format:Y-m-d H:i:s'],
        ];
    }
}
