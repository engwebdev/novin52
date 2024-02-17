<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\RequestAuthorize;

class TaskUpdateRequest extends FormRequest
{
    use RequestAuthorize;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['string','exists:users,id'],
            'task_name' => ['string'],
            'task_description' => ['string'],
            'task_priority' => ['string'],
            'task_due' => ['date_format:Y-m-d H:i:s'],
        ];
    }
}
