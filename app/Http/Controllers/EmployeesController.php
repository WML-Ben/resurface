<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Employee;
use App\Http\Requests\EmployeeRequest;

use UserObserver;

use ImageTrait;
use StringTrait;

class EmployeesController extends FrontEndBaseController
{
    use ImageTrait, StringTrait;

    public function __construct()
    {
        parent::__construct();

        Employee::observe(new UserObserver);
    }

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $showAll = $request->show_all ?? 0;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $employees = Employee::noRoot()->showAllFilter($showAll)->sortable()->paginate($perPage);

        $data = [
            'employees' => $employees,
            //'json_company_position_cb' => json_encode(\App\CompanyPosition::positionsCB(), JSON_FORCE_OBJECT),
            'needle'    => null,
            'showAll'   => $showAll,
            'seo'       => [
                'pageTitle' => 'Employees',
            ],
        ];

        return view('employee.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $showAll = $request->show_all ?? 0;

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $employees = Employee::noRoot()->showAllFilter($showAll)->search($needle)->sortable()->paginate($perPage);

        $data = [
            'employees' => $employees,
            'needle'    => $needle,
            'showAll'   => $showAll,
            'seo'       => [
                'pageTitle' => 'Employees',
            ],
        ];

        return view('employee.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'rolesCB'     => $this->rolesBelowCB(auth()->user()->role_id, ['0' => 'Roles...'], false),
            'countriesCB' => \App\Country::countriesCB(['0' => '']),
            'statesCB'    => \App\State::statesCB(231, ['0' => '']),
            'seo'         => [
                'pageTitle' => 'New Employee',
            ],
        ];

        return view('employee.create', $data);
    }

    public function store(EmployeeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), (new Employee())->dates);

        try {
            \DB::transaction(function () use ($inputs) {
                Employee::create($inputs);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('employee_list')->with('success', 'Employee created.');
    }

    public function show(Employee $employee)
    {
        if (auth()->user()->isNotAllowTo('show-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($employee);
    }

    public function edit(Employee $employee)
    {
        if (auth()->user()->isNotAllowTo('update-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$employee) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        if (!empty($employee->role->name) && !auth()->user()->hasRole($employee->role->name)) {
            return redirect()->back()->with('error', 'You cannot modify a employee with a higher role than yours.');
        }

        $data = [
            'employee'    => $employee,
            'rolesCB'     => $this->rolesBelowCB(auth()->user()->role_id),
            'countriesCB' => \App\Country::countriesCB(['0' => '']),
            'statesCB'    => \App\State::statesCB($employee->country_id ?? 231, ['0' => '']),
            'seo'         => [
                'pageTitle' => 'Edit: ' . $employee->fullName,
            ],
        ];

        return view('employee.edit', $data);
    }

    public function update(Employee $employee, EmployeeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), $employee->dates);

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

        $employee->update($inputs);

        return redirect()->route('employee_list')->withSuccess('Employee updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-employee')) {
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
                        $model = Employee::find($id);

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

    public function toggleStatus(Employee $employee)
    {
        if (auth()->user()->isNotAllowTo('update-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $employee->disabled = !$employee->disabled;
        $employee->save();

        return redirect()->back()->with('success', 'Employee status has been toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-employee')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$employee = Employee::find($request->item_id)) {
            return redirect()->back()->with('error', 'Employee not found.');
        }

        $employee->delete();

        return redirect()->route('employee_list')->withSuccess('Employee deleted.');
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
                Employee::find($id)->update(array ('avatar' => null));
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

    public function ajaxDeleteSignature(Request $request)
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
                    if (Storage::disk('s3')->exists('public/media/signatures/' . $image)) {
                        Storage::disk('s3')->delete('public/media/signatures/' . $image);
                    }
                } else {                                                                    // in local public folder
                    $avatarsPath = public_path() . '/media/signatures/';
                    if (file_exists($avatarsPath . $image)) {
                        unlink($avatarsPath . $image);
                    }
                }
                Employee::find($id)->update(['signature' => null]);
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

}
