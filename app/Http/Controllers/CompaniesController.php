<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Company;
use App\Http\Requests\CompanyRequest;
use App\Http\Requests\SearchRequest;

class CompaniesController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $companies = Company::sortable()->paginate($perPage);

        $data = [
            'companies'                  => $companies,
            'needle'                     => null,
            'json_company_categories_cb' => json_encode(\App\CompanyCategory::categoriesCB(), JSON_FORCE_OBJECT),
            'seo'                        => [
                'pageTitle' => 'Companies',
            ],
        ];

        return view('company.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $companies = Company::search($needle)->sortable()->paginate($perPage);

        $data = [
            'companies'                  => $companies,
            'needle'                     => $needle,
            'json_company_categories_cb' => json_encode(\App\CompanyCategory::categoriesCB(), JSON_FORCE_OBJECT),
            'seo'                        => [
                'pageTitle' => 'Companies - Search Results',
            ],
        ];

        return view('company.index', $data);
    }

    public function show(Company $company)
    {
        if (auth()->user()->isNotAllowTo('show-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($company);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'companyCategoriesCB' => \App\CompanyCategory::categoriesCB(['0' => '']),
            'countriesCB'         => \App\Country::countriesCB(['0' => '']),
            'statesCB'            => \App\State::statesCB(231, ['0' => '']),
            'seo'                 => [
                'pageTitle' => 'New Company',
            ],
        ];

        return view('company.create', $data);
    }

    public function store(CompanyRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (!empty($inputs['above_as_billing_address'])) {
            $inputs['billing_address'] = $inputs['address'];
            $inputs['billing_address_2'] = $inputs['address_2'] ?? null;
            $inputs['billing_city'] = $inputs['city'] ?? null;
            $inputs['billing_zipcode'] = $inputs['zipcode'] ?? null;
            $inputs['billing_state_id'] = $inputs['state_id'] ?? null;
            $inputs['billing_country_id'] = $inputs['country_id'] ?? null;
        }

        $inputs['created_at'] = now(session()->get('timezone'));
        $inputs['created_by'] = auth()->user()->id;

        Company::create($inputs);

        return redirect()->route('company_list')->withSuccess('Company created.');
    }

    public function ajaxStore(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-company')) {
            $response = [
                'success' => false,
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];
        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'name'        => $request->new_company_name,
                    'category_id' => $request->new_company_category_id,
                    'address'     => $request->new_company_address,
                    'address_2'   => $request->new_company_address_2,
                    'city'        => $request->new_company_city,
                    'zipcode'     => $request->new_company_zipcode,
                    'country_id'  => $request->new_company_country_id,
                    'state_id'    => $request->new_company_state_id,
                ],
                [
                    'name'        => 'required|plainText',
                    'category_id' => 'required|positive',
                    'address'     => 'required|plainText',
                    'address_2'   => 'nullable|plainText',
                    'city'        => 'required|plainText',
                    'zipcode'     => 'required|plainText',
                    'country_id'  => 'required|positive',
                    'state_id'    => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    \DB::transaction(function () use ($request, & $company) {
                        $data = [
                            'name'               => $request->new_company_name,
                            'category_id'        => $request->new_company_category_id,
                            'address'            => $request->new_company_address,
                            'address_2'          => $request->new_company_address_2,
                            'city'               => $request->new_company_city,
                            'zipcode'            => $request->new_company_zipcode,
                            'country_id'         => $request->new_company_country_id,
                            'state_id'           => $request->new_company_state_id,
                            'billing_address'    => $request->new_company_address,
                            'billing_address_2'  => $request->new_company_address_2,
                            'billing_city'       => $request->new_company_city,
                            'billing_zipcode'    => $request->new_company_zipcode,
                            'billing_country_id' => $request->new_company_country_id,
                            'billing_state_id'   => $request->new_company_state_id,
                        ];

                        $company = Company::create($data);
                    });
                    $response = [
                        'success'           => true,
                        'company'           => $company,
                        'billing_states_cb' => !empty($company->billing_country_id) ? \App\State::statesCB($company->billing_country_id) : [],
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

    public function edit(Company $company)
    {
        if (auth()->user()->isNotAllowTo('update-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'company'             => $company,
            'companyCategoriesCB' => \App\CompanyCategory::categoriesCB(),
            'countriesCB'         => \App\Country::countriesCB(['0' => '']),
            'statesCB'            => \App\State::statesCB($company->country_id ?? 231, ['0' => '']),
            'seo'                 => [
                'pageTitle' => 'Edit Company',
            ],
        ];

        return view('company.edit', $data);
    }

    public function update(Company $company, CompanyRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $company->update($inputs);

        return redirect()->route('company_list')->withSuccess('Company updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-company')) {
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
                        $model = Company::find($id);

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

    public function toggleStatus(Company $company)
    {
        if (auth()->user()->isNotAllowTo('update-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Company status toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Company::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('company_list')->withSuccess('Company deleted.');
    }


}
