<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Contact;
use App\Http\Requests\ContactRequest;

use ImageTrait;
use StringTrait;

class ContactsController extends FrontEndBaseController
{
    use ImageTrait, StringTrait;

    public function index(Request $request)
    {
        if (auth()->user()->isNotAllowTo('list-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $contacts = Contact::sortable()->paginate($perPage);

        $data = [
            'contacts' => $contacts,
            'needle'   => null,
            'seo'      => [
                'pageTitle' => 'Contacts',
            ],
        ];

        return view('contact.index', $data);
    }

    public function search(Request $request)
    {
        if (auth()->user()->isNotAllowTo('search-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $contacts = Contact::search($needle)->sortable()->paginate($perPage);

        $data = [
            'contacts' => $contacts,
            'needle'   => $needle,
            'seo'      => [
                'pageTitle' => 'Contacts',
            ],
        ];

        return view('contact.index', $data);
    }

    public function create()
    {
        if (auth()->user()->isNotAllowTo('create-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $data = [
            'json_html_property_management_companies_cb' => \App\Company::jsonHtmlCompaniesCB(),
            'categoriesCB'                               => \App\UserCategory::categoriesCB(),
            'countriesCB'                                => \App\Country::countriesCB(['0' => '']),
            'statesCB'                                   => \App\State::statesCB(231, ['0' => '']),
            'seo'                                        => [
                'pageTitle' => 'New Contact',
            ],
        ];

        return view('contact.create', $data);
    }

    public function store(ContactRequest $request)
    {
        if (auth()->user()->isNotAllowTo('create-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), (new Contact())->dates);

        try {
            \DB::transaction(function () use ($inputs) {
                Contact::create($inputs);
            });
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage());
        }

        return redirect()->route('contact_list')->with('success', 'Contact created.');
    }

    public function ajaxStore(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-contact')) {
            $response = [
                'success' => false,
                'message' => self::ERROR_NOT_ENOUGH_PRIVILEGES,
            ];
        } else if ($request->isMethod('post') && $request->ajax()) {
            $validator = \Validator::make(
                [
                    'first_name'  => $request->new_manager_first_name,
                    'last_name'   => $request->new_manager_last_name,
                    'email'       => $request->new_manager_email,
                    'phone'       => $request->new_manager_phone,
                    'category_id' => $request->new_manager_category_id,
                    'company_id'  => $request->company_id,
                ],
                [
                    'first_name'  => 'required|personName',
                    'last_name'   => 'required|personName',
                    'email'       => 'nullable|email',
                    'phone'       => 'required|phone',
                    'category_id' => 'required|positive',
                    'company_id'  => 'nullable|positive',
                ]
            );

            if ($validator->fails()) {
                $response = [
                    'success' => false,
                    'message' => $validator->messages()->first(),
                ];
            } else {
                try {
                    \DB::transaction(function () use ($request, & $manager) {
                        $data = [
                            'first_name'  => $request->new_manager_first_name,
                            'last_name'   => $request->new_manager_last_name,
                            'email'       => $request->new_manager_email,
                            'phone'       => $request->new_manager_phone,
                            'category_id' => $request->new_manager_category_id,
                            'company_id'  => $request->company_id,
                        ];

                        $manager = Contact::create($data);
                    });
                    $response = [
                        'success' => true,
                        'manager' => $manager,
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

    public function show(Contact $contact)
    {
        if (auth()->user()->isNotAllowTo('show-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        dd($contact);
    }

    public function edit(Contact $contact)
    {
        if (auth()->user()->isNotAllowTo('update-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$contact) {
            return redirect()->back()->with('error', 'Contact not found.');
        }

        if (!empty($contact->role->name) && !auth()->user()->hasRole($contact->role->name)) {
            return redirect()->back()->with('error', 'You cannot modify a contact with a higher role than yours.');
        }

        $data = [
            'contact'                                    => $contact,
            'json_html_property_management_companies_cb' => \App\Company::jsonHtmlCompaniesCB(),
            'categoriesCB'                               => \App\UserCategory::categoriesCB(),
            'countriesCB'                                => \App\Country::countriesCB(['0' => '']),
            'statesCB'                                   => \App\State::statesCB($contact->country_id ?? 231, ['0' => '']),
            'seo'                                        => [
                'pageTitle' => 'Edit: ' . $contact->fullName,
            ],
        ];

        return view('contact.edit', $data);
    }

    public function update(Contact $contact, ContactRequest $request)
    {
        if (auth()->user()->isNotAllowTo('update-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $inputs = $this->convertUsFormatStringDatesToCarbon($request->all(), $contact->dates);

        $contact->update($inputs);

        return redirect()->route('contact_list')->withSuccess('Contact updated.');
    }

    public function inlineUpdate(Request $request)
    {
        if (auth()->user()->isNotAllowTo('update-contact')) {
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

            /*
             * The first argument passed to the make method is the data under validation.
             * The second argument is the validation rules that should be applied to the data.
             */

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
                        $model = Contact::find($id);

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

    public function toggleStatus(Contact $contact)
    {
        if (auth()->user()->isNotAllowTo('update-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $contact->disabled = !$contact->disabled;
        $contact->save();

        return redirect()->back()->with('success', 'Contact status has been toggled.');
    }

    public function destroy(Request $request)
    {
        if (auth()->user()->isNotAllowTo('delete-contact')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        if (!$contact = Contact::find($request->item_id)) {
            return redirect()->back()->with('error', 'Contact not found.');
        }

        $contact->delete();

        return redirect()->route('contact_list')->withSuccess('Contact deleted.');
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
                Contact::find($id)->update(array ('avatar' => null));
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

    public function ajaxDeleteSignature(Request $request)
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
                    if (Storage::disk('s3')->exists('public/media/signatures/' . $image)) {
                        Storage::disk('s3')->delete('public/media/signatures/' . $image);
                    }
                } else {                                                                    // in local public folder
                    $avatarsPath = public_path() . '/media/signatures/';
                    if (file_exists($avatarsPath . $image)) {
                        unlink($avatarsPath . $image);
                    }
                }
                Contact::find($id)->update(['signature' => null]);
            }

            $response = ['status' => 'success'];
        } else {
            $response = ['status' => 'error', 'message' => 'Invalid request.'];
        }

        return response()->json($response);
    }

}
