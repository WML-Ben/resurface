<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\WorkOrder;

use ImageTrait;
use StringTrait;

class WorkOrdersController extends FrontEndBaseController
{
    use ImageTrait, StringTrait;

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $allStatus = auth()->user()->hasPrivilege('list-all-status-work-order');

        $statusId = 5;

        if ($allStatus) {
            $statusId = $request->statusId ?? $statusId;
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->statusFilter($statusId)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'statusCB'   => $allStatus ? \App\OrderStatus::workOrderStatusCB(['0' => 'All Status']) : null,
            'statusId'   => $allStatus ? $statusId : null,
            //'json_status_cb'         => json_encode(\App\OrderStatus::workOrderStatusCB(), JSON_FORCE_OBJECT),
            //'json_sales_managers_cb' => json_encode(\App\Employee::salesManagersCB(['0' => '']), JSON_FORCE_OBJECT),
            //'json_sales_persons_cb'  => json_encode(\App\Employee::salesPersonsCB(['0' => '']), JSON_FORCE_OBJECT),
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Work Orders',
            ],
        ];

        return view('work_order.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $allStatus = auth()->user()->hasPrivilege('list-all-status-work-order');

        $statusId = 5;

        if ($allStatus) {
            $statusId = $request->statusId ?? $statusId;
        }

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->search($needle)
            ->statusFilter($statusId)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'statusCB'   => $allStatus ? \App\OrderStatus::workOrderStatusCB(['0' => 'All Status']) : null,
            'statusId'   => $allStatus ? $statusId : null,
            //'json_status_cb'         => json_encode(\App\OrderStatus::workOrderStatusCB(), JSON_FORCE_OBJECT),
            //'json_sales_managers_cb' => json_encode(\App\Employee::salesManagersCB(['0' => '']), JSON_FORCE_OBJECT),
            //'json_sales_persons_cb'  => json_encode(\App\Employee::salesPersonsCB(['0' => '']), JSON_FORCE_OBJECT),
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Work Orders',
            ],
        ];

        return view('work_order.index', $data);
    }

    public function processing(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->workOrderProcessing()
            ->search($needle)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Processing Work Orders',
            ],
        ];

        return view('work_order.processing', $data);
    }

    public function processingSearch(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->workOrderProcessing()
            ->search($needle)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Processing Work Orders',
            ],
        ];

        return view('work_order.processing', $data);
    }

    public function active(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->workOrderActive()
            ->search($needle)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Active Work Orders',
            ],
        ];

        return view('work_order.active', $data);
    }

    public function activeSearch(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->workOrderActive()
            ->search($needle)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Active Work Orders',
            ],
        ];

        return view('work_order.active', $data);
    }

    public function billing(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->workOrderBilling()
            ->search($needle)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Billing Work Orders',
            ],
        ];

        return view('work_order.billing', $data);
    }

    public function billingSearch(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $workOrders = WorkOrder::basedOnRole()
            ->workOrderBilling()
            ->search($needle)
            ->with(['property', 'company'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'workOrders' => $workOrders,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Billing Work Orders',
            ],
        ];

        return view('work_order.billing', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Work Order',
            ],
        ];

        return view('work_order.create', $data);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        WorkOrder::create($request->all());

        return redirect()->route('work_order_list')->with('success', 'Work Order created.');
    }

    public function show(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('show-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }


    }

    public function edit(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('update-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'workOrder' => $workOrder,
            'seo'       => [
                'pageTitle' => 'Edit: ' . $workOrder->fullName,
            ],
        ];

        return view('work_order.edit', $data);
    }

    public function update(WorkOrder $workOrder, Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $workOrder->update($inputs);

        return redirect()->route('work_order_list')->withSuccess('Work Order updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-work-order')) {
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
                    \DB::transaction(function () use ($id, $name, $value, $relation, & $oldValue) {
                        $model = WorkOrder::find($id);

                        if (!empty($relation)) {
                            $oldValue = $model->{$relation}->{$name};
                            $model->{$relation}->update([$name => $value]);
                        } else {
                            $oldValue = $model->{$name};
                            $model->{$name} = $value;
                            $model->save();
                        }
                    });
                    $response = [
                        'status'    => 'success',
                        'field'     => $name,
                        'old_value' => $oldValue,
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

    public function toggleStatus(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('update-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $workOrder->disabled = !$workOrder->disabled;
        $workOrder->save();

        return redirect()->back()->with('success', 'Work Order status has been toggled.');
    }

    public function createNote(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('add-note-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'workOrder' => $workOrder,
            'seo'       => [
                'pageTitle' => 'Add Note to Work Order',
            ],
        ];

        return view('work_order.edit', $data);
    }

    public function storeNote(WorkOrder $workOrder, Request $request)
    {
        if (auth()->user()->isNotAllowTo('add-note-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $workOrder->update($inputs);

        return redirect()->route('work_order_list')->withSuccess('Work Order updated.');
    }

    public function editStatus(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('change-status-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'workOrder' => $workOrder,
            'seo'       => [
                'pageTitle' => 'Change Work Order Status',
            ],
        ];

        return view('work_order.edit', $data);
    }

    public function updateStatus(WorkOrder $workOrder, Request $request)
    {
        if (auth()->user()->isNotAllowTo('change-status-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $workOrder->update($inputs);

        return redirect()->route('work_order_list')->withSuccess('Work Order updated.');
    }


    public function uploadForm(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('add-upload-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'workOrder' => $workOrder,
            'seo'       => [
                'pageTitle' => 'Add Upload to Work Order',
            ],
        ];

        return view('work_order.edit', $data);
    }

    public function doUpload(WorkOrder $workOrder, Request $request)
    {
        if (auth()->user()->isNotAllowTo('add-upload-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $workOrder->update($inputs);

        return redirect()->route('work_order_list')->withSuccess('Work Order updated.');
    }

    public function print(WorkOrder $workOrder)
    {
        if (auth()->user()->isNotAllowTo('print-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'workOrder' => $workOrder,
            'seo'       => [
                'pageTitle' => 'Print WorkOrder',
            ],
        ];

        return view('work_order.edit', $data);
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$workOrder = WorkOrder::find($request->item_id)) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        $workOrder->delete();

        return redirect()->route('work_order_list')->withSuccess('Work Order deleted.');
    }

    public function ajaxUploadImage(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $input = $request->all();

            $data = $input['data'];
            $originalFileName = $input['name'];
            $original = (!empty($input['original'])) ? $input['original'] : null;

            $serverDir = storage_path() . '/tmp/';

            list(, $tmp) = explode(',', $data);
            $imgData = base64_decode($tmp);

            $nameInfo = pathinfo($originalFileName);
            $ranStr = substr(sha1(time()), 0, 6);

            $newFileName = $this->cleanFileName($nameInfo['filename']) . '-' . $ranStr . '.' . $nameInfo['extension'];

            $handle = fopen($serverDir . $newFileName, 'w');
            fwrite($handle, $imgData);
            fclose($handle);

            $response = [
                'status'           => 'success',
                'url'              => $newFileName . '?' . time(), // added the time to force update when editting multiple times
                'originalFileName' => $originalFileName,
                'newFileName'      => $newFileName,
            ];

            if (!empty($original)) {
                list(, $tmp) = explode(',', $original);
                $originalData = base64_decode($tmp);

                $original = $nameInfo['filename'] . '-' . $ranStr . '-original' . $nameInfo['extension'];

                $handle = fopen($serverDir . $original, 'w');
                fwrite($handle, $originalData);
                fclose($handle);

                $response['original'] = $original;
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

    public function ajaxDeleteImage(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $id = !empty($request->id) ? $request->id : null;
            $imageUrl = !empty($request->image) ? $request->image : null;

            $chunks = explode('/', $imageUrl);
            $image = array_pop($chunks);

            $serverDir = storage_path() . '/tmp/';

            if (!empty($image) && file_exists($serverDir . $image)) {
                unlink($serverDir . $image);
            }

            if (!empty($id)) {
                // delete from avatars folder and database:
                if ($this->s3) {                                                           // in S3 public folder
                    if (Storage::disk('s3')->exists('public/media/avatars/' . $image)) {
                        Storage::disk('s3')->delete('public/media/avatars/' . $image);
                    }
                } else {                                                                    // in local public folder
                    $avatarsPath = public_path() . '/media/avatars/';
                    if (file_exists($avatarsPath . $image)) {
                        unlink($avatarsPath . $image);
                    }
                }
                WorkOrder::find($id)->update(array ('avatar' => null));
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

}
