<?php

namespace App\Repositories;

use App\Http\Requests\Task\TaskShowRequest;
use App\Http\Resources\v1\TaskResource;
use App\Models\Task as TaskModel;
use Illuminate\Support\Facades\Validator;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class taskShowRepository extends RepositoryInterface
{

    protected TaskShowRequest $request;
    private mixed $access;
    private mixed $id;

    public function __construct(TaskShowRequest $request, $id)
    {
        $this->request = $request;
        $this->setId($id);
        $this->setAccess();
        parent::__construct();
    }

    public function model()
    {
        return TaskModel::class;
    }

    public function showRepository()
    {
        try {
            $user_id = auth()->id();
            $task = TaskModel::when($this->access == false, function ($q) use ($user_id) {
                return $q->where('user_id', '=', $user_id);
            })->findOrFail($this->id);
            return new TaskResource($task);
        }
        catch (NotFoundHttpException $ex) {
            $ex->getMessage();
        }
    }

    /**
     * @param mixed $id
     */
    public function setId(mixed $id): void
    {
        $validator = Validator::make(
            ['id' => $id],
            ['id' => ['required', 'integer']]
        );
        $validated = $validator->validated();
        $this->id = $validated['id'];
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
