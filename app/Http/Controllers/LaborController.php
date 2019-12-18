<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Labor;
use App\Http\Requests\LaborRequest;
use App\Http\Requests\SearchRequest;

class LaborController extends FrontEndBaseController
{
    public function index(Request $request)
    {


        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $labor = Labor::sortable()->paginate($perPage);

        $data = [
            'labor' => $labor,
            'countriesCB'                                => \App\Country::countriesCB(['0' => '']),
            'statesCB'                                   => \App\State::statesCB(231, ['0' => '']),


            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Labor Type',
            ],
        ];

        return view('labor.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $labor = Labor::search($needle)->sortable()->paginate($perPage);

        $data = [
            'labor' => $labor,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Labor Type - Search Results',
            ],
        ];

        return view('labor.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Labor Type',
            ],
        ];

        return view('labor.create', $data);
    }

    public function store(LaborRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Labor::create($request->all());

        return redirect()->route('labor_list')->withSuccess('Labor Type created.');
    }

    public function edit(Labor $labor)
    {
        if (auth()->user()->isNotAllowTo('update-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'labor' => $labor,
            'seo'       => [
                'pageTitle' => 'Edit Labor Type',
            ],
        ];

        return view('labor.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-labor')) {
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
                        $model = Labor::find($id);

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
        if (auth()->user()->isNotAllowTo('update-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = Labor::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Labor Type status toggled.');
    }

    public function update(Labor $labor, LaborRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $labor->update($inputs);

        return redirect()->route('labor_list')->withSuccess('Labor Type Updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-labor')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Labor::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('labor_list')->withSuccess('Labor Type Deleted.');
    }


}
