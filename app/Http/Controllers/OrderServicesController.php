<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\OrderService;

use ImageTrait;
use StringTrait;

class OrderServicesController extends FrontEndBaseController
{
    use ImageTrait, StringTrait;

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $statusId = $request->statusId ?? 1000;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $orderServices = OrderService::statusFilter($statusId)
            ->whereHas('order', function ($w) {
                $w->workOrderActive()->basedOnRole();
            })
            ->with(['order' => function ($q) {
                $q->with(['property']);
            }, 'orderServiceStatus', 'service', 'manager', 'subManager'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'orderServices'             => $orderServices,
            'statusCB'                  => \App\OrderServiceStatus::statusCB(['0' => 'All Status', '1000' => 'Scheduled and Not Scheduled']),
            'statusId'                  => $statusId,
            'json_job_site_managers_cb' => json_encode(\App\Employee::jobSiteManagersCB(['0' => '']), JSON_FORCE_OBJECT),
            'needle'                    => null,
            'seo'                       => [
                'pageTitle' => 'Order Services',
            ],
        ];

        return view('order_service.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $statusId = $request->statusId ?? 1000;
        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $orderServices = OrderService::statusFilter($statusId)
            ->search($needle)
            ->whereHas('order', function ($w) {
                $w->workOrderActive()->basedOnRole();
            })
            ->with(['order' => function ($q) {
                $q->with(['property']);
            }, 'orderServiceStatus', 'service', 'manager', 'subManager'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'orderServices'             => $orderServices,
            'statusCB'                  => \App\OrderServiceStatus::statusCB(['0' => 'All Status']),
            'statusId'                  => $statusId,
            'json_job_site_managers_cb' => json_encode(\App\Employee::jobSiteManagersCB(['0' => '']), JSON_FORCE_OBJECT),
            'needle'                    => $needle,
            'seo'                       => [
                'pageTitle' => 'Order Services',
            ],
        ];

        return view('order_service.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Order Service',
            ],
        ];

        return view('order_service.create', $data);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        OrderService::create($request->all());

        return redirect()->route('order_service_list')->with('success', 'Order Service created.');
    }

    public function show(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('show-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }


    }

    public function edit(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('update-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'orderService' => $orderService,
            'seo'          => [
                'pageTitle' => 'Edit: ' . $orderService->fullName,
            ],
        ];

        return view('order_service.edit', $data);
    }

    public function update(OrderService $orderService, Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $orderService->update($inputs);

        return redirect()->route('order_service_list')->withSuccess('Order Service updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-order-service')) {
            $response = [
                'status'  => 'error',
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {
            $relation = false;

            $id = $request->pk;

            if (strpos($request->name, '->') === false) {
                $name = $request->name;
            } else {
                $tokens = explode('.', $relation);
                $name = array_pop($tokens);

                $relation = implode('->', $tokens);
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
                        $model = OrderService::find($id);

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

    public function toggleStatus(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('update-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $orderService->disabled = !$orderService->disabled;
        $orderService->save();

        return redirect()->back()->with('success', 'Order Service status has been toggled.');
    }

    public function createNote(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('add-note-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'orderService' => $orderService,
            'seo'          => [
                'pageTitle' => 'Add Note to Order Service',
            ],
        ];

        return view('order_service.edit', $data);
    }

    public function storeNote(OrderService $orderService, Request $request)
    {
        if (auth()->user()->isNotAllowTo('add-note-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $orderService->update($inputs);

        return redirect()->route('order_service_list')->withSuccess('Order Service updated.');
    }

    public function editStatus(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('change-status-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'orderService' => $orderService,
            'seo'          => [
                'pageTitle' => 'Change Order Service Status',
            ],
        ];

        return view('order_service.edit', $data);
    }

    public function updateStatus(OrderService $orderService, Request $request)
    {
        if (auth()->user()->isNotAllowTo('change-status-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $orderService->update($inputs);

        return redirect()->route('order_service_list')->withSuccess('Order Service updated.');
    }


    public function uploadForm(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('add-upload-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'orderService' => $orderService,
            'seo'          => [
                'pageTitle' => 'Add Upload to Order Service',
            ],
        ];

        return view('order_service.edit', $data);
    }

    public function doUpload(OrderService $orderService, Request $request)
    {
        if (auth()->user()->isNotAllowTo('add-upload-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $orderService->update($inputs);

        return redirect()->route('order_service_list')->withSuccess('Order Service updated.');
    }

    public function print(OrderService $orderService)
    {
        if (auth()->user()->isNotAllowTo('print-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'orderService' => $orderService,
            'seo'          => [
                'pageTitle' => 'Print OrderService',
            ],
        ];

        return view('order_service.edit', $data);
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$orderService = OrderService::find($request->item_id)) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        $orderService->delete();

        return redirect()->route('order_service_list')->withSuccess('Order Service deleted.');
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
                OrderService::find($id)->update(array ('avatar' => null));
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

}
