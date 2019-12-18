<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Appointment;
use App\Http\Requests\CalendarEventRequest;
use App\Http\Requests\SearchRequest;
class AppointmentsController extends FrontEndBaseController
{
    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $appointments = Appointment::basedOnRole()->sortable('started_at')->with(['property' => function ($q) {
            $q->with(['owner', 'company', 'manager']);
        }])->paginate($perPage);

        $data = [
            'appointments' => $appointments,
            'needle'       => null,
            'seo'          => [
                'pageTitle' => 'Appointments',
            ],
        ];

        return view('appointment.index', $data);
    }

    public function search(SearchRequest $request)
    {
        if (auth()->user()->isNotAllowTo('search-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $appointments = Appointment::basedOnRole()->search($needle)->sortable('started_at')->with(['property' => function ($q) {
            $q->with(['owner', 'company', 'manager']);
        }])->paginate($perPage);

        $data = [
            'appointments' => $appointments,
            'needle'       => $needle,
            'seo'          => [
                'pageTitle' => 'Appointments - Search Results',
            ],
        ];

        return view('appointment.index', $data);
    }

    public function show(Appointment $appointment)
    {
        if (auth()->user()->isNotAllowTo('show-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($appointment);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'appointmentCategoriesCB' => \App\AppointmentCategory::categoriesCB(['0' => '']),
            'countriesCB'             => \App\Country::countriesCB(['0' => '']),
            'statesCB'                => \App\State::statesCB(231, ['0' => '']),
            'seo'                     => [
                'pageTitle' => 'New Appointment',
            ],
        ];

        return view('appointment.create', $data);
    }

    public function store(CalendarEventRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-appointment')) {
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

        Appointment::create($inputs);

        return redirect()->route('appointment_list')->withSuccess('Appointment created.');
    }

    public function ajaxStore(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-appointment')) {
            $response = [
                'success' => false,
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];
        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'name'        => $request->new_appointment_name,
                    'category_id' => $request->new_appointment_category_id,
                    'address'     => $request->new_appointment_address,
                    'address_2'   => $request->new_appointment_address_2,
                    'city'        => $request->new_appointment_city,
                    'zipcode'     => $request->new_appointment_zipcode,
                    'country_id'  => $request->new_appointment_country_id,
                    'state_id'    => $request->new_appointment_state_id,
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
                    \DB::transaction(function () use ($request, & $appointment) {
                        $data = [
                            'name'               => $request->new_appointment_name,
                            'category_id'        => $request->new_appointment_category_id,
                            'address'            => $request->new_appointment_address,
                            'address_2'          => $request->new_appointment_address_2,
                            'city'               => $request->new_appointment_city,
                            'zipcode'            => $request->new_appointment_zipcode,
                            'country_id'         => $request->new_appointment_country_id,
                            'state_id'           => $request->new_appointment_state_id,
                            'billing_address'    => $request->new_appointment_address,
                            'billing_address_2'  => $request->new_appointment_address_2,
                            'billing_city'       => $request->new_appointment_city,
                            'billing_zipcode'    => $request->new_appointment_zipcode,
                            'billing_country_id' => $request->new_appointment_country_id,
                            'billing_state_id'   => $request->new_appointment_state_id,
                        ];

                        $appointment = Appointment::create($data);
                    });
                    $response = [
                        'success'           => true,
                        'appointment'       => $appointment,
                        'billing_states_cb' => !empty($appointment->billing_country_id) ? \App\State::statesCB($appointment->billing_country_id) : [],
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

    public function edit(Appointment $appointment)
    {
        if (auth()->user()->isNotAllowTo('update-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'appointment'             => $appointment,
            'appointmentCategoriesCB' => \App\AppointmentCategory::categoriesCB(),
            'countriesCB'             => \App\Country::countriesCB(['0' => '']),
            'statesCB'                => \App\State::statesCB($appointment->country_id ?? 231, ['0' => '']),
            'seo'                     => [
                'pageTitle' => 'Edit Appointment',
            ],
        ];

        return view('appointment.edit', $data);
    }

    public function update(Appointment $appointment, CalendarEventRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        $appointment->update($inputs);

        return redirect()->route('appointment_list')->withSuccess('Appointment updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-appointment')) {
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
                        $model = Appointment::find($id);

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

    public function toggleStatus(Appointment $appointment)
    {
        if (auth()->user()->isNotAllowTo('update-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $privilege->disabled = !$privilege->disabled;
        $privilege->save();

        return redirect()->back()->with('success', 'Appointment status toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-appointment')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$entry = Appointment::find($request->item_id)) {
            return redirect()->back()->with('error', 'Item not found.');
        }

        $entry->delete();

        return redirect()->route('appointment_list')->withSuccess('Appointment deleted.');
    }


}
