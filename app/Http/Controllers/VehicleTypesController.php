<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\VehicleType;
use App\Http\Requests\VehicleTypeRequest;

use StringTrait;

class VehicleTypesController extends FrontEndBaseController
{
    use StringTrait;

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $showAll = $request->show_all ?? 0;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $vehicleTypes = VehicleType::sortable()->paginate($perPage);

        $data = [
            'vehicleTypes' => $vehicleTypes,
            'needle'       => null,
            'showAll'      => $showAll,
            'seo'          => [
                'pageTitle' => 'Vehicle Types',
            ],
        ];

        return view('vehicle_type.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $showAll = $request->show_all ?? 0;

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $vehicleTypes = VehicleType::search($needle)->sortable()->paginate($perPage);

        $data = [
            'vehicleTypes' => $vehicleTypes,
            'needle'       => $needle,
            'showAll'      => $showAll,
            'seo'          => [
                'pageTitle' => 'Vehicle Types',
            ],
        ];

        return view('vehicle_type.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Vehicle Type',
            ],
        ];

        return view('vehicle_type.create', $data);
    }

    public function store(VehicleTypeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), (new VehicleType())->dates);

        VehicleType::create($inputs);

        return redirect()->route('vehicle_type_list')->with('success', 'Vehicle Type created.');
    }

    public function show(VehicleType $vehicleType)
    {
        if (auth()->user()->isNotAllowTo('show-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($vehicleType);
    }

    public function edit(VehicleType $vehicleType)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (empty($vehicleType->id) ) {
            return redirect()->back()->with('error', 'Vehicle Type not found.');
        }

        $data = [
            'vehicleType' => $vehicleType,
            'seo'         => [
                'pageTitle' => 'Edit: ' . $vehicleType->Name,
            ],
        ];

        return view('vehicle_type.edit', $data);
    }

    public function update(VehicleType $vehicleType, VehicleTypeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), $vehicleType->dates);

        $vehicleType->update($inputs);

        return redirect()->route('vehicle_type_list')->withSuccess('Vehicle Type updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle-type')) {
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
                        $model = VehicleType::find($id);

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

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-vehicle-type')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$vehicleType = VehicleType::find($request->item_id)) {
            return redirect()->back()->with('error', 'vehicle type not found.');
        }

        $vehicleType->delete();

        return redirect()->route('vehicle_type_list')->withSuccess('Vehicle Type deleted.');
    }


}
