<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Proposal;
use App\OrderService;
use App\AgePeriod;
use ImageTrait;
use StringTrait;

class ProposalsController extends FrontEndBaseController
{
    use ImageTrait, StringTrait;


    public function __construct()
    {
        parent::__construct();

        ini_set('max_execution_time', 300);
    }

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $allStatus = auth()->user()->hasPrivilege('list-all-status-proposal');

        $statusId = 1;

        if ($allStatus) {
            $statusId = $request->statusId ?? $statusId;
        }

        $agingId = $request->agingId ?? 0;
        $startId = $request->startId ?? 0;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $proposals = Proposal::basedOnRole()
            ->statusFilter($statusId)
            ->agingFilter($agingId)
            ->startFilter($startId)
            ->with(['status', 'property', 'company', 'manager', 'salesManager', 'salesPerson', 'createdBy', 'updatedBy'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'proposals' => $proposals,
            'statusCB'  => $allStatus ? \App\OrderStatus::proposalStatusCB(['0' => 'All Status']) : null,
            'statusId'  => $allStatus ? $statusId : null,
            'agingCB'   => \App\AgePeriod::agePeriodsCB(['0' => 'All aging']),
            'agingId'   => $agingId,
            //'startsCB'               => \App\OrderStart::startsCB(['0' => 'All Starts']),
            'startId'   => $startId,
            //'json_starts_cb'         => json_encode(\App\OrderStart::startsCB(), JSON_FORCE_OBJECT),
            //'json_sales_managers_cb' => json_encode(\App\Employee::salesManagersCB(['0' => '']), JSON_FORCE_OBJECT),
            //'json_sales_persons_cb'  => json_encode(\App\Employee::salesPersonsCB(['0' => '']), JSON_FORCE_OBJECT),
            'needle'    => null,
            'seo'       => [
                'pageTitle' => 'Proposals',
            ],
        ];

        return view('proposal.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $allStatus = auth()->user()->hasPrivilege('list-all-status-proposal');

        $statusId = 1;

        if ($allStatus) {
            $statusId = $request->statusId ?? $statusId;
        }

        $startId = $request->startId ?? 0;
        $agingId = $request->agingId ?? 0;
        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $proposals = Proposal::basedOnRole()
            ->search($needle)
            ->statusFilter($statusId)
            ->startFilter($startId)
            ->with(['status', 'property', 'company', 'manager', 'salesManager', 'salesPerson', 'createdBy', 'updatedBy'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'proposals' => $proposals,
            'statusCB'  => $allStatus ? \App\OrderStatus::proposalStatusCB(['0' => 'All Status']) : null,
            'statusId'  => $allStatus ? $statusId : null,
            'agingCB'   => \App\AgePeriod::agePeriodsCB(['0' => 'All aging']),
            'agingId'   => $agingId,
            //'startsCB'               => \App\OrderStart::startsCB(['0' => 'All Starts']),
            'startId'   => $startId,
            //'json_starts_cb'         => json_encode(\App\OrderStart::startsCB(), JSON_FORCE_OBJECT),
            //'json_sales_managers_cb' => json_encode(\App\Employee::salesManagersCB(['0' => '']), JSON_FORCE_OBJECT),
            //'json_sales_persons_cb'  => json_encode(\App\Employee::salesPersonsCB(['0' => '']), JSON_FORCE_OBJECT),
            'needle'    => $needle,
            'seo'       => [
                'pageTitle' => 'Proposals',
            ],
        ];

        return view('proposal.index', $data);
    }

    public function draft(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $agingId = $request->agingId ?? 0;
        $startId = $request->startId ?? 0;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $proposals = Proposal::basedOnRole()
            ->proposalDraft()
            ->agingFilter($agingId)
            ->startFilter($startId)
            ->with(['status', 'property', 'company', 'manager', 'salesManager', 'salesPerson', 'createdBy', 'updatedBy'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'proposals' => $proposals,
            'agingCB'   => \App\AgePeriod::agePeriodsCB(['0' => 'All aging']),
            'agingId'   => $agingId,
            'startId'   => $startId,
            'needle'    => null,
            'seo'       => [
                'pageTitle' => 'Draft Proposals',
            ],
        ];

        return view('proposal.draft', $data);
    }

    public function draftSearch(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $startId = $request->startId ?? 0;
        $agingId = $request->agingId ?? 0;
        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $proposals = Proposal::basedOnRole()
            ->proposalDraft()
            ->search($needle)
            ->startFilter($startId)
            ->with(['status', 'property', 'company', 'manager', 'salesManager', 'salesPerson', 'createdBy', 'updatedBy'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'proposals' => $proposals,
            'agingCB'   => \App\AgePeriod::agePeriodsCB(['0' => 'All aging']),
            'agingId'   => $agingId,
            'startId'   => $startId,
            'needle'    => $needle,
            'seo'       => [
                'pageTitle' => 'Draft Proposals',
            ],
        ];

        return view('proposal.draft', $data);
    }

    public function pending(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $agingId = $request->agingId ?? 0;
        $startId = $request->startId ?? 0;

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $proposals = Proposal::basedOnRole()
            ->proposalPending()
            ->agingFilter($agingId)
            ->startFilter($startId)
            ->with(['status', 'property', 'company', 'manager', 'salesManager', 'salesPerson', 'createdBy', 'updatedBy'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'proposals' => $proposals,
            'agingCB'   => \App\AgePeriod::agePeriodsCB(['0' => 'All aging']),
            'agingId'   => $agingId,
            'startId'   => $startId,
            'needle'    => null,
            'seo'       => [
                'pageTitle' => 'Pending Proposals',
            ],
        ];

        return view('proposal.pending', $data);
    }

    public function pendingSearch(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $startId = $request->startId ?? 0;
        $agingId = $request->agingId ?? 0;
        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $proposals = Proposal::basedOnRole()
            ->proposalPending()
            ->search($needle)
            ->startFilter($startId)
            ->with(['status', 'property', 'company', 'manager', 'salesManager', 'salesPerson', 'createdBy', 'updatedBy'])
            ->sortable()
            ->paginate($perPage);

        $data = [
            'proposals' => $proposals,
            'agingCB'   => \App\AgePeriod::agePeriodsCB(['0' => 'All aging']),
            'agingId'   => $agingId,
            'startId'   => $startId,
            'needle'    => $needle,
            'seo'       => [
                'pageTitle' => 'Pending Proposals',
            ],
        ];

        return view('proposal.pending', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'salesManagersCB' => \App\Employee::salesManagersCB(['0' => '']),
            'salesPersonsCB'  => \App\Employee::salesPersonsCB(['0' => '']),
            'seo'             => [
                'pageTitle' => 'New Proposal',
            ],
        ];

        return view('proposal.create', $data);
    }

    public function store(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        Proposal::create($request->all());

        return redirect()->route('proposal_list')->with('success', 'Proposal created.');
    }

    public function createIntakeForm(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        //$request->property_id = 2501;

        $property = !empty($request->property_id) ? \App\Property::with(['company'])->find($request->property_id) : null;

        // retrieve from cache if exist, otherwise, call methid and cache it for future requests:

        $json_html_properties_cb = \Cache::remember('json_html_properties_cb', 60 * 12, function () {
            return \App\Property::jsonHtmlPropertiesCB();
        });

        $json_html_property_management_companies_cb = \Cache::remember('json_html_property_management_companies_cb', 60 * 12, function () {
            return \App\Company::jsonHtmlPropertyManagementCompaniesCB();
        });

        $json_html_contacts_cb = \Cache::remember('json_html_contacts_cb', 60 * 12, function () {
            return \App\Contact::jsonHtmlContactsCB();
        });

        $data = [
            'property'                                   => $property,
            'json_html_properties_cb'                    => $json_html_properties_cb,
            'json_html_property_management_companies_cb' => $json_html_property_management_companies_cb,
            'json_html_contacts_cb'                      => $json_html_contacts_cb,
            'managersCB'                                 => !empty($property) ? $property->company->getUsersCB(['0' => '']) : [],
            'countriesCB'                                => \App\Country::countriesCB(['0' => '']),
            'statesCB'                                   => !empty($property) ? \App\State::statesCB($property->country_id ?? 231, ['0' => '']) : \App\State::statesCB(231, ['0' => '']),
            'salesManagersCB'                            => \App\Employee::salesManagersCB(['0' => '']),
            'salesPersonsCB'                             => \App\Employee::salesPersonsCB(['0' => '']),
            'companyCategoriesCB'                        => \App\CompanyCategory::categoriesCB(['0' => '']),
            'contactCategoriesCB'                        => \App\UserCategory::categoriesCB(['0' => '']),
            'serviceCategoriesCB'                        => \App\ServiceCategory::categoriesCB(null, [10]),
            'seo'                                        => [
                'pageTitle' => 'Intake Form',
            ],
        ];

        return view('proposal.intake_form', $data);
    }

    public function storeIntakeForm(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $validator = \Validator::make(
            [
                'property_id'               => $request->property_id,
                'owner_id'                  => $request->owner_id,
                'address'                   => $request->address,
                'address_2'                 => $request->address_2,
                'city'                      => $request->city,
                'zipcode'                   => $request->zipcode,
                'country_id'                => $request->country_id,
                'state_id'                  => $request->state_id,
                'company_id'                => $request->company_id,
                'manager_id'                => $request->manager_id,
                'parcel_number'             => $request->parcel_number,
                'billing_address'           => $request->billing_address,
                'billing_address_2'         => $request->billing_address_2,
                'billing_city'              => $request->billing_city,
                'billing_zipcode'           => $request->billing_zipcode,
                'billing_country_id'        => $request->billing_country_id,
                'billing_state_id'          => $request->billing_state_id,
                'name'                      => $request->name,
                'email'                     => $request->email,
                'phone'                     => $request->phone,
                'sales_manager_id'          => $request->sales_manager_id,
                'sales_person_id'           => $request->sales_person_id,
                'event_started_at'          => $request->event_started_at,
                'event_ended_at'            => $request->event_ended_at,
                'event_name'                => $request->event_name,
                'event_description'         => $request->event_description,
                'service_category_ids'      => $request->service_category_ids,
                'how_did_you_hear_about_us' => $request->how_did_you_hear_about_us,
                'referring_person'          => $request->referring_person,
            ],
            [
                'property_id'               => 'required|positive',
                'owner_id'                  => 'nullable|zeroOrPositive',
                'address'                   => 'nullable|plainText',
                'address_2'                 => 'nullable|plainText',
                'city'                      => 'nullable|plainText',
                'zipcode'                   => 'nullable|plainText',
                'country_id'                => 'nullable|zeroOrPositive',
                'state_id'                  => 'nullable|zeroOrPositive',
                'company_id'                => 'nullable|zeroOrPositive',
                'manager_id'                => 'nullable|zeroOrPositive',
                'parcel_number'             => 'nullable|plainText',
                'billing_address'           => 'nullable|plainText',
                'billing_address_2'         => 'nullable|plainText',
                'billing_city'              => 'nullable|plainText',
                'billing_zipcode'           => 'nullable|plainText',
                'billing_country_id'        => 'nullable|zeroOrPositive',
                'billing_state_id'          => 'nullable|zeroOrPositive',
                'name'                      => 'nullable|plainText',
                'email'                     => 'nullable|email',
                'phone'                     => 'nullable|phone',
                'sales_manager_id'          => 'nullable|zeroOrPositive',
                'sales_person_id'           => 'nullable|zeroOrPositive',
                'event_started_at'          => 'nullable|usDateTime',
                'event_ended_at'            => 'nullable|usDateTime',
                'event_name'                => 'nullable|plainText',
                'event_description'         => 'nullable|plainText',
                'service_category_ids.*'    => 'nullable|positive',
                'how_did_you_hear_about_us' => 'nullable|plainText',
                'referring_person'          => 'nullable|personName',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withError($validator->messages()->first());
        }

        try {
            \DB::transaction(function () use ($request, &$proposal) {

                // update property:

                if ($property = \App\Property::find($request->property_id)) {
                    $data = [
                        'property_id'   => $request->property_id,
                        'owner_id'      => $request->owner_id,
                        'address'       => $request->address,
                        'address_2'     => $request->address_2,
                        'city'          => $request->city,
                        'zipcode'       => $request->zipcode,
                        'country_id'    => $request->country_id,
                        'state_id'      => $request->state_id,
                        'company_id'    => $request->company_id,
                        'manager_id'    => $request->manager_id,
                        'parcel_number' => $request->parcel_number,
                    ];

                    $property->update($data);
                }

                // update company:

                if ($company = \App\Company::find($request->company_id)) {
                    $data = [
                        'billing_address'    => $request->billing_address,
                        'billing_address_2'  => $request->billing_address_2,
                        'billing_city'       => $request->billing_city,
                        'billing_zipcode'    => $request->billing_zipcode,
                        'billing_country_id' => $request->billing_country_id,
                        'billing_state_id'   => $request->billing_state_id,
                    ];

                    $company->update($data);
                }

                // create proposal:

                if (!empty($request->service_category_ids)) {
                    $services = \App\ServiceCategory::whereIn('id', $request->service_category_ids)->orderBy('name')->pluck('name')->toArray();
                    $servicesStr = implode(',', $services);
                } else {
                    $servicesStr = null;
                }

                $proposal = new Proposal;

                $proposal->status_id = 10;     // draft
                $proposal->company_id = !empty($request->company_id) ? $request->company_id : null;
                $proposal->property_id = !empty($request->property_id) ? $request->property_id : null;
                $proposal->manager_id = !empty($request->manager_id) ? $request->manager_id : null;
                $proposal->sales_manager_id = !empty($request->sales_manager_id) ? $request->sales_manager_id : null;
                $proposal->sales_person_id = !empty($request->sales_person_id) ? $request->sales_person_id : null;
                $proposal->address = $request->address;
                $proposal->address_2 = $request->address_2;
                $proposal->city = $request->city;
                $proposal->zipcode = $request->zipcode;
                $proposal->country_id = !empty($request->country_id) ? $request->country_id : null;
                $proposal->state_id = !empty($request->state_id) ? $request->state_id : null;
                $proposal->parcel_number = $request->parcel_number;
                $proposal->billing_address = $request->billing_address;
                $proposal->billing_address_2 = $request->billing_address_2;
                $proposal->billing_city = $request->billing_city;
                $proposal->billing_zipcode = $request->billing_zipcode;
                $proposal->billing_country_id = !empty($request->billing_country_id) ? $request->billing_country_id : null;
                $proposal->billing_state_id = !empty($request->billing_state_id) ? $request->billing_state_id : null;
                $proposal->name = $request->name;
                $proposal->email = $request->email;
                $proposal->phone = $request->phone;
                $proposal->what_services_were_you_looking_for = $servicesStr;
                $proposal->how_did_you_hear_about_us = $request->how_did_you_hear_about_us;
                $proposal->referring_person = $request->referring_person;

                $proposal->save();

                // create a materials snapshot at his moment:

                if ($materials = \App\Material::select(['id AS material_id', 'name', 'cost', 'alt_cost'])->orderBy('name')->get()->toArray()) {
                    foreach ($materials as $material) {
                        $material['order_id'] = $proposal->id;
                    }

                    $data = [
                        'order_id' => $proposal->id,
                        'name'     => $proposal->name,
                    ];
                    \App\OrderMaterial::insert($data);
                }

                // create appointment if defined:

                if (!empty($request->sales_manager_id) && !empty($request->event_started_at)) {
                    $startedAt = \Carbon\Carbon::createFromFormat('m/d/Y h:i A', $request->event_started_at, session()->get('timezone'));

                    if (!empty($request->event_ended_at)) {
                        $endedAt = \Carbon\Carbon::createFromFormat('m/d/Y h:i A', $request->event_ended_at, session()->get('timezone'));
                    } else {
                        // add an hour to started_at:
                        $endedAt = $startedAt->copy()->addHour();
                    }

                    $appointmentData = [
                        'user_id'     => $request->sales_manager_id,
                        'type_id'     => 1,                                 // Appointment
                        'property_id' => $property->id,
                        'started_at'  => $startedAt,
                        'ended_at'    => $endedAt,
                        'name'        => 'Appointment from Intake Form',
                        'description' => $request->event_description,
                    ];
                    //\App\CalendarEvent::create($appointmentData);

                    // Notify sale manager and property manager:

                    // listener:  SendAppointmentNotificationToParties',

                    $proposal->load(['property', 'salesManager', 'salesPerson', 'manager']);

                    event(new \App\Events\NewAppointmentWasScheduled($proposal, $appointmentData, $services));
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('proposal_details_client', ['id' => $proposal->id])->with('success', 'Proposal created.');
    }

    /** Client section */

    public function detailsClient($proposalId)
    {
        if (auth()->user()->isNotAllowTo('show-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (empty($proposalId) || !($proposal = \App\Proposal::with(['property', 'company', 'manager', 'status', 'statusHistory', 'notes' => function ($q) {
                $q->with(['createdBy']);
            }, 'createdBy',])->find($proposalId))) {
            return redirect()->back()->withError('Proposal not found.');
        }

        $json_html_properties_cb = \Cache::remember('json_html_properties_cb', 60 * 12, function () {
            return \App\Property::jsonHtmlPropertiesCB();
        });

        $json_html_property_management_companies_cb = \Cache::remember('json_html_property_management_companies_cb', 60 * 12, function () {
            return \App\Company::jsonHtmlCompaniesCB();
        });

        $json_html_contacts_cb = \Cache::remember('json_html_contacts_cb', 60 * 12, function () {
            return \App\Contact::jsonHtmlContactsCB();
        });

        $data = [
            'proposal'                                   => $proposal,
            'countriesCB'                                => \App\Country::countriesCB(['0' => '']),
            'statesCB'                                   => \App\State::statesCB($proposal->country_id ?? 231, ['0' => '']),
            'salesManagersCB'                            => \App\Employee::salesManagersCB(['0' => '']),
            'salesPersonsCB'                             => \App\Employee::salesPersonsCB(['0' => '']),
            'json_html_properties_cb'                    => $json_html_properties_cb,
            'json_html_property_management_companies_cb' => $json_html_property_management_companies_cb,
            'json_html_contacts_cb'                      => $json_html_contacts_cb,
            'managersCB'                                 => !empty($proposal->company) ? $proposal->company->getUsersCB(['0' => '']) : [],
        ];

        return view('proposal.proposal_client', $data);
    }

    public function ajaxClientUpdateManagers(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            $response = [
                'status'  => 'error',
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'proposal_id'      => $request->proposal_id,
                    'sales_manager_id' => $request->sales_manager_id,
                    'sales_person_id'  => $request->sales_person_id,
                ],
                [
                    'proposal_id'     => 'required|positive',
                    'sales_person_id' => 'nullable|zeroOrPositive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if ($proposal = Proposal::find($request->proposal_id)) {
                        $data = [
                            'sales_manager_id' => $request->sales_manager_id,
                            'sales_person_id'  => !empty($request->sales_person_id) ? $request->sales_person_id : null,
                        ];

                        $proposal->update($data);

                        $response = [
                            'success' => true,
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal not found.',
                        ];
                    }
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

    public function ajaxClientUpdateContactInfo(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            $response = [
                'status'  => 'error',
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'proposal_id'        => $request->proposal_id,
                    'name'               => $request->name,
                    'company_id'         => $request->company_id,
                    'manager_id'         => $request->manager_id,
                    'contact'            => $request->contact,
                    'email'              => $request->email,
                    'phone'              => $request->phone,
                    'alt_phone'          => $request->alt_phone,
                    'billing_address'    => $request->billing_address,
                    'billing_address_2'  => $request->billing_address_2,
                    'billing_city'       => $request->billing_city,
                    'billing_zipcode'    => $request->billing_zipcode,
                    'billing_country_id' => $request->billing_country_id,
                    'billing_state_id'   => $request->billing_state_id,
                ],
                [
                    'proposal_id'        => 'required|positive',
                    'name'               => 'nullable|plainText',
                    'company_id'         => 'nullable|zeroOrPositive',
                    'manager_id'         => 'nullable|zeroOrPositive',
                    'contact'            => 'nullable|plainText',
                    'email'              => 'nullable|email',
                    'phone'              => 'nullable|phone',
                    'alt_phone'          => 'nullable|phone',
                    'billing_address'    => 'nullable|plainText',
                    'billing_address_2'  => 'nullable|plainText',
                    'billing_city'       => 'nullable|plainText',
                    'billing_zipcode'    => 'nullable|plainText',
                    'billing_country_id' => 'nullable|zeroOrPositive',
                    'billing_state_id'   => 'nullable|zeroOrPositive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if ($proposal = Proposal::find($request->proposal_id)) {
                        $data = [
                            'name'               => $request->name,
                            'company_id'         => !empty($request->company_id) ? $request->company_id : null,
                            'manager_id'         => !empty($request->manager_id) ? $request->manager_id : null,
                            'contact'            => $request->contact,
                            'email'              => $request->email,
                            'phone'              => $request->phone,
                            'alt_phone'          => $request->alt_phone,
                            'billing_address'    => $request->billing_address,
                            'billing_address_2'  => $request->billing_address_2,
                            'billing_city'       => $request->billing_city,
                            'billing_zipcode'    => $request->billing_zipcode,
                            'billing_country_id' => !empty($request->billing_country_id) ? $request->billing_country_id : null,
                            'billing_state_id'   => !empty($request->billing_state_id) ? $request->billing_state_id : null,
                        ];

                        $proposal->update($data);

                        $response = [
                            'success' => true,
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal not found.',
                        ];
                    }
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

    public function ajaxClientUpdateJobLocation(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            $response = [
                'status'  => 'error',
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'proposal_id' => $request->proposal_id,
                    'address'     => $request->address,
                    'address_2'   => $request->address_2,
                    'city'        => $request->city,
                    'zipcode'     => $request->zipcode,
                    'country_id'  => $request->country_id,
                    'state_id'    => $request->state_id,
                ],
                [
                    'proposal_id' => 'required|positive',
                    'address'     => 'nullable|plainText',
                    'address_2'   => 'nullable|plainText',
                    'city'        => 'nullable|plainText',
                    'zipcode'     => 'nullable|plainText',
                    'country_id'  => 'nullable|zeroOrPositive',
                    'state_id'    => 'nullable|zeroOrPositive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if ($proposal = Proposal::find($request->proposal_id)) {
                        $data = [
                            'address'    => $request->address,
                            'address_2'  => $request->address_2,
                            'city'       => $request->city,
                            'zipcode'    => $request->zipcode,
                            'country_id' => !empty($request->country_id) ? $request->country_id : null,
                            'state_id'   => !empty($request->state_id) ? $request->state_id : null,
                        ];

                        $proposal->update($data);

                        $response = [
                            'success' => true,
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal not found.',
                        ];
                    }
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

    public function ajaxClientAddNote(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            $response = [
                'status'  => 'error',
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];

        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'proposal_id'  => $request->proposal_id,
                    'note'         => $request->note,
                    'remainded_at' => $request->remainded_at,
                    'share_note'   => $request->share_note,
                ],
                [
                    'proposal_id'  => 'required|positive',
                    'note'         => 'required|text',
                    'remainded_at' => 'nullable|usDateTime',
                    'share_note'   => 'nullable|boolean',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if ($proposal = Proposal::find($request->proposal_id)) {
                        \DB::transaction(function () use ($proposal, $request, & $response) {
                            $remainedAt = !empty($request->remainded_at) ? \Carbon\Carbon::createFromFormat('m/d/Y h:i A', $request->remainded_at, session()->get('timezone')) : null;

                            $data = [
                                'order_id'     => $request->proposal_id,
                                'note'         => $request->note,
                                'remainded_at' => $remainedAt,
                            ];

                            $newNote = new \App\OrderNote($data);

                            $proposal->notes()->save($newNote);

                            if (!empty($request->share_note)) {
                                // send note to customer:
                            }

                            $response = [
                                'success'      => true,
                                'date'         => now()->format('d M Y'),
                                'creator'      => auth()->user()->fullName,
                                'excerpt'      => str_limit($request->note, 200),
                                'note'         => $request->note,
                                'remainded_at' => !empty($remainedAt) ? $remainedAt->format('d M y h:i A') : '',
                                'share'        => !empty($request->share_note) ? 'Yes' : 'No',
                            ];
                        });
                    } else {
                        $response = [
                            'success' => false,
                            'message' => 'Proposal not found.',
                        ];
                    }
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

    /** Services section */

    public function detailsServices($proposalId)
    {
        if (auth()->user()->isNotAllowTo('show-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (empty($proposalId) || !($proposal = \App\Proposal::with(['services' => function ($q) {
                $q->with('serviceCategory')->orderBy('d_sort');
            }, 'property', 'company', 'manager', 'status', 'createdBy',])->find($proposalId))) {

            return redirect()->back()->withError('Proposal not found.');
        }

        $data = [
            'proposal'            => $proposal,
            'serviceCategoriesCB' => \App\Service::servicesWithCategoryCB([0 => 'Select service']),
            'stripingVendorsCB'   => \App\StripingVendor::vendorsCB([null => 'Select vendor']),
            'service_id'          => null,
        ];

        return view('proposal.proposal_services', $data);
    }

    public function detailsServicesReorder(Proposal $proposal, Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            return redirect()->back()->withError(self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$strCid = $request->input('strCid')) {
            return redirect()->back()->withError('strcid is empty.');
        }

        foreach (explode(',', $strCid) as $index => $id) {
            $proposal->services()->find($id)->update(['d_sort' => $index + 1]);
        }

        return redirect()->back()->withSuccess('Service order updated.');
    }

    public function detailsServiceCreateFromGet($proposalId, Request $request)
    {
        if (!$proposal = Proposal::find($proposalId)) {
            return redirect()->route('proposal_list')->withError('Unknown proposal.');
        }

        //   http://allpaving.test/proposals/service/create/254?service_id=15    seal coating
        //   http://allpaving.test/proposals/service/create/254?service_id=20    paver brick
        //   http://allpaving.test/proposals/service/create/254?service_id=21    drainage and catchbasins
        //   http://allpaving.test/proposals/service/create/254?service_id=17    sub contractor
        //   http://allpaving.test/proposals/service/create/254?service_id=16    other
        //   http://allpaving.test/proposals/service/create/254?service_id=2     rock
        //   http://allpaving.test/proposals/service/create/254?service_id=1     excavation
        //   http://allpaving.test/proposals/service/create/254?service_id=6     concrete < 12    Curb (Extruded)
        //   http://allpaving.test/proposals/service/create/254?service_id=14    concrete >= 12   Sidewalks
        //   http://allpaving.test/proposals/service/create/254?service_id=19    asphalt = 19     Milling and cleanup
        //   http://allpaving.test/proposals/service/create/254?service_id=3     asphalt = 3      Repairs
        //   http://allpaving.test/proposals/service/create/254?service_id=18&striping_vendor_id=1     striping

        return $this->detailsServiceCreate($proposal, $request);
    }

    public function detailsServiceCreate(Proposal $proposal, Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-order-service')) {
            return redirect()->route('proposal_details_services', ['id' => $proposal->id])->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $validator = \Validator::make(
            [
                'service_id'         => $request->service_id,
                'striping_vendor_id' => $request->striping_vendor_id,
            ],
            [
                'service_id'         => 'required|positive',
                'striping_vendor_id' => 'nullable|positive|required_if:service_id,18',
            ]
        );

        if ($validator->fails()) {
            return redirect()->route('proposal_details_services', ['id' => $proposal->id])->withError($validator->messages()->first());
        }

        if (!$service = \App\Service::with(['category' => function ($q) {
            $q->with(['vehicleTypes' => function ($w) {
                $w->orderBy('name');
            }, 'equipments'          => function ($e) {
                $e->orderBy('name');
            }]);
        }])->find($request->service_id)) {
            return redirect()->route('proposal_details_services', ['id' => $proposal->id])->withError('Service not found.');
        }

        $data = [
            'proposal'            => $proposal,
            'service'             => $service,
            'otherCostCategories' => \App\OtherCostCategory::orderBy('d_sort')->get(),
            'countriesCB'         => \App\Country::countriesCB(['0' => '']),
            'statesCB'            => \App\State::statesCB(231, ['0' => '']),
        ];

        if ($service->service_category_id == 9) {           // striping
            $stripingVendorId = $request->striping_vendor_id;
            $stripingVendor = \App\StripingVendor::find($request->striping_vendor_id);

            $stripingServiceCategories = \App\StripingServiceCategory::with(['stripingServices' => function ($q) use ($stripingVendorId) {
                $q->whereHas('stripingVendors', function ($w) use ($stripingVendorId) {
                    $w->where('id', $stripingVendorId);
                })
                    ->with(['stripingVendors' => function ($w) use ($stripingVendorId) {
                        $w->where('id', $stripingVendorId)->withPivot('price');
                    }])
                    ->orderBy('name');
            }])->orderBy('d_sort')->get();

            $data['stripingVendor'] = $stripingVendor;
            $data['stripingServiceCategories'] = $stripingServiceCategories;
        } else {
            $data['labors'] = \App\Labor::orderBy('name')->get();
            $data['subContractors'] = \App\SubContractor::orderBy('first_name')->get();
        }

        switch ($service->service_category_id) {
            case '1':                                   // Asphalt

                switch ($service->id) {
                    case '19':
                        $blade = 'asphalt_19';
                        break;
                    case '3':
                    case '4':
                    case '5':
                    case '22':
                        $asphaltMaterials = \App\Material::materialsAndCostsCB([8, 14, 15]);
                        $data['tackMaterial'] = $asphaltMaterials[14];
                        if (in_array($service->id, [4, 22])) {
                            $data['asphaltMaterial'] = $asphaltMaterials[15];
                        } else {
                            $data['asphaltMaterial'] = $asphaltMaterials[8];
                        }
                        $blade = 'asphalt_3_4_5_22';
                        break;
                }
                break;
            case '2':                                   // Concrete
                $data['concreteMaterials'] = \App\Material::materialsAndCostsCB([9, 10]);

                if ($service->id < 12) {
                    $blade = 'concrete_lt_12';
                } else {
                    $blade = 'concrete_egt_12';
                }
                break;
            case '3':                                   // Drainage and Catchbasins
                $blade = 'drainage_and_catchbasins';
                break;
            case '4':                                   // Excavation
                $blade = 'excavation';
                break;
            case '5':                                   // Other
                $blade = 'other';
                break;
            case '6':                                   // Paver Brick
                $blade = 'paver_brick';
                break;
            case '7':                                   // Rock
                $data['rockMaterials'] = \App\Material::materialsAndCostsCB([6, 7]);

                $blade = 'rock';
                break;
            case '8':                                   // Seal Coating
                $yieldsCB = ['' => '', 0 => 0];
                for ($i = 60; $i <= 145; $i += 5) {
                    $yieldsCB[$i] = $i;
                }
                $data['yieldsCB'] = $yieldsCB;

                $blade = 'sealcoating';
                break;
            case '9':                                   // Striping
                $blade = 'striping';
                break;
            case '10':                                  // Sub Contractor
                $data['subContractorsCB'] = \App\SubContractor::subContractorsCB(['0' => '']);

                $blade = 'sub_contractor';
                break;
        }

        if (empty($blade)) {
            return redirect()->back()->withError('Unknown service calculation.');
        }

        return view('proposal.proposal_service_' . $blade . '_create', $data);
    }

    public function detailsServiceStore(Proposal $proposal, Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-order-service')) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $proposal->id, 'service_id' => $request->service_id])->withError(self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$service = \App\Service::find($request->service_id)) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $proposal->id, 'service_id' => $request->service_id])->withError('Service not found.');
        }

        $validator = \Validator::make(
            [
                'service_id'                      => $request->service_id,
                'service_category_id'             => $request->service_category_id,
                'vendor_id'                       => $request->vendor_id,
                'striping_vendor_id'              => $request->striping_vendor_id,
                'name'                            => $request->name,
                'description'                     => $request->description,
                'address'                         => $request->address,
                'address_2'                       => $request->address_2,
                'city'                            => $request->city,
                'zipcode'                         => $request->zipcode,
                'state_id'                        => $request->state_id,
                'parcel_number'                   => $request->parcel_number,
                'loads'                           => $request->loads,
                'locations'                       => $request->locations,
                'linear_feet'                     => $request->linear_feet,
                'cost_per_linear_feet'            => $request->cost_per_linear_feet,
                'square_feet'                     => $request->square_feet,
                'square_yards'                    => $request->square_yards,
                'cubic_yards'                     => $request->cubic_yards,
                'tons'                            => $request->tons,
                'depth_in_inches'                 => $request->depth_in_inches,
                'days'                            => $request->days,
                'cost_per_day'                    => $request->cost_per_day,
                'break_even'                      => $request->break_even,
                'yield'                           => $request->yield,
                'primer'                          => $request->primer,
                'fast_set'                        => $request->fast_set,
                'sand'                            => $request->sand,
                'additive'                        => $request->additive,
                'sealer'                          => $request->sealer,
                'phases'                          => $request->phases,
                'overhead'                        => $request->overhead,
                'cost'                            => $request->cost,
                'profit'                          => $request->profit,
                'proposal_text'                   => $request->proposal_text,
                'vehicle_type_id'                 => $request->vehicle_type_id,
                'vehicle_name'                    => $request->vehicle_name,
                'vehicle_type_quantity'           => $request->vehicle_type_quantity,
                'vehicle_type_days_needed'        => $request->vehicle_type_days_needed,
                'vehicle_type_hours_per_day'      => $request->vehicle_type_hours_per_day,
                'vehicle_rate'                    => $request->vehicle_rate,
                'equipment_id'                    => $request->equipment_id,
                'equipment_rate_type_id'          => $request->equipment_rate_type_id,
                'equipment_name'                  => $request->equipment_name,
                'equipment_quantity'              => $request->equipment_quantity,
                'equipment_days_needed'           => $request->equipment_days_needed,
                'equipment_hours_per_day'         => $request->equipment_hours_per_day,
                'equipment_cost'                  => $request->equipment_cost,
                'equipment_min_cost'              => $request->equipment_min_cost,
                'labor_id'                        => $request->labor_id,
                'labor_name'                      => $request->labor_name,
                'labor_quantity'                  => $request->labor_quantity,
                'labor_days_needed'               => $request->labor_days_needed,
                'labor_hours_per_day'             => $request->labor_hours_per_day,
                'labor_rate'                      => $request->labor_rate,
                'other_cost_category_id'          => $request->other_cost_category_id,
                'other_cost_category_description' => $request->other_cost_category_description,
                'other_cost_category_cost'        => $request->other_cost_category_cost,
                'sub_contractor_id'               => $request->sub_contractor_id,
                'sub_contractor_description'      => $request->sub_contractor_description,
                'sub_contractor_over_head'        => $request->sub_contractor_over_head,
                'sub_contractor_quoted_cost'      => $request->sub_contractor_quoted_cost,
                'sub_contractor_have_bid'         => $request->sub_contractor_have_bid,
                'striping_service_id'             => $request->striping_service_id,
                'striping_service_quantity'       => $request->striping_service_quantity,
                'striping_service_price'          => $request->striping_service_price,
                'unit_cost'                       => $request->unit_cost,
            ],
            [
                'service_id'                        => 'required|positive',
                'service_category_id'               => 'required|positive',
                'vendor_id'                         => 'nullable|positive',
                'striping_vendor_id'                => 'nullable|positive|required_if:service_category_id,9',
                'name'                              => 'required|plainText',
                'description'                       => 'nullable|plainText',
                'address'                           => 'nullable|plainText',
                'address_2'                         => 'nullable|plainText',
                'city'                              => 'nullable|plainText',
                'zipcode'                           => 'nullable|plainText',
                'state_id'                          => 'nullable|zeroOrPositive',
                'country_id'                        => 'nullable|zeroOrPositive',
                'parcel_number'                     => 'nullable|plainText',
                'loads'                             => 'nullable|zeroOrPositive',
                'locations'                         => 'nullable|positive',
                'linear_feet'                       => 'nullable|float',
                'cost_per_linear_feet'              => 'nullable|float',
                'square_feet'                       => 'nullable|float',
                'square_yards'                      => 'nullable|float',
                'cubic_yards'                       => 'nullable|float',
                'tons'                              => 'nullable|positive|required_if:service_category_id,6',   // for paver brick
                'depth_in_inches'                   => 'nullable|float',
                'days'                              => 'nullable|zeroOrPositive',
                'cost_per_day'                      => 'nullable|float',
                'break_even'                        => 'required|float',
                'yield'                             => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'primer'                            => 'nullable|float|required_if:service_category_id,8',      // for seal coating
                'fast_set'                          => 'nullable|float|required_if:service_category_id,8',      // for seal coating
                'sand'                              => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'additive'                          => 'nullable|float',
                'sealer'                            => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'phases'                            => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'overhead'                          => 'required|float',
                'cost'                              => 'required|float',
                'profit'                            => 'required|float',
                'proposal_text'                     => 'required|text',
                'vehicle_type_id.*'                 => 'nullable|zeroOrPositive',
                'vehicle_name.*'                    => 'nullable|plainText',
                'vehicle_type_quantity.*'           => 'nullable|zeroOrPositive',
                'vehicle_type_days_needed.*'        => 'nullable|zeroOrPositive',
                'vehicle_type_hours_per_day.*'      => 'nullable|zeroOrPositive',
                'vehicle_rate.*'                    => 'nullable|float',
                'equipment_id.*'                    => 'nullable|zeroOrPositive',
                'equipment_rate_type_id.*'          => 'nullable|zeroOrPositive',
                'equipment_quantity.*'              => 'nullable|zeroOrPositive',
                'equipment_days_needed.*'           => 'nullable|zeroOrPositive',
                'equipment_hours_per_day.*'         => 'nullable|zeroOrPositive',
                'equipment_cost.*'                  => 'nullable|float',
                'equipment_min_cost.*'              => 'nullable|float',
                'labor_id.*'                        => 'nullable|zeroOrPositive',
                'labor_name.*'                      => 'nullable|plainText',
                'labor_quantity.*'                  => 'nullable|zeroOrPositive',
                'labor_days_needed.*'               => 'nullable|zeroOrPositive',
                'labor_hours_per_day.*'             => 'nullable|zeroOrPositive',
                'labor_rate.*'                      => 'nullable|float',
                'other_cost_category_id.*'          => 'nullable|zeroOrPositive',
                'other_cost_category_description.*' => 'nullable|plainText',
                'other_cost_category_cost.*'        => 'nullable|float',
                'sub_contractor_id.*'               => 'nullable|zeroOrPositive',
                'sub_contractor_description.*'      => 'nullable|plainText',
                'sub_contractor_over_head.*'        => 'nullable|zeroOrPositive',
                'sub_contractor_quoted_cost.*'      => 'nullable|float',
                'sub_contractor_have_bid.*'         => 'nullable|boolean',
                'striping_service_id.*'             => 'nullable|zeroOrPositive',
                'striping_service_quantity.*'       => 'nullable|zeroOrPositive',
                'striping_service_price.*'          => 'nullable|float',
                'unit_cost'                         => 'nullable|float',
            ]
        );

        // seal coating, paver brick, rock, excavation, concrete >= 12, asphalt both
        $validator->sometimes('square_feet', 'required', function ($input) {
            return in_array($input->service_category_id, [8, 6, 7, 4, 1]) || ($input->service_category_id == 2 && $input->service_id >= 12);
        });

        // seal coating, number of catch basins, subcontractor, rock
        $validator->sometimes('additive', 'required', function ($input) {
            return in_array($input->service_category_id, [8, 3, 10, 7]);
        });

        // paver brick, number of catch basins, subcontractor, other, excavation, asphalt 12
        $validator->sometimes('cost_per_day', 'required', function ($input) {
            return in_array($input->service_category_id, [6, 3, 10, 5, 4]) || ($input->service_category_id == 1 && $input->service_id == 19);
        });

        // paver brick, number of catch basins, subcontractor, other
        $validator->sometimes('description', 'required', function ($input) {
            return in_array($input->service_category_id, [6, 3, 10, 5]);
        });

        // sub contrator
        $validator->sometimes('vendor_id', 'required', function ($input) {
            return in_array($input->service_category_id, [10]);
        });

        // other, concrete both, asphalt both
        $validator->sometimes('locations', 'required', function ($input) {
            return in_array($input->service_category_id, [5, 2, 1]);
        });

        // rock, excavation, concrete >= 12, asphalt both
        $validator->sometimes('depth_in_inches', 'required', function ($input) {
            return in_array($input->service_category_id, [7, 4, 1]) || ($input->service_category_id == 2 && $input->service_id >= 12);
        });

        // concrete < 12
        $validator->sometimes('linear_feet', 'required', function ($input) {
            return $input->service_category_id == 2 && $input->service_id < 12;
        });

        // concrete both
        $validator->sometimes('cubic_yards', 'required', function ($input) {
            return $input->service_category_id == 2;
        });

        // asphalt both
        $validator->sometimes('square_yards', 'required', function ($input) {
            return $input->service_category_id == 1;
        });

        // asphalt 19
        $validator->sometimes('days', 'required', function ($input) {
            return $input->service_category_id == 1 && $input->service_id == 19;
        });

        // rock, excavation, asphalt 19
        $validator->sometimes('loads', 'required', function ($input) {
            return in_array($input->service_category_id, [7, 4]) || ($input->service_category_id == 1 && $input->service_id == 19);
        });

        // paver brick, rock, excavation, asphalt 3,4,5,22
        $validator->sometimes('tons', 'required', function ($input) {
            return in_array($input->service_category_id, [6, 7, 4]) || ($input->service_category_id == 1 && in_array($input->service_id, [3, 4, 5, 22]));
        });

        if ($validator->fails()) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $proposal->id, 'service_id' => $request->service_id])->withError($validator->messages()->first());
        }

        try {
            \DB::transaction(function () use ($proposal, $request) {

                // create service:

                $patterns = [
                    '/@@SQFT@@/',
                    '/@@PHASES@@/',
                    '/@@INCHES@@/',
                    '/@@TONS@@/',
                    '/@@BASINS@@/',
                ];
                $replacements = [
                    number_format($request->square_feet, 2),
                    $request->phases,
                    $request->depth_in_inches,
                    $request->tons,
                    $request->additive,
                ];

                $data = [
                    'order_id'                => $proposal->id,
                    'order_service_status_id' => 3,                 // not schedulled
                    'service_id'              => $request->service_id,
                    'service_category_id'     => $request->service_category_id,
                    'vendor_id'               => $request->vendor_id,
                    'striping_vendor_id'      => $request->striping_vendor_id,
                    'name'                    => $request->name,
                    'description'             => $request->description,
                    'address'                 => $request->address,
                    'address_2'               => $request->address_2,
                    'city'                    => $request->city,
                    'zipcode'                 => $request->zipcode,
                    'state_id'                => $request->state_id,
                    'parcel_number'           => $request->parcel_number,
                    'loads'                   => $request->loads,
                    'locations'               => $request->locations,
                    'linear_feet'             => $request->linear_feet,
                    'cost_per_linear_feet'    => $request->cost_per_linear_feet,
                    'square_feet'             => $request->square_feet,
                    'square_yards'            => $request->square_yards,
                    'cubic_yards'             => $request->cubic_yards,
                    'tons'                    => $request->tons,
                    'depth_in_inches'         => $request->depth_in_inches,
                    'days'                    => $request->days,
                    'cost_per_day'            => $request->cost_per_day,
                    'break_even'              => $request->break_even,
                    'yield'                   => $request->yield,
                    'primer'                  => $request->primer,
                    'fast_set'                => $request->fast_set,
                    'sand'                    => $request->sand,
                    'additive'                => $request->additive,
                    'sealer'                  => $request->sealer,
                    'phases'                  => $request->phases,
                    'overhead'                => $request->overhead,
                    'cost'                    => $request->cost,
                    'profit'                  => $request->profit,
                    'proposal_text'           => preg_replace($patterns, $replacements, $request->proposal_text),
                ];
                $service = \App\OrderService::create($data);

                // create service vehicle types:

                if (!empty($request->vehicle_type_id)) {
                    for ($i = 0; $i < count($request->vehicle_type_id); $i++) {
                        if (!empty($request->vehicle_type_id[$i])) {
                            $data = [
                                'order_service_id' => $service->id,
                                'vehicle_type_id'  => $request->vehicle_type_id[$i],
                                'name'             => $request->vehicle_name[$i],
                                'quantity'         => $request->vehicle_type_quantity[$i],
                                'days_needed'      => $request->vehicle_type_days_needed[$i],
                                'hours_per_day'    => $request->vehicle_type_hours_per_day[$i],
                                'rate'             => $request->vehicle_rate[$i],
                            ];
                            \App\OrderServiceVehicleType::create($data);
                        }
                    }
                }

                // create service equipment:

                if (!empty($request->equipment_id)) {
                    for ($i = 0; $i < count($request->equipment_id); $i++) {
                        if (!empty($request->equipment_id[$i])) {
                            $data = [
                                'order_service_id' => $service->id,
                                'equipment_id'     => $request->equipment_id[$i],
                                'rate_type_id'     => $request->equipment_rate_type_id[$i],
                                'name'             => $request->equipment_name[$i],
                                'quantity'         => $request->equipment_quantity[$i],
                                'days_needed'      => $request->equipment_days_needed[$i],
                                'hours_per_day'    => $request->equipment_hours_per_day[$i] ?? null,
                                'cost'             => $request->equipment_cost[$i],
                                'min_cost'         => $request->equipment_min_cost[$i],
                            ];
                            \App\OrderServiceEquipment::create($data);
                        }
                    }
                }

                // create service labors:

                if (!empty($request->labor_id)) {
                    for ($i = 0; $i < count($request->labor_id); $i++) {
                        if (!empty($request->labor_id[$i])) {
                            $data = [
                                'order_service_id' => $service->id,
                                'labor_id'         => $request->labor_id[$i],
                                'name'             => $request->labor_name[$i],
                                'quantity'         => $request->labor_quantity[$i],
                                'days_needed'      => $request->labor_days_needed[$i],
                                'hours_per_day'    => $request->labor_hours_per_day[$i],
                                'rate'             => $request->labor_rate[$i],
                            ];
                            \App\OrderServiceLabor::create($data);
                        }
                    }
                }

                // create service other costs:

                if (!empty($request->other_cost_category_id)) {
                    for ($i = 0; $i < count($request->other_cost_category_id); $i++) {
                        if (!empty($request->other_cost_category_id[$i])) {
                            $data = [
                                'order_service_id'       => $service->id,
                                'other_cost_category_id' => $request->other_cost_category_id[$i],
                                'description'            => $request->other_cost_category_description[$i],
                                'cost'                   => $request->other_cost_category_cost[$i],
                            ];
                            \App\OrderServiceOtherCost::create($data);
                        }
                    }
                }

                // create service sub contractors:

                if (!empty($request->sub_contractor_id)) {
                    for ($i = 0; $i < count($request->sub_contractor_id); $i++) {
                        if (!empty($request->sub_contractor_id[$i])) {
                            $data = [
                                'order_service_id'  => $service->id,
                                'sub_contractor_id' => $request->sub_contractor_id[$i],
                                'description'       => $request->sub_contractor_description[$i],
                                'cost'              => $request->sub_contractor_quoted_cost[$i],
                                'overhead'          => $request->sub_contractor_over_head[$i],
                                'have_bid'          => $request->sub_contractor_have_bid[$i] ?? 0,
                            ];
                            \App\OrderServiceSubContractor::create($data);
                        }
                    }
                }

                // create service striping:

                if (!empty($request->striping_service_id)) {
                    for ($i = 0; $i < count($request->striping_service_id); $i++) {
                        if (!empty($request->striping_service_id[$i])) {
                            $data = [
                                'order_service_id'    => $service->id,
                                'striping_vendor_id'  => $request->striping_vendor_id,
                                'striping_service_id' => $request->striping_service_id[$i],
                                'quantity'            => $request->striping_service_quantity[$i],
                                'price'               => $request->striping_service_price[$i],
                            ];
                            \App\OrderServiceStripingVendorService::create($data);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $proposal->id, 'service_id' => $request->service_id])->withError($e->getMessage());
        }

        return redirect()->route('proposal_details_services', ['id' => $proposal->id])->withSuccess('Service created.');
    }

    public function detailsServiceEdit($proposalId, $serviceId)
    {
        if (auth()->user()->isNotAllowTo('update-order-service')) {
            return redirect()->route('proposal_details_services', ['id' => $proposalId])->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$orderService = OrderService::with(['order', 'service' => function ($q) {
            $q->with(['category' => function ($q) {
                $q->with(['vehicleTypes' => function ($w) {
                    $w->orderBy('name');
                }, 'equipments'          => function ($e) {
                    $e->orderBy('name');
                }]);
            }]);
        }, 'equipments', 'labors', 'materials', 'otherCosts', 'subContactors', 'vehicleTypes'])
            ->find($serviceId)) {

            return redirect()->route('proposal_details_services', ['id' => $proposalId])->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'orderService'        => $orderService,
            'proposal'            => $orderService->order,
            'service'             => $orderService->service,
            'otherCostCategories' => \App\OtherCostCategory::orderBy('d_sort')->get(),
            'countriesCB'         => \App\Country::countriesCB(['0' => '']),
            'statesCB'            => \App\State::statesCB(231, ['0' => '']),
        ];

        if ($orderService->service->service_category_id == 9) {           // striping
            $stripingVendorId = $orderService->striping_vendor_id;
            $stripingVendor = $orderService->stripingVendor;

            $stripingServiceCategories = \App\StripingServiceCategory::with(['stripingServices' => function ($q) use ($stripingVendorId) {
                $q->whereHas('stripingVendors', function ($w) use ($stripingVendorId) {
                    $w->where('id', $stripingVendorId);
                })
                    ->with(['stripingVendors' => function ($w) use ($stripingVendorId) {
                        $w->where('id', $stripingVendorId)->withPivot('price');
                    }])
                    ->orderBy('name');
            }])->orderBy('d_sort')->get();

            $data['stripingVendor'] = $stripingVendor;
            $data['stripingServiceCategories'] = $stripingServiceCategories;
        } else {
            $data['labors'] = \App\Labor::orderBy('name')->get();
            $data['subContractors'] = \App\SubContractor::orderBy('first_name')->get();
        }

        switch ($orderService->service->service_category_id) {
            case '1':                                   // Asphalt

                switch ($orderService->service_id) {
                    case '19':
                        $blade = 'asphalt_19';
                        break;
                    case '3':
                    case '4':
                    case '5':
                    case '22':
                        $asphaltMaterials = \App\Material::materialsAndCostsCB([8, 14, 15]);
                        $data['tackMaterial'] = $asphaltMaterials[14];
                        if (in_array($orderService->service_id, [4, 22])) {
                            $data['asphaltMaterial'] = $asphaltMaterials[15];
                        } else {
                            $data['asphaltMaterial'] = $asphaltMaterials[8];
                        }
                        $blade = 'asphalt_3_4_5_22';
                        break;
                }
                break;
            case '2':                                   // Concrete
                $data['concreteMaterials'] = \App\Material::materialsAndCostsCB([9, 10]);

                if ($orderService->service_id < 12) {
                    $blade = 'concrete_lt_12';
                } else {
                    $blade = 'concrete_egt_12';
                }
                break;
            case '3':                                   // Drainage and Catchbasins
                $blade = 'drainage_and_catchbasins';
                break;
            case '4':                                   // Excavation
                $blade = 'excavation';
                break;
            case '5':                                   // Other
                $blade = 'other';
                break;
            case '6':                                   // Paver Brick
                $blade = 'paver_brick';
                break;
            case '7':                                   // Rock
                $data['rockMaterials'] = \App\Material::materialsAndCostsCB([6, 7]);

                $blade = 'rock';
                break;
            case '8':                                   // Seal Coating
                $yieldsCB = ['' => '', 0 => 0];
                for ($i = 60; $i <= 145; $i += 5) {
                    $yieldsCB[$i] = $i;
                }
                $data['yieldsCB'] = $yieldsCB;

                $blade = 'sealcoating';
                break;
            case '9':                                   // Striping
                $blade = 'striping';
                break;
            case '10':                                  // Sub Contractor
                $data['subContractorsCB'] = \App\SubContractor::subContractorsCB(['0' => '']);

                $blade = 'sub_contractor';
                break;
        }

        if (empty($blade)) {
            return redirect()->back()->withError('Unknown service calculation.');
        }

        return view('proposal.proposal_service_' . $blade . '_edit', $data);
    }

    public function detailsServiceUpdate(OrderService $orderService, Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-order-service')) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $orderService->order_id, 'service_id' => $orderService->id])->withError(self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $validator = \Validator::make(
            [
                'service_id'                      => $request->service_id,
                'service_category_id'             => $request->service_category_id,
                'vendor_id'                       => $request->vendor_id,
                'striping_vendor_id'              => $request->striping_vendor_id,
                'name'                            => $request->name,
                'description'                     => $request->description,
                'address'                         => $request->address,
                'address_2'                       => $request->address_2,
                'city'                            => $request->city,
                'zipcode'                         => $request->zipcode,
                'state_id'                        => $request->state_id,
                'parcel_number'                   => $request->parcel_number,
                'loads'                           => $request->loads,
                'locations'                       => $request->locations,
                'linear_feet'                     => $request->linear_feet,
                'cost_per_linear_feet'            => $request->cost_per_linear_feet,
                'square_feet'                     => $request->square_feet,
                'square_yards'                    => $request->square_yards,
                'cubic_yards'                     => $request->cubic_yards,
                'tons'                            => $request->tons,
                'depth_in_inches'                 => $request->depth_in_inches,
                'days'                            => $request->days,
                'cost_per_day'                    => $request->cost_per_day,
                'break_even'                      => $request->break_even,
                'yield'                           => $request->yield,
                'primer'                          => $request->primer,
                'fast_set'                        => $request->fast_set,
                'sand'                            => $request->sand,
                'additive'                        => $request->additive,
                'sealer'                          => $request->sealer,
                'phases'                          => $request->phases,
                'overhead'                        => $request->overhead,
                'cost'                            => $request->cost,
                'profit'                          => $request->profit,
                'proposal_text'                   => $request->proposal_text,
                'vehicle_type_id'                 => $request->vehicle_type_id,
                'vehicle_name'                    => $request->vehicle_name,
                'vehicle_type_quantity'           => $request->vehicle_type_quantity,
                'vehicle_type_days_needed'        => $request->vehicle_type_days_needed,
                'vehicle_type_hours_per_day'      => $request->vehicle_type_hours_per_day,
                'vehicle_rate'                    => $request->vehicle_rate,
                'equipment_id'                    => $request->equipment_id,
                'equipment_rate_type_id'          => $request->equipment_rate_type_id,
                'equipment_name'                  => $request->equipment_name,
                'equipment_quantity'              => $request->equipment_quantity,
                'equipment_days_needed'           => $request->equipment_days_needed,
                'equipment_hours_per_day'         => $request->equipment_hours_per_day,
                'equipment_cost'                  => $request->equipment_cost,
                'equipment_min_cost'              => $request->equipment_min_cost,
                'labor_id'                        => $request->labor_id,
                'labor_name'                      => $request->labor_name,
                'labor_quantity'                  => $request->labor_quantity,
                'labor_days_needed'               => $request->labor_days_needed,
                'labor_hours_per_day'             => $request->labor_hours_per_day,
                'labor_rate'                      => $request->labor_rate,
                'other_cost_category_id'          => $request->other_cost_category_id,
                'other_cost_category_description' => $request->other_cost_category_description,
                'other_cost_category_cost'        => $request->other_cost_category_cost,
                'sub_contractor_id'               => $request->sub_contractor_id,
                'sub_contractor_description'      => $request->sub_contractor_description,
                'sub_contractor_over_head'        => $request->sub_contractor_over_head,
                'sub_contractor_quoted_cost'      => $request->sub_contractor_quoted_cost,
                'sub_contractor_have_bid'         => $request->sub_contractor_have_bid,
                'striping_service_id'             => $request->striping_service_id,
                'striping_service_quantity'       => $request->striping_service_quantity,
                'striping_service_price'          => $request->striping_service_price,
                'unit_cost'                       => $request->unit_cost,
            ],
            [
                'service_id'                        => 'required|positive',
                'service_category_id'               => 'required|positive',
                'vendor_id'                         => 'nullable|positive',
                'striping_vendor_id'                => 'nullable|positive|required_if:service_category_id,9',
                'name'                              => 'required|plainText',
                'description'                       => 'nullable|plainText',
                'address'                           => 'nullable|plainText',
                'address_2'                         => 'nullable|plainText',
                'city'                              => 'nullable|plainText',
                'zipcode'                           => 'nullable|plainText',
                'state_id'                          => 'nullable|zeroOrPositive',
                'country_id'                        => 'nullable|zeroOrPositive',
                'parcel_number'                     => 'nullable|plainText',
                'loads'                             => 'nullable|zeroOrPositive',
                'locations'                         => 'nullable|positive',
                'linear_feet'                       => 'nullable|float',
                'cost_per_linear_feet'              => 'nullable|float',
                'square_feet'                       => 'nullable|float',
                'square_yards'                      => 'nullable|float',
                'cubic_yards'                       => 'nullable|float',
                'tons'                              => 'nullable|positive|required_if:service_category_id,6',   // for paver brick
                'depth_in_inches'                   => 'nullable|float',
                'days'                              => 'nullable|zeroOrPositive',
                'cost_per_day'                      => 'nullable|float',
                'break_even'                        => 'required|float',
                'yield'                             => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'primer'                            => 'nullable|float|required_if:service_category_id,8',      // for seal coating
                'fast_set'                          => 'nullable|float|required_if:service_category_id,8',      // for seal coating
                'sand'                              => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'additive'                          => 'nullable|float',
                'sealer'                            => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'phases'                            => 'nullable|positive|required_if:service_category_id,8',   // for seal coating
                'overhead'                          => 'required|float',
                'cost'                              => 'required|float',
                'profit'                            => 'required|float',
                'proposal_text'                     => 'required|text',
                'vehicle_type_id.*'                 => 'nullable|zeroOrPositive',
                'vehicle_name.*'                    => 'nullable|plainText',
                'vehicle_type_quantity.*'           => 'nullable|zeroOrPositive',
                'vehicle_type_days_needed.*'        => 'nullable|zeroOrPositive',
                'vehicle_type_hours_per_day.*'      => 'nullable|zeroOrPositive',
                'vehicle_rate.*'                    => 'nullable|float',
                'equipment_id.*'                    => 'nullable|zeroOrPositive',
                'equipment_rate_type_id.*'          => 'nullable|zeroOrPositive',
                'equipment_quantity.*'              => 'nullable|zeroOrPositive',
                'equipment_days_needed.*'           => 'nullable|zeroOrPositive',
                'equipment_hours_per_day.*'         => 'nullable|zeroOrPositive',
                'equipment_cost.*'                  => 'nullable|float',
                'equipment_min_cost.*'              => 'nullable|float',
                'labor_id.*'                        => 'nullable|zeroOrPositive',
                'labor_name.*'                      => 'nullable|plainText',
                'labor_quantity.*'                  => 'nullable|zeroOrPositive',
                'labor_days_needed.*'               => 'nullable|zeroOrPositive',
                'labor_hours_per_day.*'             => 'nullable|zeroOrPositive',
                'labor_rate.*'                      => 'nullable|float',
                'other_cost_category_id.*'          => 'nullable|zeroOrPositive',
                'other_cost_category_description.*' => 'nullable|plainText',
                'other_cost_category_cost.*'        => 'nullable|float',
                'sub_contractor_id.*'               => 'nullable|zeroOrPositive',
                'sub_contractor_description.*'      => 'nullable|plainText',
                'sub_contractor_over_head.*'        => 'nullable|zeroOrPositive',
                'sub_contractor_quoted_cost.*'      => 'nullable|float',
                'sub_contractor_have_bid.*'         => 'nullable|boolean',
                'striping_service_id.*'             => 'nullable|zeroOrPositive',
                'striping_service_quantity.*'       => 'nullable|zeroOrPositive',
                'striping_service_price.*'          => 'nullable|float',
                'unit_cost'                         => 'nullable|float',
            ]
        );

        // seal coating, paver brick, rock, excavation, concrete >= 12, asphalt both
        $validator->sometimes('square_feet', 'required', function ($input) {
            return in_array($input->service_category_id, [8, 6, 7, 4, 1]) || ($input->service_category_id == 2 && $input->service_id >= 12);
        });

        // seal coating, number of catch basins, subcontractor, rock
        $validator->sometimes('additive', 'required', function ($input) {
            return in_array($input->service_category_id, [8, 3, 10, 7]);
        });

        // paver brick, number of catch basins, subcontractor, other, excavation, asphalt 12
        $validator->sometimes('cost_per_day', 'required', function ($input) {
            return in_array($input->service_category_id, [6, 3, 10, 5, 4]) || ($input->service_category_id == 1 && $input->service_id == 19);
        });

        // paver brick, number of catch basins, subcontractor, other
        $validator->sometimes('description', 'required', function ($input) {
            return in_array($input->service_category_id, [6, 3, 10, 5]);
        });

        // sub contrator
        $validator->sometimes('vendor_id', 'required', function ($input) {
            return in_array($input->service_category_id, [10]);
        });

        // other, concrete both, asphalt both
        $validator->sometimes('locations', 'required', function ($input) {
            return in_array($input->service_category_id, [5, 2, 1]);
        });

        // rock, excavation, concrete >= 12, asphalt both
        $validator->sometimes('depth_in_inches', 'required', function ($input) {
            return in_array($input->service_category_id, [7, 4, 1]) || ($input->service_category_id == 2 && $input->service_id >= 12);
        });

        // concrete < 12
        $validator->sometimes('linear_feet', 'required', function ($input) {
            return $input->service_category_id == 2 && $input->service_id < 12;
        });

        // concrete both
        $validator->sometimes('cubic_yards', 'required', function ($input) {
            return $input->service_category_id == 2;
        });

        // asphalt both
        $validator->sometimes('square_yards', 'required', function ($input) {
            return $input->service_category_id == 1;
        });

        // asphalt 19
        $validator->sometimes('days', 'required', function ($input) {
            return $input->service_category_id == 1 && $input->service_id == 19;
        });

        // rock, excavation, asphalt 19
        $validator->sometimes('loads', 'required', function ($input) {
            return in_array($input->service_category_id, [7, 4]) || ($input->service_category_id == 1 && $input->service_id == 19);
        });

        // paver brick, rock, excavation, asphalt 3,4,5,22
        $validator->sometimes('tons', 'required', function ($input) {
            return in_array($input->service_category_id, [6, 7, 4]) || ($input->service_category_id == 1 && in_array($input->service_id, [3, 4, 5, 22]));
        });

        if ($validator->fails()) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $orderService->order_id, 'service_id' => $orderService->id])->withError($validator->messages()->first());
        }

        $orderService->load(['order', 'service' => function ($q) {
            $q->with(['category' => function ($q) {
                $q->with(['vehicleTypes' => function ($w) {
                    $w->orderBy('name');
                }, 'equipments'          => function ($e) {
                    $e->orderBy('name');
                }]);
            }]);
        }, 'equipments', 'labors', 'materials', 'otherCosts', 'subContactors', 'vehicleTypes']);

        try {
            \DB::transaction(function () use ($orderService, $request) {

                $patterns = [
                    '/@@SQFT@@/',
                    '/@@PHASES@@/',
                    '/@@INCHES@@/',
                    '/@@TONS@@/',
                    '/@@BASINS@@/',
                ];
                $replacements = [
                    number_format($request->square_feet, 2),
                    $request->phases,
                    $request->depth_in_inches,
                    $request->tons,
                    $request->additive,
                ];

                $data = [
                    'service_id'           => $request->service_id,
                    'service_category_id'  => $request->service_category_id,
                    'vendor_id'            => $request->vendor_id,
                    'striping_vendor_id'   => $request->striping_vendor_id,
                    'name'                 => $request->name,
                    'description'          => $request->description,
                    'address'              => $request->address,
                    'address_2'            => $request->address_2,
                    'city'                 => $request->city,
                    'zipcode'              => $request->zipcode,
                    'state_id'             => $request->state_id,
                    'parcel_number'        => $request->parcel_number,
                    'loads'                => $request->loads,
                    'locations'            => $request->locations,
                    'linear_feet'          => $request->linear_feet,
                    'cost_per_linear_feet' => $request->cost_per_linear_feet,
                    'square_feet'          => $request->square_feet,
                    'square_yards'         => $request->square_yards,
                    'cubic_yards'          => $request->cubic_yards,
                    'tons'                 => $request->tons,
                    'depth_in_inches'      => $request->depth_in_inches,
                    'days'                 => $request->days,
                    'cost_per_day'         => $request->cost_per_day,
                    'break_even'           => $request->break_even,
                    'yield'                => $request->yield,
                    'primer'               => $request->primer,
                    'fast_set'             => $request->fast_set,
                    'sand'                 => $request->sand,
                    'additive'             => $request->additive,
                    'sealer'               => $request->sealer,
                    'phases'               => $request->phases,
                    'overhead'             => $request->overhead,
                    'cost'                 => $request->cost,
                    'profit'               => $request->profit,
                    'proposal_text'        => preg_replace($patterns, $replacements, $request->proposal_text),
                ];
                $orderService->update($data);

                // re-create service vehicle types:

                $orderService->vehicleTypes()->delete();

                if (!empty($request->vehicle_type_id)) {
                    for ($i = 0; $i < count($request->vehicle_type_id); $i++) {
                        if (!empty($request->vehicle_type_id[$i])) {
                            $data = [
                                'order_service_id' => $orderService->id,
                                'vehicle_type_id'  => $request->vehicle_type_id[$i],
                                'name'             => $request->vehicle_name[$i],
                                'quantity'         => $request->vehicle_type_quantity[$i],
                                'days_needed'      => $request->vehicle_type_days_needed[$i],
                                'hours_per_day'    => $request->vehicle_type_hours_per_day[$i],
                                'rate'             => $request->vehicle_rate[$i],
                            ];
                            \App\OrderServiceVehicleType::create($data);
                        }
                    }
                }

                // re-create service equipment:

                $orderService->equipments()->delete();

                if (!empty($request->equipment_id)) {
                    for ($i = 0; $i < count($request->equipment_id); $i++) {
                        if (!empty($request->equipment_id[$i])) {
                            $data = [
                                'order_service_id' => $orderService->id,
                                'equipment_id'     => $request->equipment_id[$i],
                                'rate_type_id'     => $request->equipment_rate_type_id[$i],
                                'name'             => $request->equipment_name[$i],
                                'quantity'         => $request->equipment_quantity[$i],
                                'days_needed'      => $request->equipment_days_needed[$i],
                                'hours_per_day'    => $request->equipment_hours_per_day[$i] ?? null,
                                'cost'             => $request->equipment_cost[$i],
                                'min_cost'         => $request->equipment_min_cost[$i],
                            ];
                            \App\OrderServiceEquipment::create($data);
                        }
                    }
                }

                // re-create service labors:

                $orderService->labors()->delete();

                if (!empty($request->labor_id)) {
                    for ($i = 0; $i < count($request->labor_id); $i++) {
                        if (!empty($request->labor_id[$i])) {
                            $data = [
                                'order_service_id' => $orderService->id,
                                'labor_id'         => $request->labor_id[$i],
                                'name'             => $request->labor_name[$i],
                                'quantity'         => $request->labor_quantity[$i],
                                'days_needed'      => $request->labor_days_needed[$i],
                                'hours_per_day'    => $request->labor_hours_per_day[$i],
                                'rate'             => $request->labor_rate[$i],
                            ];
                            \App\OrderServiceLabor::create($data);
                        }
                    }
                }

                // create service other costs:

                $orderService->otherCosts()->delete();

                if (!empty($request->other_cost_category_id)) {
                    for ($i = 0; $i < count($request->other_cost_category_id); $i++) {
                        if (!empty($request->other_cost_category_id[$i])) {
                            $data = [
                                'order_service_id'       => $orderService->id,
                                'other_cost_category_id' => $request->other_cost_category_id[$i],
                                'description'            => $request->other_cost_category_description[$i],
                                'cost'                   => $request->other_cost_category_cost[$i],
                            ];
                            \App\OrderServiceOtherCost::create($data);
                        }
                    }
                }

                // re-create service sub contractors:

                $orderService->subContactors()->delete();

                if (!empty($request->sub_contractor_id)) {
                    for ($i = 0; $i < count($request->sub_contractor_id); $i++) {
                        if (!empty($request->sub_contractor_id[$i])) {
                            $data = [
                                'order_service_id'  => $orderService->id,
                                'sub_contractor_id' => $request->sub_contractor_id[$i],
                                'description'       => $request->sub_contractor_description[$i],
                                'cost'              => $request->sub_contractor_quoted_cost[$i],
                                'overhead'          => $request->sub_contractor_over_head[$i],
                                'have_bid'          => $request->sub_contractor_have_bid[$i] ?? 0,
                            ];
                            \App\OrderServiceSubContractor::create($data);
                        }
                    }
                }

                // re-create service striping:

                $orderService->stripingVendorServices()->delete();

                if (!empty($request->striping_service_id)) {
                    for ($i = 0; $i < count($request->striping_service_id); $i++) {
                        if (!empty($request->striping_service_id[$i])) {
                            $data = [
                                'order_service_id'    => $orderService->id,
                                'striping_vendor_id'  => $request->striping_vendor_id,
                                'striping_service_id' => $request->striping_service_id[$i],
                                'quantity'            => $request->striping_service_quantity[$i],
                                'price'               => $request->striping_service_price[$i],
                            ];
                            \App\OrderServiceStripingVendorService::create($data);
                        }
                    }
                }
            });
        } catch (\Exception $e) {
            return redirect()->route('get_proposal_details_service_create', ['proposal_id' => $orderService->order_id, 'service_id' => $orderService->id])->withError($e->getMessage());
        }

        return redirect()->route('proposal_details_services', ['id' => $orderService->order_id])->withSuccess('Service created.');
    }

    public function detailsServiceDestroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-order-service')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$service = \App\OrderService::find($request->item_id)) {
            return redirect()->back()->with('error', 'Service not found.');
        }

        $returnTo = route('proposal_details_services', ['id' => $service->order_id]);

        $service->delete();

        return redirect($returnTo)->withSuccess('Service removed.');
    }

    /////

    public function edit(Proposal $proposal)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'proposal'        => $proposal,
            'salesManagersCB' => \App\Employee::salesManagersCB(),
            'salesPersonsCB'  => \App\Employee::salesPersonsCB(),

            'kk'         => \App\Country::countriesCB(['0' => '']),
            'servicesCB' => \App\Service::servicesCB($proposal->country_id ?? 231, ['0' => '']),

            'countriesCB' => \App\Country::countriesCB(['0' => '']),
            'statesCB'    => \App\State::statesCB($proposal->country_id ?? 231, ['0' => '']),
            'seo'         => [
                'pageTitle' => 'Edit: ' . $proposal->fullName,
            ],
        ];

        return view('proposal.edit', $data);
    }

    public function update(Proposal $proposal, Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $proposal->update($inputs);

        return redirect()->route('proposal_list')->withSuccess('Proposal updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
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
                    \DB::transaction(function () use ($id, $name, $value, $relation, & $response) {
                        $model = Proposal::find($id);

                        if (!empty($relation)) {
                            $oldValue = $model->{$relation}->{$name};
                            $model->{$relation}->update([$name => $value]);
                        } else {
                            $oldValue = $model->{$name};
                            $model->{$name} = $value;
                            $model->save();
                        }

                        $response = [
                            'status'                                     => 'success',
                            'field'                                      => $name,
                            'old_value'                                  => $oldValue,
                            'services_total_cost_with_discount_currency' => $model->services_total_cost_with_discount_currency,
                        ];
                    });

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

    public function toggleStatus(Proposal $proposal)
    {
        if (auth()->user()->isNotAllowTo('update-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $proposal->disabled = !$proposal->disabled;
        $proposal->save();

        return redirect()->back()->with('success', 'Proposal status has been toggled.');
    }

    public function createNote(Proposal $proposal)
    {
        if (auth()->user()->isNotAllowTo('add-note-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'proposal' => $proposal,
            'seo'      => [
                'pageTitle' => 'Add Note to Proposal',
            ],
        ];

        return view('proposal.edit', $data);
    }

    public function storeNote(Proposal $proposal, Request $request)
    {
        if (auth()->user()->isNotAllowTo('add-note-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $proposal->update($inputs);

        return redirect()->route('proposal_list')->withSuccess('Proposal updated.');
    }

    public function editStatus(Proposal $proposal)
    {
        if (auth()->user()->isNotAllowTo('change-status-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'proposal' => $proposal,
            'seo'      => [
                'pageTitle' => 'Change Proposal Status',
            ],
        ];

        return view('proposal.edit', $data);
    }

    public function updateStatus(Proposal $proposal, Request $request)
    {
        if (auth()->user()->isNotAllowTo('change-status-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $request->all();

        if (empty($inputs['disable'])) {
            $inputs['disable'] = 0;
        }

        $proposal->update($inputs);

        return redirect()->route('proposal_list')->withSuccess('Proposal updated.');
    }

    // Media section

    public function detailsMedia($proposalId)
    {
        if (auth()->user()->isNotAllowTo('show-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (empty($proposalId) || !($proposal = \App\Proposal::with(['property', 'company', 'manager', 'status', 'media' => function ($q) {
                $q->with(['mediaCategory', 'orderService']);
            }, 'createdBy', 'updatedBy'])->find($proposalId))) {
            return redirect()->back()->withError('Proposal not found.');
        }

        $data = [
            'proposal'           => $proposal,
            'mediaCategoriesCB'  => \App\MediaCategory::categoriesCB(['0' => '']),
            'existingServicesCB' => ['0' => 'Attach to entire proposal'] + $proposal->services()->orderBy('name')->pluck('name', 'id')->toArray(),
        ];

        return view('proposal.proposal_media', $data);
    }

    // media upload and delete

    public function ajaxUploadMedia(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'proposal_id'       => $request->proposal_id,
                    'media_category_id' => $request->media_category_id,
                    'order_service_id'  => $request->order_service_id,
                    'description'       => $request->description,
                    'admin_only'        => $request->admin_only,
                ],
                [
                    'proposal_id'       => 'required|positive',
                    'media_category_id' => 'required|positive',
                    'order_service_id'  => 'nullable|zeroOrPositive',
                    'description'       => 'nullable|plainText',
                    'admin_only'        => ['regex:/^("on"|"off"|\'on\'|\'off\'|true|false|"true"|"false"|\'true\'|\'false\'|1|0)$/'],
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'error'   => true,
                    'message' => $validator->messages()->first(),
                    'code'    => 500,
                ];
            } else if (!$proposal = Proposal::find($request->proposal_id)) {
                $response = [
                    'error'   => true,
                    'message' => 'Proposal not found.',
                    'code'    => 500,
                ];
            } else {
                try {
                    $fileFieldName = 'mediafile';
                    $destinationPath = '/media/order_media/';

                    $response = $this->_uploadFile($request, $fileFieldName, $destinationPath);

                    if (!empty($result['error'])) {
                        $response = [
                            'error'   => true,
                            'message' => $response['message'],
                            'code'    => 500,
                        ];
                    } else {
                        $data = [
                            'order_id'           => $proposal->id,
                            'order_service_id'   => !empty($request->order_service_id)? $request->order_service_id : null,
                            'media_category_id'  => $request->media_category_id,
                            'description'        => $request->description ?? null,
                            'admin_only'         => $request->admin_only == 'true',
                            'file_name'          => $response['fileName'],
                            'original_file_name' => $response['originalFileName'],
                        ];
                        $orderMedia = \App\OrderMedia::create($data);

                        $orderMedia->load(['mediaCategory', 'orderService']);

                        $response['order_media_id'] = $orderMedia->id;
                        $response['media_category'] = $orderMedia->mediaCategory->name;
                        $response['order_service'] = $orderMedia->orderService->name ?? 'Attached to entire proposal';
                        $response['created_at_str'] = $orderMedia->created_at->format('F d, Y');
                        $response['message'] = $response['originalFileName'] . ' successfully uploaded.';
                    }
                } catch (\Exception $e) {
                    $response = [
                        'error'   => true,
                        'message' => $e->getMessage(),
                        'code'    => 500,
                    ];
                }
            }
        } else {
            $response = [
                'error'   => true,
                'message' => 'Solicitud no válida.',
                'code'    => 500,
            ];
        }

        return response()->json($response, $response['code']);
    }

    private function _uploadFile($request, $fileFieldName, $destinationPath, $params = [])
    {
        $oldFileName = (!empty($params['oldFileName'])) ? $params['oldFileName'] : null;

        if (!$request->hasFile($fileFieldName)) {
            return [
                'error'       => true,
                'message'     => 'No file uploaded.',
                'code'        => 500,
                'oldFileName' => $oldFileName,
            ];
        }

        $file = $request->file($fileFieldName);

        if (!$file->isValid()) {
            return [
                'error'       => true,
                'message'     => 'Invalid file.',
                'code'        => 500,
                'oldFileName' => $oldFileName,
            ];
        }

        $originalFileName = $file->getClientOriginalName();

        $fileName = $this->cleanFileName($originalFileName);

        $nameInfo = pathinfo($fileName);
        $ranStr = substr(sha1(time()), 0, 6);

        $fileName = $this->cleanFileName($nameInfo['filename']) . '-' . $ranStr . '.' . $nameInfo['extension'];

        if (env('S3_ACTIVE', false)) {
            $file->move(storage_path() . '/tmp/', $fileName);

            \Storage::disk('s3')->put('public' . $destinationPath . $fileName, file_get_contents(storage_path() . '/tmp/' . $fileName));

            if (empty($params['keepLocalFileIfS3'])) {
                unlink(storage_path() . '/tmp/' . $fileName);
            }
        } else {
            $file->move(public_path() . $destinationPath, $fileName);
        }

        return [
            'error'            => false,
            'code'             => 200,
            'fileName'         => $fileName,
            'originalFileName' => $originalFileName,
            'fullPathFileName' => $destinationPath . $fileName,
            'oldFileName'      => $oldFileName,
        ];
    }

    public function ajaxDeleteMedia(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'order_media_id' => $request->order_media_id,
                ],
                [
                    'order_media_id' => 'required|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    if (!$orderMedia = \App\OrderMedia::find($request->order_media_id)) {
                        $response = [
                            'success' => false,
                            'message' => 'Order media not found.',
                        ];
                    } else {
                        $fileName = $orderMedia->file_name;
                        $originalFileName = $orderMedia->original_file_name;

                        if ($orderMedia->delete()) {
                            $destinationPath = '/media/order_media/';
                            $relPathFilename = $destinationPath . $fileName;

                            if (env('S3_ACTIVE', false)) {
                                if (\Storage::disk('s3')->exists('public' . $relPathFilename)) {
                                    \Storage::disk('s3')->delete('public' . $relPathFilename);
                                }
                            } else {
                                $absPathFileName = public_path() . $relPathFilename;
                                if (file_exists($absPathFileName)) {
                                    unlink($absPathFileName);
                                }
                            }

                            $response = [
                                'success'            => true,
                                'original_file_name' => $originalFileName,
                                'message'            => 'Media removed.',
                            ];
                        } else {
                            $response = [
                                'success' => false,
                                'message' => 'Error deleting row.',
                            ];
                        }
                    }
                } catch (\Exception $e) {
                    $response = [
                        'success' => false,
                        'message' => $e->getMessage(),
                    ];
                }
            }
        } else {
            $response = ['success' => false, 'message' => 'Solicitud no válida.'];
        }

        return response()->json($response);
    }

    public function history(Proposal $proposal)
    {
        if (auth()->user()->isNotAllowTo('view-history-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'proposal' => $proposal,
            'seo'      => [
                'pageTitle' => 'Proposal History',
            ],
        ];

        return view('work_order.history', $data);
    }

    public function print(Proposal $proposal)
    {
        if (auth()->user()->isNotAllowTo('print-work-order')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'proposal' => $proposal,
            'seo'      => [
                'pageTitle' => 'Print Proposal',
            ],
        ];

        return view('work_order.edit', $data);
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$proposal = Proposal::find($request->item_id)) {
            return redirect()->back()->with('error', 'Proposal not found.');
        }

        $proposal->delete();

        return redirect()->route('proposal_list')->withSuccess('Proposal deleted.');
    }

    public function ajaxUploadImage(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $input = $request->all();

            $data = $input['data'];
            $originalFileName = $input['name'];
            $original = (!empty($input['original'])) ? $input['original'] : null;

            $serverDir = storage_path() . '/tmp/';

            list(, $tmp) = explode(',', $data);
            $imgData = base64_decode($tmp);

            $nameInfo = pathinfo($originalFileName);
            $ranStr = substr(sha1(time()), 0, 6);

            $newFileName = $this->cleanFileName($nameInfo['filename']) . '-' . $ranStr . '.' . $nameInfo['extension'];

            $handle = fopen($serverDir . $newFileName, 'w');
            fwrite($handle, $imgData);
            fclose($handle);

            $response = [
                'status'           => 'success',
                'url'              => $newFileName . '?' . time(), // added the time to force update when editting multiple times
                'originalFileName' => $originalFileName,
                'newFileName'      => $newFileName,
            ];

            if (!empty($original)) {
                list(, $tmp) = explode(',', $original);
                $originalData = base64_decode($tmp);

                $original = $nameInfo['filename'] . '-' . $ranStr . '-original' . $nameInfo['extension'];

                $handle = fopen($serverDir . $original, 'w');
                fwrite($handle, $originalData);
                fclose($handle);

                $response['original'] = $original;
            }
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

    public function ajaxDeleteImage(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {

            $id = !empty($request->id) ? $request->id : null;
            $imageUrl = !empty($request->image) ? $request->image : null;

            $chunks = explode('/', $imageUrl);
            $image = array_pop($chunks);

            $serverDir = storage_path() . '/tmp/';

            if (!empty($image) && file_exists($serverDir . $image)) {
                unlink($serverDir . $image);
            }

            if (!empty($id)) {
                // delete from avatars folder and database:
                if ($this->s3) {                                                           // in S3 public folder
                    if (Storage::disk('s3')->exists('public/media/avatars/' . $image)) {
                        Storage::disk('s3')->delete('public/media/avatars/' . $image);
                    }
                } else {                                                                    // in local public folder
                    $avatarsPath = public_path() . '/media/avatars/';
                    if (file_exists($avatarsPath . $image)) {
                        unlink($avatarsPath . $image);
                    }
                }
                Proposal::find($id)->update(array ('avatar' => null));
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

}
