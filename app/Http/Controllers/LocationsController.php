<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Location;
use App\Http\Requests\LocationRequest;
use App\Http\Requests\SearchRequest;

class LocationsController extends FrontEndBaseController
{
    public function index(Request $request)
    {


        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $location = Location::sortable()->paginate($perPage);

        $data = [
            'location' => $location,
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Location Type',
            ],
        ];

        return view('location.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $location = Location::search($needle)->sortable()->paginate($perPage);

        $data = [
            'location' => $location,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Location - Search Results',
            ],
        ];

        return view('location.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Location',
            ],
        ];

        return view('location.create', $data);
    }

    public function store(LocationRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Location::create($request->all());

        return redirect()->route('location_list')->withSuccess('Location created.');
    }

    public function edit(Location $location)
    {
        if (auth()->user()->isNotAllowTo('update-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'location' => $location,
            'seo'       => [
                'pageTitle' => 'Edit Location Type',
            ],
        ];

        return view('location.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-location')) {
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
                        $model = Location::find($id);

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
        if (auth()->user()->isNotAllowTo('update-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = Location::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Location status toggled.');
    }

    public function update(Location $location, LocationRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $location->update($inputs);

        return redirect()->route('location_list')->withSuccess('Location Updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-location')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Location::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('location_list')->withSuccess('Location Deleted.');
    }


}
