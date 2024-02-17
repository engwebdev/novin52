<?php

namespace App\Repositories;

use App\Http\Requests\Task\TaskDestroyRequest;
use App\Http\Resources\v1\TaskResource;
use App\Models\Task as TaskModel;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class taskDestroyRepository extends RepositoryInterface
{

    private TaskDestroyRequest $request;
    private mixed $id;
    private mixed $user_id;

    public function __construct(TaskDestroyRequest $request, $id)
    {
        $this->request = $request;
        $this->setId($id);
        $this->setUserId($this->request->input('user_id'));

        parent::__construct();
    }

    public function model()
    {
        return TaskModel::class;
    }

    public function DestroyRepository()
    {
        $task = TaskModel::findOrFail($this->id);

        if (in_array('admin', $this->request->attributes->get('roles'))) {
            $task->delete();
        }
        else {
            if ($task->user_id == $this->user_id) {
                $task->delete();
            }
            else {
                throw new AccessDeniedHttpException;
            }
        }

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
     * @return mixed
     */
    public function getId(): mixed
    {
        return $this->id;
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

}
