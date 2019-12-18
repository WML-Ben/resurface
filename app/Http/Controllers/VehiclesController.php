<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vehicle;
use App\Http\Requests\VehicleRequest;

use StringTrait;

class VehiclesController extends FrontEndBaseController
{
    use StringTrait;

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $showAll = $request->show_all ?? 0;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $vehicles = Vehicle::showAllFilter($showAll)->sortable()->paginate($perPage);

        $data = [
            'vehicles'              => $vehicles,
            'json_locations_cb'     => json_encode(\App\Location::locationsCB(), JSON_FORCE_OBJECT),
            'json_vehicle_types_cb' => json_encode(\App\VehicleType::typesCB(), JSON_FORCE_OBJECT),
            'needle'                => null,
            'showAll'               => $showAll,
            'seo'                   => [
                'pageTitle' => 'Vehicles',
            ],
        ];

        return view('vehicle.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $showAll = $request->show_all ?? 0;

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $vehicles = Vehicle::showAllFilter($showAll)->search($needle)->sortable()->paginate($perPage);

        $data = [
            'vehicles'              => $vehicles,
            'needle'                => $needle,
            'json_locations_cb'     => json_encode(\App\Location::locationsCB(), JSON_FORCE_OBJECT),
            'json_vehicle_types_cb' => json_encode(\App\VehicleType::typesCB(), JSON_FORCE_OBJECT),
            'showAll'               => $showAll,
            'seo'                   => [
                'pageTitle' => 'Vehicles',
            ],
        ];

        return view('vehicle.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'locations_cb'     => \App\Location::locationsCB(['0' => '']),
            'vehicle_types_cb' => \App\VehicleType::typesCB(['0' => '']),
            'seo'              => [
                'pageTitle' => 'New vehicle',
            ],
        ];

        return view('vehicle.create', $data);
    }

    public function store(VehicleRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), (new Vehicle())->dates);

        //default new vehicle to active

        $inputs['disabled'] = false;

        Vehicle::create($inputs);

        return redirect()->route('vehicle_list')->with('success', 'Vehicle created.');
    }

    public function show(Vehicle $vehicle)
    {
        if (auth()->user()->isNotAllowTo('show-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($vehicle);
    }

    public function edit(Vehicle $vehicle)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$vehicle) {
            return redirect()->back()->with('error', 'vehicle not found.');
        }

        $data = [
            'vehicle'          => $vehicle,
            'locations_cb'     => \App\Location::locationsCB(['0' => '']),
            'vehicle_types_cb' => \App\VehicleType::typesCB(['0' => '']),
            'seo'              => [
                'pageTitle' => 'Edit: ' . $vehicle->Name,
            ],
        ];

        return view('vehicle.edit', $data);
    }

    public function update(Vehicle $vehicle, VehicleRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), $vehicle->dates);

        if (empty($inputs['disabled'])) {
            $inputs['disabled'] = 0;
        }

        $vehicle->update($inputs);

        return redirect()->route('vehicle_list')->withSuccess('Vehicle updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle')) {
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
                        $model = Vehicle::find($id);

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

    public function toggleStatus(Vehicle $vehicle)
    {
        if (auth()->user()->isNotAllowTo('update-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $vehicle->disabled = !$vehicle->disabled;

        $vehicle->save();

        return redirect()->back()->with('success', 'Vehicle status has been toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-vehicle')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$vehicle = Vehicle::find($request->item_id)) {
            return redirect()->back()->with('error', 'vehicle not found.');
        }

        $vehicle->delete();

        return redirect()->route('vehicle_list')->withSuccess('Vehicle deleted.');
    }


}
