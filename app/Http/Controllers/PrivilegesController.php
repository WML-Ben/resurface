<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Privilege;
use App\Http\Requests\PrivilegeRequest;
use App\Http\Requests\SearchRequest;

class PrivilegesController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-privilege')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $privileges = Privilege::sortable()->paginate($perPage);

        $data = [
            'privileges' => $privileges,
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Privileges',
            ],
        ];

        return view('privilege.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-privilege')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $privileges = Privilege::search($needle)->sortable()->paginate($perPage);

        $data = [
            'privileges' => $privileges,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Privileges - Search Results',
            ],
        ];

        return view('privilege.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-privilege')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Privilege',
            ],
        ];

        return view('privilege.create', $data);
    }

    public function store(PrivilegeRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-privilege')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $returnRoute = $request->from_role_form ? 'role_list' : 'privilege_list';

        if (empty($inputs['createCrud'])) {
            Privilege::create($request->all());

            return redirect()->route($returnRoute)->withSuccess('Privilege created.');
        } else {
            // create CRUD:
            $sufix = $inputs['name'];
            $totalCreated = 0;

            foreach ($this->_crud as $crud) {
                $privilegeName = $crud . '-' . $sufix;

                if (null === Privilege::where('name', $privilegeName)->first()) {
                    $inputs['name'] = $privilegeName;

                    Privilege::create($inputs);
                    $totalCreated++;
                }
            }

            if ($totalCreated > 0) {
                return redirect()->route($returnRoute)->with('success', 'CRUD set created for "' . $sufix . '".');
            } else {
                return redirect()->back()->with('error', 'CRUD set for "' . $sufix . '" already exists.');
            }
        }
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-privilege')) {
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
                        $model = Privilege::find($id);

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

    public function toggleStatus($id)
    {
        if (auth()->user()->isNotAllowTo('update-privilege')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = Privilege::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Privilege status toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-privilege')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Privilege::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('privilege_list')->withSuccess('Privilege deleted.');
    }


}
