<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\CompanyCategory;
use App\Http\Requests\CompanyCategoryRequest;
use App\Http\Requests\SearchRequest;

class CompanyCategoriesController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $companyCategories = CompanyCategory::sortable()->paginate($perPage);

        $data = [
            'companyCategories' => $companyCategories,
            'needle'            => null,
            'seo'               => [
                'pageTitle' => 'Company Categories',
            ],
        ];

        return view('company_category.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $companyCategories = CompanyCategory::search($needle)->sortable()->paginate($perPage);

        $data = [
            'companyCategories' => $companyCategories,
            'needle'            => $needle,
            'seo'               => [
                'pageTitle' => 'Company Categories - Search Results',
            ],
        ];

        return view('company_category.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Company Category',
            ],
        ];

        return view('company_category.create', $data);
    }

    public function store(CompanyCategoryRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        CompanyCategory::create($request->all());

        return redirect()->route('company_category_list')->withSuccess('Company Category created.');
    }

    public function edit(CompanyCategory $companyCategory)
    {
        if (auth()->user()->isNotAllowTo('update-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'companyCategory' => $companyCategory,
            'seo'             => [
                'pageTitle' => 'Edit Company Category',
            ],
        ];

        return view('company_category.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-company-category')) {
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
                        $model = CompanyCategory::find($id);

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

    public function update(CompanyCategory $companyCategory, CompanyCategoryRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $companyCategory->update($inputs);

        return redirect()->route('company_category_list')->withSuccess('Company Category updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-company-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = CompanyCategory::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('company_category_list')->withSuccess('Company Category deleted.');
    }


}
