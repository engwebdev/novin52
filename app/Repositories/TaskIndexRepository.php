<?php

namespace App\Repositories;

use App\Http\Requests\Task\TaskIndexRequest;
use App\Http\Resources\v1\TaskCollection;
use App\Http\Resources\v1\TaskResource;
use App\Models\Task as TaskModel;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorAlias;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaskIndexRepository extends RepositoryInterface
{
    protected TaskIndexRequest $request;
    private mixed $user_id;
    private mixed $access;
    private \Symfony\Component\HttpFoundation\InputBag $query;
    private \Symfony\Component\HttpFoundation\InputBag $searchItems;
    public \Illuminate\Database\Eloquent\Builder $taskDBQuery;

    private string|int|bool|null|float $sort_by;
    private string|int|bool|null|float $order_by;
    private string|int|bool|null|float $limit;
    private string|int|bool|null|float $page;
    private string|int|bool|null|float $filter;

    public function __construct(TaskIndexRequest $request)
    {
        $this->request = $request;
        $this->query = $request->query;
        $this->searchItems = $this->query;

        $this->setSortBy($this->query->get('sort_by'));
        $this->setOrderBy($this->query->get('order_by'));
        $this->setLimit($this->query->get('limit'));
        $this->setPage($this->query->get('page'));
        $this->setFilter($this->query->get('filter'));

        $this->setUserId();
        parent::__construct();
    }

    public function model()
    {
        return TaskModel::class;
    }

    /**
     * @param TaskIndexRequest $request
     */
    public function indexRepository()
    {
        try {
            $this->taskDBQuery = TaskModel::query();
            $user_id = $this->user_id;
            $resulte = $this->taskDBQuery
                ->when($this->access == false, function ($q) use ($user_id) {
                    return $q->where('user_id', '=', $user_id);
                })
                ->when($this->filter == 'past', function ($q) {
                    return $q->where('task_due', '<', now()->toDateString());
                })
                ->when($this->filter == 'future', function ($q) {
                    return $q->where('task_due', '>', now()->toDateString());
                })
                ->when($this->filter == 'all', function ($q) {
                    return $q;
                })
                ->orderBy($this->sort_by, $this->order_by)
                ->paginate($this->limit, '*', 'page', $this->page);

//            return new TaskCollection($resulte);
            return TaskResource::collection($resulte);

        }
        catch (NotFoundHttpException $ex) {
            $ex->getMessage();
        }
    }

    /**
     * @param bool|float|int|string|null $sort_by
     */
    public function setSortBy(float|bool|int|string|null $sort_by): void
    {
        $arr_sort = ['id', 'task_name', 'task_description', 'task_priority'];
        if ($sort_by) {
            $res_1 = in_array(strtoupper($sort_by), $arr_sort);
            if (!$res_1) {
                $this->sort_by = 'id';
            }
            else {
                $this->sort_by = $sort_by;
            }
        }
        else {
            $this->sort_by = 'id';
        }
    }

    /**
     * @param bool|float|int|string|null $order_by
     */
    public function setOrderBy(float|bool|int|string|null $order_by): void
    {
        $arr_order = ['ASC', 'DESC'];
        if ($order_by) {
            $res_2 = in_array(strtoupper($order_by), $arr_order);
            if (!$res_2) {
                $this->order_by = 'asc';
            }
            else {
                $this->order_by = $order_by;
            }
        }
        else {
            $this->order_by = 'asc';
        }
    }

    /**
     * @param bool|float|int|string|null $limit
     */
    public function setLimit(float|bool|int|string|null $limit): void
    {
        if (is_numeric($limit)) {
            $this->limit = $limit;
        }
        else {
            $this->limit = '10';
        }
    }

    /**
     * @param bool|float|int|string|null $page
     */
    public function setPage(float|bool|int|string|null $page): void
    {
        if (is_numeric($page)) {
            $this->page = $page;
        }
        else {
            $this->page = '1';
        }
    }

    /**
     * @param bool|float|int|string|null $filter
     */
    public function setFilter(float|bool|int|string|null $filter): void
    {
        if ($filter) {
            if ($filter == 'past') {
                $this->filter = 'past';
            }
            else if ($filter == 'future') {
                $this->filter = 'future';
            }
            else {
                $this->filter = 'all';
            }
        }
        else {
            $this->filter = 'all';
        }
    }

    /**
     * @param int|mixed|string|null $user_id
     */
    public function setUserId(): void
    {
        $this->user_id = auth()->id();

        if (in_array('admin', $this->request->attributes->get('roles'))) {
            $this->access = true;
        }
        else {
            $this->access = false;
        }
    }

}
