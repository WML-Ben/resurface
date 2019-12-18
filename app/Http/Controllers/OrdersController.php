<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Order;
use App\Http\Requests\OrderRequest;

use UserObserver;

use ImageTrait;
use StringTrait;

class OrdersController extends FrontEndBaseController
{
    use ImageTrait, StringTrait;

    public function __construct()
    {
        parent::__construct();

        // Order::observe(new UserObserver);
    }

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);
        $proposals = Order::basedOnRole()->sortable()->paginate($perPage);
        $data = [
            'orders' => $proposals,
            'needle'    => null,
            'seo'       => [
                'pageTitle' => 'Orders',
            ],
        ];

        return view('order.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);
        $proposals = Order::noRoot()->search($needle)->sortable()->paginate($perPage);
        $data = [
            'orders' => $proposals,
            'needle'    => $needle,
            'seo'       => [
                'pageTitle' => 'Orders',
            ],
        ];

        return view('order.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'rolesCB' => $this->rolesBelowCB(auth()->user()->role_id, ['0' => 'Roles...'], false),
            'seo'     => [
                'pageTitle' => 'New Order',
            ],
        ];

        return view('order.create', $data);
    }

    public function store(OrderRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Order::create($request->all());

        return redirect()->route('order_list')->with('success', 'Order created.');
    }

    public function show($id)
    {
        if (auth()->user()->isNotAllowTo('show-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$proposal = Order::find($id)) {
            return redirect()->back()->with('error', 'Order not found.');
        }
    }

    public function edit($id)
    {
        if (auth()->user()->isNotAllowTo('update-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$proposal = Order::find($id)) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $editableOrder = Order::find($proposal->id);

        if (!empty($editableOrder->role)) {
            $roleName = $editableOrder->role->name;

            if (!auth()->user()->hasRole($roleName)) {
                return redirect()->back()->with('error', 'You cannot modify a order with a higher role than yours.');
            }
        }

        $data = [
            'order' => $proposal,
            'rolesCB'  => $this->rolesBelowCB(auth()->user()->role_id),
            'seo'      => [
                'pageTitle' => 'Edit: ' . $proposal->fullName,
            ],
        ];

        return view('order.edit', $data);
    }

    public function profile()
    {
        $data = [
            'order' => auth()->user(),
            'returnTo' => \URL::previous(),
            'seo'      => [
                'pageTitle' => 'Profile: ' . auth()->user()->fullName,
            ],
        ];

        return view('order.profile', $data);
    }

    public function updateProfile(OrderRequest $request)
    {
        if (!$proposal = \App\Order::find(auth()->user()->id)) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        if (empty($inputs['password'])) {
            unset($inputs['password']);
            unset($inputs['repeat_password']);
        } else {
            if ($inputs['password'] == $inputs['repeat_password']) {
                $inputs['password'] = \Hash::make($inputs['password']);
            } else {
                return redirect()->back()->withError('Las contraseñas no coinciden.');
            }
        }

        $proposal->update($inputs);

        return redirect()->back()->with('success', 'Profile updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-order')) {
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

            /*
             * The first argument passed to the make method is the data under validation.
             * The second argument is the validation rules that should be applied to the data.
             */

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
                        $model = Order::find($id);

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

    public function update($id, OrderRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$proposal = Order::find($id)) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        if (empty($inputs['password'])) {
            unset($inputs['password']);
            unset($inputs['repeat_password']);
        } else {
            if ($inputs['password'] == $inputs['repeat_password']) {
                $inputs['password'] = \Hash::make($inputs['password']);
            } else {
                return redirect()->back()->withError('Passwords don\'t match.');
            }
        }

        $proposal->update($inputs);

        return redirect()->route('order_list')->withSuccess('Order updated.');
    }

    public function toggleStatus($id)
    {
        if (auth()->user()->isNotAllowTo('update-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $proposal = Order::find($id);
        $proposal->disabled = !$proposal->disabled;
        $proposal->save();

        return redirect()->back()->with('success', 'Order status has been toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$proposal = Order::find($request->item_id)) {
            return redirect()->back()->with('error', 'Order not found.');
        }

        $proposal->delete();

        return redirect()->route('order_list')->withSuccess('Order deleted.');
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
                Order::find($id)->update(array ('avatar' => null));
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

}
