<?php

namespace App\Repositories;

use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Resources\v1\TaskResource;
use App\Models\Task as TaskModel;

class TaskStoreRepository extends RepositoryInterface
{
    protected TaskStoreRequest $request;
    private mixed $user_id;
    private mixed $task_name;
    private mixed $task_description;
    private mixed $task_priority;
    private mixed $task_due;

    public function __construct(TaskStoreRequest $request)
    {
        $this->request = $request;

        $this->task_name = $request->input('task_name');
        $this->task_description = $request->input('task_description');
        $this->task_priority = $request->input('task_priority');
        $this->task_due = $request->input('task_due');

        $this->setUserId($request->input('user_id'));
        parent::__construct();
    }

    public function model()
    {
        return TaskModel::class;
    }

    public function storeRepository()
    {
        $task = TaskModel::create([
            'user_id' => $this->user_id,
            'task_name' => $this->task_name,
            'task_description' => $this->task_description,
            'task_priority' => $this->task_priority,
            'task_due' => $this->task_due,
        ]);

        return new TaskResource($task);
    }

    /**
     * @param int|mixed|string|null $user_id
     */
    public function setUserId(mixed $user_id): void
    {
        if ($user_id) {
            if (in_array('admin', $this->request->attributes->get('roles'))) {
                $this->user_id = $user_id;
            }else{
                $this->user_id = auth()->id();
            }
        }
        else {
            $this->user_id = auth()->id();
        }
    }


}
