<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Equipment;
use App\Http\Requests\EquipmentRequest;
use App\Http\Requests\SearchRequest;

class EquipmentsController extends FrontEndBaseController
{
    public function index(Request $request)
    {

        $perPage = $request->perPage ?? 50;

        $equipments = Equipment::sortable()->paginate($perPage);

        $data = [
            'equipments' => $equipments,
            'json_ratetypes_cb'     => json_encode(\App\EquipmentRateType::typesCB(), JSON_FORCE_OBJECT),
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Equipment',
            ],
        ];

        return view('equipment.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-equipment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? 50;

        $equipments = Equipment::search($needle)->sortable()->paginate($perPage);

        $data = [
            'equipments' => $equipments,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Equipment- Search Results',
            ],
        ];

        return view('equipment.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-equipment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'types_cb'     => \App\EquipmentRateType::typesCB(),
            'seo' => [
                'pageTitle' => 'New equipment',
            ],

        ];

        return view('equipment.create', $data);
    }

    public function store(EquipmentRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-equipment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Equipment::create($request->all());

        return redirect()->route('equipments_list')->withSuccess('Equipment created.');
    }

    public function edit(Equipment $equipment)
    {
        if (auth()->user()->isNotAllowTo('update-equipment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'equipment' => $equipment,
            'seo'       => [
                'pageTitle' => 'Edit Equipment',
            ],
        ];

        return view('equipment.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-equipment')) {
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
                        $model = Equipment::find($id);

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
        if (auth()->user()->isNotAllowTo('update-equipment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = Equipment::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'equipment toggled.');
    }

    public function update(Equipment $equipment, EquipmentRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-equipment.')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $equipment->update($inputs);

        return redirect()->route('equipments_list')->withSuccess('Equipment updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-equipment.')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Equipment::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('equipments_list')->withSuccess('Equipment deleted.');
    }


}
