<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Task;
use App\Http\Requests\TaskRequest;
use App\Http\Requests\SearchRequest;

class TasksController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $tasks = Task::basedOnRole()->sortable('due_at')->with(['assignedTo', 'createdBy'])->paginate($perPage);

        $data = [
            'tasks'  => $tasks,
            'needle' => null,
            'seo'    => [
                'pageTitle' => 'Tasks',
            ],
        ];

        return view('task.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $tasks = Task::basedOnRole()->search($needle)->sortable('due_at')->with(['assignedTo', 'createdBy'])->paginate($perPage);

        $data = [
            'tasks'  => $tasks,
            'needle' => $needle,
            'seo'    => [
                'pageTitle' => 'Tasks - Search Results',
            ],
        ];

        return view('task.index', $data);
    }

    public function show(Task $task)
    {
        if (auth()->user()->isNotAllowTo('show-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($task);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'taskCategoriesCB' => \App\TaskCategory::categoriesCB(['0' => '']),
            'countriesCB'      => \App\Country::countriesCB(['0' => '']),
            'statesCB'         => \App\State::statesCB(231, ['0' => '']),
            'seo'              => [
                'pageTitle' => 'New Task',
            ],
        ];

        return view('task.create', $data);
    }

    public function store(TaskRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (!empty($inputs['above_as_billing_address'])) {
            $inputs['billing_address'] = $inputs['address'];
            $inputs['billing_address_2'] = $inputs['address_2'] ?? null;
            $inputs['billing_city'] = $inputs['city'] ?? null;
            $inputs['billing_zipcode'] = $inputs['zipcode'] ?? null;
            $inputs['billing_state_id'] = $inputs['state_id'] ?? null;
            $inputs['billing_country_id'] = $inputs['country_id'] ?? null;
        }

        $inputs['created_at'] = now(session()->get('timezone'));
        $inputs['created_by'] = auth()->user()->id;

        Task::create($inputs);

        return redirect()->route('task_list')->withSuccess('Task created.');
    }

    public function ajaxStore(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-task')) {
            $response = [
                'success' => false,
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];
        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'name'        => $request->new_task_name,
                    'category_id' => $request->new_task_category_id,
                    'address'     => $request->new_task_address,
                    'address_2'   => $request->new_task_address_2,
                    'city'        => $request->new_task_city,
                    'zipcode'     => $request->new_task_zipcode,
                    'country_id'  => $request->new_task_country_id,
                    'state_id'    => $request->new_task_state_id,
                ],
                [
                    'name'        => 'required|plainText',
                    'category_id' => 'required|positive',
                    'address'     => 'required|plainText',
                    'address_2'   => 'nullable|plainText',
                    'city'        => 'required|plainText',
                    'zipcode'     => 'required|plainText',
                    'country_id'  => 'required|positive',
                    'state_id'    => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    \DB::transaction(function () use ($request, & $task) {
                        $data = [
                            'name'               => $request->new_task_name,
                            'category_id'        => $request->new_task_category_id,
                            'address'            => $request->new_task_address,
                            'address_2'          => $request->new_task_address_2,
                            'city'               => $request->new_task_city,
                            'zipcode'            => $request->new_task_zipcode,
                            'country_id'         => $request->new_task_country_id,
                            'state_id'           => $request->new_task_state_id,
                            'billing_address'    => $request->new_task_address,
                            'billing_address_2'  => $request->new_task_address_2,
                            'billing_city'       => $request->new_task_city,
                            'billing_zipcode'    => $request->new_task_zipcode,
                            'billing_country_id' => $request->new_task_country_id,
                            'billing_state_id'   => $request->new_task_state_id,
                        ];

                        $task = Task::create($data);
                    });
                    $response = [
                        'success'           => true,
                        'task'              => $task,
                        'billing_states_cb' => !empty($task->billing_country_id) ? \App\State::statesCB($task->billing_country_id) : [],
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function edit(Task $task)
    {
        if (auth()->user()->isNotAllowTo('update-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'task'             => $task,
            'taskCategoriesCB' => \App\TaskCategory::categoriesCB(),
            'countriesCB'      => \App\Country::countriesCB(['0' => '']),
            'statesCB'         => \App\State::statesCB($task->country_id ?? 231, ['0' => '']),
            'seo'              => [
                'pageTitle' => 'Edit Task',
            ],
        ];

        return view('task.edit', $data);
    }

    public function update(Task $task, TaskRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $task->update($inputs);

        return redirect()->route('task_list')->withSuccess('Task updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-task')) {
            $response = [
                'status'  => 'error',
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {
            $relation = false;

            $id = $request->pk;
            if (strpos($request->name, '.') === false) {
                $name = $request->name;
            } else {
                list($relation, $name) = explode('.', $request->name);
            }
            $value = $request->value;
            $rule = (isset($request->rule)) ? $request->rule : 'text';

            $validator = \Validator::make(
                [$name => $value],
                [$name => $rule]
            );

            if ($validator->fails()) {
                $response = [
                    'status'  => 'error',
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    \DB::transaction(function () use ($id, $name, $value, $relation) {
                        $model = Task::find($id);

                        if (!empty($relation)) {
                            $model->{$relation}->update([$name => $value]);
                        } else {
                            $model->{$name} = $value;
                            $model->save();
                        }
                    });
                    $response = [
                        'status' => 'success',
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'status'  => 'error',
                        'message' => $e->getMessage(),
                    ];
                }
            }
        } else {
            $response = [
                'status'  => 'error',
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function toggleStatus(Task $task)
    {
        if (auth()->user()->isNotAllowTo('update-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Task status toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-task')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Task::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('task_list')->withSuccess('Task deleted.');
    }


}
