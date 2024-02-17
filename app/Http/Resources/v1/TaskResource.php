<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property mixed $id
 * @property mixed $user_id
 * @property mixed $task_name
 * @property mixed $task_description
 * @property mixed $task_priority
 * @property mixed $task_due
 */
class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'task_name' => $this->task_name,
            'task_description' => $this->task_description,
            'task_priority' => $this->task_priority,
            'task_due' => $this->task_due,
        ];
//        return parent::toArray($request);
    }
}
