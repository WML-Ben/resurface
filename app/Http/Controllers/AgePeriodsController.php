<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\AgePeriod;
use App\Http\Requests\AgePeriodRequest;
use App\Http\Requests\SearchRequest;

class AgePeriodsController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $agePeriods = AgePeriod::sortable()->paginate($perPage);

        $data = [
            'agePeriods' => $agePeriods,
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Age Periods',
            ],
        ];

        return view('age_period.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $agePeriods = AgePeriod::search($needle)->sortable()->paginate($perPage);

        $data = [
            'agePeriods' => $agePeriods,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Categories - Search Results',
            ],
        ];

        return view('age_period.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Age Period',
            ],
        ];

        return view('age_period.create', $data);
    }

    public function store(AgePeriodRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        AgePeriod::create($request->all());

        return redirect()->route('age_period_list')->withSuccess('Age Period created.');
    }

    public function edit(AgePeriod $agePeriod)
    {
        if (auth()->user()->isNotAllowTo('update-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'agePeriod' => $agePeriod,
            'seo'       => [
                'pageTitle' => 'Edit Age Period',
            ],
        ];

        return view('age_period.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-age-period')) {
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
                        $model = AgePeriod::find($id);

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
        if (auth()->user()->isNotAllowTo('update-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege = AgePeriod::find($id);
        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Age Period status toggled.');
    }

    public function update(AgePeriod $agePeriod, AgePeriodRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['do_not_delete'])) {
            $inputs['do_not_delete'] = 0;
        }
        if (empty($inputs['wizard'])) {
            $inputs['wizard'] = 0;
        }
        if (empty($inputs['entity'])) {
            $inputs['entity'] = 0;
        }

        $agePeriod->update($inputs);

        return redirect()->route('age_period_list')->withSuccess('Age Period updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-age-period')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = AgePeriod::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('age_period_list')->withSuccess('Age Period deleted.');
    }


}
