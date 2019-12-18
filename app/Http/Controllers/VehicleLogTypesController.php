<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\VehicleLogType;
use App\Http\Requests\VehicleLogTypeRequest;
use App\Http\Requests\SearchRequest;

class VehicleLogTypesController extends FrontEndBaseController
{
    public function index(Request $request)
    {


        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $vehicleLogTypes = VehicleLogType::sortable()->paginate($perPage);

        $data = [
            'vehicleLogTypes' => $vehicleLogTypes,
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Vehicle Log Type',
            ],
        ];

        return view('vehicle_log_type.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $vehicleLogTypes = VehicleLogType::search($needle)->sortable()->paginate($perPage);

        $data = [
            'vehicleLogTypes' => $vehicleLogTypes,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Vehicle Log Type - Search Results',
            ],
        ];

        return view('vehicle_log_type.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Vehicle Log Type',
            ],
        ];

        return view('vehicle_log_type.create', $data);
    }

    public function store(VehicleLogTypeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        VehicleLogType::create($request->all());

        return redirect()->route('vehicle_log_type_list')->withSuccess('Vehicle log Type created.');
    }

    public function edit(VehicleLogType $vehicleLogType)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'vehicleLogType' => $vehicleLogType,
            'seo'       => [
                'pageTitle' => 'Edit Vehicle Log Type',
            ],
        ];

        return view('vehicle_log_type.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-log-type')) {
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
                        $model = VehicleLogType::find($id);

                        if (empty($value)) {  // make zero value null
                            $value = null;
                        }

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

    public function toggleStatus($id)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = VehicleLogType::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Vehicle Log Type status toggled.');
    }

    public function update(VehicleLogType $vehicleLogType, VehicleLogTypeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $vehicleLogType->update($inputs);

        return redirect()->route('vehicle_log_type_list')->withSuccess('Vehicle Log Type updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-vehicle-log-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = VehicleLogType::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('vehicle_log_type_list')->withSuccess('Vehicle Log Type deleted.');
    }


}
