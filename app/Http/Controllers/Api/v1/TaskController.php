<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Repositories\taskDestroyRepository;
use App\Repositories\TaskIndexRepository;
use App\Repositories\taskShowRepository;
use App\Repositories\TaskStoreRepository;
use App\Repositories\taskUpdateRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskShowRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Requests\Task\TaskDestroyRequest;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(TaskIndexRequest $request)
    {
        $task = (new taskIndexRepository($request))->indexRepository();

        return $task;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskStoreRequest $request)
    {
        $task = (new taskStoreRepository($request))->storeRepository();

        return $task;
    }

    /**
     * Display the specified resource.
     */
    public function show(TaskShowRequest $request, string $id)
    {
        $task = (new taskShowRepository($request, $id))->showRepository();

        return $task;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskUpdateRequest $request, string $id)
    {
        $task = (new taskUpdateRepository($request, $id))->updateRepository();

        return $task;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskDestroyRequest $request, string $id)
    {
        $task = (new taskDestroyRepository($request, $id))->DestroyRepository();
        return $task;
    }
}
