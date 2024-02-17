<?php

namespace App\Repositories;

use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\v1\TaskResource;
use App\Models\Task as TaskModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class taskUpdateRepository extends RepositoryInterface
{
    private TaskUpdateRequest $request;
    private mixed $user_id;
    private mixed $access;
    private mixed $id;

    public function __construct(TaskUpdateRequest $request, $id)
    {
        $this->request = $request;
        $this->setId($id);
        $this->setAccess();
        $this->setUserId($this->request->input('user_id'));

        parent::__construct();
    }

    public function model()
    {
        return TaskModel::class;
    }

    public function updateRepository()
    {
        try {
        $user_id = auth()->id();
        $task = TaskModel::when($this->access == false, function ($q) use ($user_id) {
            return $q->where('user_id', '=', $user_id);
        })->findOrFail($this->id);
        }
        catch (NotFoundHttpException $ex) {
            $ex->getMessage();
        }

        $task->user_id = $this->user_id;

        if (isset($this->request->task_name)) {
            $task->task_name = $this->request->task_name;
        }
        if (isset($this->request->task_description)) {
            $task->task_description = $this->request->task_description;
        }
        if (isset($this->request->task_priority)) {
            $task->task_priority = $this->request->task_priority;
        }
        if (isset($this->request->task_due)) {
            $task->task_due = $this->request->task_due;
        }
        $task->save();

        return new TaskResource($task);
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer', 'exists:tasks,id']]
        );
        $validated = $validator->validated();
        $this->id = $validated['id'];
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId(mixed $user_id): void
    {
        if ($user_id) {
            if (in_array('admin', $this->request->attributes->get('roles'))) {
                $this->user_id = $user_id;
            }
            else {
                $this->user_id = auth()->id();
            }
        }
        else {
            $this->user_id = auth()->id();
        }
    }

    /**
     * @return mixed
     */
    public function getUserId(): mixed
    {
        return $this->user_id;
    }

    public function setAccess(): void
    {
        if (in_array('admin', $this->request->attributes->get('roles'))) {
            $this->access = true;
        }
        else {
            $this->access = false;
        }
    }
}
