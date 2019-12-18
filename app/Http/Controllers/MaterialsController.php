<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Material;
use App\Http\Requests\MaterialRequest;
use App\Http\Requests\SearchRequest;

class MaterialsController extends FrontEndBaseController
{
    public function index(Request $request)
    {

        $perPage = $request->perPage ?? 50;

        $materials = Material::sortable()->paginate($perPage);

        $data = [
            'materials' => $materials,
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Materials',
            ],
        ];

        return view('material.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-material')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? 50;

        $materials = Material::search($needle)->sortable()->paginate($perPage);

        $data = [
            'materials' => $materials,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Materials- Search Results',
            ],
        ];

        return view('material.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-material')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Material',
            ],
        ];

        return view('material.create', $data);
    }

    public function store(MaterialRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-material')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Material::create($request->all());

        return redirect()->route('materials_list')->withSuccess('Material created.');
    }

    public function edit(Material $material)
    {
        if (auth()->user()->isNotAllowTo('update-material')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'material' => $material,
            'seo'       => [
                'pageTitle' => 'Edit Material',
            ],
        ];

        return view('material.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-material')) {
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
                        $model = Material::find($id);

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
        if (auth()->user()->isNotAllowTo('update-material')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = Material::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Material toggled.');
    }

    public function update(Material $material, MaterialRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-material.')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $material->update($inputs);

        return redirect()->route('materials_list')->withSuccess('Material updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-material.')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Material::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('materials_list')->withSuccess('Material deleted.');
    }


}
