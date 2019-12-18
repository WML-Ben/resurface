<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Property;
use App\Http\Requests\PropertyRequest;
use App\Http\Requests\SearchRequest;

class PropertiesController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $properties = Property::sortable()->paginate($perPage);

        $data = [
            'properties' => $properties,
            'needle'     => null,
            'seo'        => [
                'pageTitle' => 'Properties',
            ],
        ];

        return view('property.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $properties = Property::search($needle)->sortable()->paginate($perPage);

        $data = [
            'properties' => $properties,
            'needle'     => $needle,
            'seo'        => [
                'pageTitle' => 'Properties - Search Results',
            ],
        ];

        return view('property.index', $data);
    }

    public function show(Property $property)
    {
        if (auth()->user()->isNotAllowTo('show-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($property);
    }

    public function create(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        /*$json_html_property_management_companies_cb = \Cache::remember('json_html_property_management_companies_cb', 60 * 12, function () {
            return \App\Company::jsonHtmlCompaniesCB();
        });

        $json_html_property_owners_cb = \Cache::remember('json_html_property_owners_cb', 60 * 12, function () {
            return Property::jsonHtmlPropertyOwnersCB();
        });

        $json_html_contacts_cb = \Cache::remember('json_html_contacts_cb', 60 * 12, function () {
            return \App\ContactNew::jsonHtmlContactsCB();
        });*/
        $data = [
            'json_html_property_management_companies_cb' => \App\Company::jsonHtmlCompaniesCB(),
            'json_html_property_owners_cb'               => Property::jsonHtmlPropertyOwnersCB(),
            'json_html_contacts_cb'                      => \App\ContactNew::jsonHtmlContactsCB(),
            'managersCB'                                 => [],
            'countriesCB'                                => \App\Country::countriesCB([]),
            'statesCB'                                   => \App\State::statesCB(231, []),
            //'companyCategoriesCB'                        => \App\CompanyCategory::categoriesCB(['0' => '']),
            //'contactCategoriesCB'                        => \App\UserCategory::categoriesCB(['0' => '']),
            'returnTo'                                   => $request->returnTo,
            'seo'                                        => [
                'pageTitle' => 'New Property',
            ],
        ];

        return view('property.create', $data);
    }

    public function store(PropertyRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $property = Property::create($inputs);

        if (!empty($request->returnTo)) {
            return redirect()->route($request->returnTo, ['property_id' => $property->id])->withSuccess('Property created.');
        } else {
            return redirect()->route('property_list')->withSuccess('Property created.');
        }
    }

    public function ajaxNewOwnerStore(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-contact')) {
            $response = [
                'success' => false,
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];
        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'first_name'  => $request->new_owner_first_name,
                    'last_name'   => $request->new_owner_last_name,
                    'email'       => $request->new_owner_email,
                    'phone'       => $request->new_owner_phone,
                    'category_id' => $request->new_owner_category_id,
                ],
                [
                    'first_name'  => 'required|personName',
                    'last_name'   => 'required|personName',
                    'email'       => 'nullable|email',
                    'phone'       => 'required|phone',
                    'category_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    \DB::transaction(function () use ($request, & $owner) {
                        $data = [
                            'first_name'  => $request->new_owner_first_name,
                            'last_name'   => $request->new_owner_last_name,
                            'email'       => $request->new_owner_email,
                            'phone'       => $request->new_owner_phone,
                            'category_id' => $request->new_owner_category_id,
                        ];

                        $owner = \App\Contact::create($data);
                    });
                    $response = [
                        'success' => true,
                        'owner'   => $owner,
                    ];
                } catch (\Exception $e) {
                    $response = [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function edit(Property $property)
    {
        if (auth()->user()->isNotAllowTo('update-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $json_html_property_owners_cb = \Cache::remember('json_html_property_owners_cb', 60 * 12, function () {
            return Property::jsonHtmlPropertyOwnersCB();
        });

        $data = [
            'property'                                   => $property,
            'json_html_property_management_companies_cb' => \App\Company::jsonHtmlCompaniesCB(),
            'json_html_property_owners_cb'               => $json_html_property_owners_cb,
            'json_html_contacts_cb'  => \App\ContactNew::jsonHtmlContactsCB(['0'=>'']),
            'managersCB'                                 => !empty($property->company) ? $property->company->getUsersCB() : [],
            'countriesCB'                                => \App\Country::countriesCB(['0' => '']),
            'statesCB'                                   => \App\State::statesCB($property->country_id ?? 231, ['0' => '']),
            'seo'                                        => [
                'pageTitle' => 'Edit Property',
            ],
        ];

        return view('property.edit', $data);
    }

    public function update(Property $property, PropertyRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $property->update($inputs);

        return redirect()->route('property_list')->withSuccess('Property updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-property')) {
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
                        $model = Property::find($id);

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

    public function toggleStatus(Property $property)
    {
        if (auth()->user()->isNotAllowTo('update-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Property status toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-property')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Property::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('property_list')->withSuccess('Property deleted.');
    }


}
