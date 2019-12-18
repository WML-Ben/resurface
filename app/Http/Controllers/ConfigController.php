<?php namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Requests\ConfigRequest;
use App\Http\Requests\SearchRequest;

use App\Config;

class ConfigController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-config')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $config = Config::noSystem()->sortable()->paginate($perPage);

        $data = [
            'conf'   => $config,
            'needle' => null,
            'seo'    => [
                'pageTitle' => 'System Configuration',
            ],
        ];

        return view('config.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-config')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $config = Config::search($needle)->noSystem()->sortable()->paginate($perPage);

        $data = [
            'conf'   => $config,
            'needle' => $needle,
            'seo'    => [
                'pageTitle' => 'System Configuration - Search Results',
            ],
        ];

        return view('config.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-config')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'seo' => [
                'pageTitle' => 'New System Configuration',
            ],
        ];

        return view('config.create', $data);
    }

    public function store(ConfigRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-config')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Config::create($request->all());

        return redirect()->route('config_list')->with('success', 'Item created.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-config')) {
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
                        $model = Config::find($id);

                        if (!empty($relation)) {
                            $model->{$relation}->update([$name => $value]);
                        } else {
                            $model->{$name} = $value;
                            $model->save();
                        }

                        Config::reload();
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
        if (auth()->user()->isNotAllowTo('update-config')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $config = Config::find($id);
        $config->disabled = !$config->disabled;
        $config->save();

        return redirect()->back()->with('success', 'Item status toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-config')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (empty($request->item_id)) {
            return redirect()->back()->with('error', 'There is no item selected.');
        }

        Config::destroy($request->item_id);

        return redirect()->route('config_list')->withSuccess('Item deleted.');
    }

}
