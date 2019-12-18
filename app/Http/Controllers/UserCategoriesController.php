<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\UserCategory;
use App\Http\Requests\UserCategoryRequest;
use App\Http\Requests\SearchRequest;

class UserCategoriesController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $userCategories = UserCategory::sortable()->paginate($perPage);

        $data = [
            'userCategories' => $userCategories,
            'needle'         => null,
            'seo'            => [
                'pageTitle' => 'Contact Categories',
            ],
        ];

        return view('user_category.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $userCategories = UserCategory::search($needle)->sortable()->paginate($perPage);

        $data = [
            'userCategories' => $userCategories,
            'needle'         => $needle,
            'seo'            => [
                'pageTitle' => 'Contact Categories - Search Results',
            ],
        ];

        return view('user_category.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New Contact Category',
            ],
        ];

        return view('user_category.create', $data);
    }

    public function store(UserCategoryRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        UserCategory::create($request->all());

        return redirect()->route('user_category_list')->withSuccess('Contact Category created.');
    }

    public function edit(UserCategory $userCategory)
    {
        if (auth()->user()->isNotAllowTo('update-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'userCategory' => $userCategory,
            'seo'          => [
                'pageTitle' => 'Edit Contact Category',
            ],
        ];

        return view('user_category.edit', $data);
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-user-category')) {
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
                        $model = UserCategory::find($id);

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

    public function update(UserCategory $userCategory, UserCategoryRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $userCategory->update($inputs);

        return redirect()->route('user_category_list')->withSuccess('Contact Category updated.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-user-category')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = UserCategory::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('user_category_list')->withSuccess('Contact Category deleted.');
    }


}
