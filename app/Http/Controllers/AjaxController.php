<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use ThrottleTrait;
use StringTrait;
use Carbon\Carbon;

class AjaxController extends CommonController
{

    use StringTrait, ThrottleTrait;

    public function setTimezone(Request $request)
    {
        if ($request->isMethod('post') && !empty($request->timezone)) {
            session()->put('timezone', $request->timezone);
        }
    }

    public function uploadTinyMceImage(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!empty($_FILES['mceImage'])) {
                $originalFileName = $_FILES['mceImage']['name'];
                $nameInfo = pathinfo($originalFileName);
                // check if image:
                if (@is_array(getimagesize($_FILES['mceImage']['tmp_name']))) {
                    $ranStr = substr(sha1(time()), 0, 6);
                    $newFileName = $this->cleanFileName($nameInfo['filename']) . '-' . $ranStr . '.' . $nameInfo['extension'];
                    if (move_uploaded_file($_FILES['mceImage']['tmp_name'], storage_path() . '/tmp/' . $newFileName)) {
                        if ($this->s3) {
                            \Storage::disk('s3')->put('media/warehouse/' . $newFileName, file_get_contents(storage_path() . '/tmp/' . $newFileName));
                            unlink(storage_path() . '/tmp/' . $newFileName);
                        } else {
                            rename(storage_path() . '/tmp/' . $newFileName, public_path() . '/media/warehouse/' . $newFileName);
                        }
                        $response = [
                            'success'  => true,
                            'url'      => $this->mediaUrl . '/warehouse/' . $newFileName,
                            'fileName' => $newFileName,
                        ];
                    } else {
                        $response = [
                            'success' => false,
                            'error'   => 'File could not be uploaded.',
                        ];
                    }
                } else {
                    $response = [
                        'success' => false,
                        'message' => 'File is not an image.',
                    ];
                }
            } else {
                $response = [
                    'success' => false,
                    'message' => 'Empty uploaded file.',
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Solicitud no v&aacute;lida.',
            ];
        }

        return json_encode($response);
    }

    public function uploadInsertedImage(Request $request)
    {
        list($fileName, $ext) = explode('.', $_FILES['file']['name']);
        $filename = str_replace('.', '', uniqid($fileName . '-', true)) . '.' . $ext;

        if (move_uploaded_file($_FILES['file']['tmp_name'], storage_path() . '/tmp/' . $filename)) {
            if ($this->s3) {
                \Storage::disk('s3')->put('media/warehouse/' . $filename, file_get_contents(storage_path() . '/tmp/' . $filename));
                unlink(storage_path() . '/tmp/' . $filename);
            } else {
                rename(storage_path() . '/tmp/' . $filename, public_path() . '/media/warehouse/' . $filename);
            }

            $input = $request->all();

            $response = [
                'success'   => true,
                'url'       => $this->mediaUrl . '/warehouse/' . $filename,
                'fileName'  => $filename,
                'link'      => $input['link'],
                'altText'   => $input['altText'],
                'newWindow' => $input['newWindow'],
            ];
        } else {
            $response = [
                'success' => false,
                'error'   => 'File could not be uploaded.',
            ];
        }

        return response()->json($response);
    }

    public function fetchStates(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!$countryId = $request->country_id) {
                if ($request->country_is_required) {
                    $response = [
                        'success' => false,
                        'message' => 'country_id is empty.',
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'data'    => [],
                    ];
                }

            } else {
                if (!$states = \App\State::statesCB($countryId)) {
                    $response = [
                        'success' => false,
                        'message' => 'No states found.',
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'data'    => $states ?? [],
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

    public function fetchCompanyUsers(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!$companyId = $request->company_id) {
                if ($request->company_is_required) {
                    $response = [
                        'success' => false,
                        'message' => 'company_id is empty.',
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'data'    => [],
                    ];
                }

            } else {
                if (!$company = \App\Company::find($companyId)) {
                    $response = [
                        'success' => false,
                        'message' => 'No company found.',
                    ];
                } else {
                    $usersCB = $company->getUsersCB();

                    $response = [
                        'success' => true,
                        'data'    => $usersCB ?? [],
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

    public function fetchCompanyDetails(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!($companyId = $request->company_id) || !($company = \App\Company::with(['users', 'category'])->find($companyId))) {
                $response = [
                    'success' => false,
                    'message' => 'Company not found.',
                ];
            } else {
                $response = [
                    'success'           => true,
                    'company'           => $company,
                    'billing_states_cb' => !empty($company->billing_country_id) ? \App\State::statesCB($company->billing_country_id) : [],
                    'company_users_cb'  => $company->getUsersCB() ?? [],
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function fetchPropertyDetails(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!($propertyId = $request->property_id) || !($property = \App\Property::with(['company', 'manager', 'owner'])->find($propertyId))) {
                $response = [
                    'success' => false,
                    'message' => 'Property not found.',
                ];
            } else {
                $response = [
                    'success'     => true,
                    'property'    => $property,
                    'states_cb'   => !empty($property->country_id) ? \App\State::statesCB($property->country_id) : [],
                    'managers_cb' => $property->company->getUsersCB() ?? [],
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function fetchUserEmailAndPhone(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!($userId = $request->user_id) || !($user = \App\User::active()->find($userId))) {
                $response = [
                    'success' => false,
                    'message' => 'User not found.',
                ];
            } else {
                $response = [
                    'success'   => true,
                    'email'     => $user->email,
                    'alt_email' => $user->alt_email,
                    'phone'     => $user->phone,
                    'alt_phone' => $user->alt_phone,
                ];
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'Invalid request.',
            ];
        }

        return response()->json($response);
    }

    public function fetchServices(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!$serviceCategoryId = $request->service_category_id) {
                if ($request->service_is_required) {
                    $response = [
                        'success' => false,
                        'message' => 'service_category_id is empty.',
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'data'    => [],
                    ];
                }
            } else {
                if (!$services = \App\Service::servicesCB($serviceCategoryId)) {
                    $response = [
                        'success' => false,
                        'message' => 'No states found.',
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'data'    => $services ?? [],
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

    public function fetchSubContractor(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            if (!$subContractorId = $request->sub_contractor_id) {
                if ($request->sub_contractor_is_required) {
                    $response = [
                        'success' => false,
                        'message' => 'sub_contractor_id is empty.',
                    ];
                } else {
                    $response = [
                        'success' => true,
                        'data'    => [],
                    ];
                }
            } else {
                if (!$subContractor = \App\SubContractor::active()->find($subContractorId)) {
                    $response = [
                        'success' => false,
                        'message' => 'Sub contractor not found.',
                    ];
                } else {
                    $response = [
                        'success'        => true,
                        'sub_contractor' => $subContractor,
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
	/*Intake*/
	public function ajaxGetInformationProperty(Request $request){
		if ($request->isMethod('post') && $request->ajax()) {
			if(!empty($request['id_properties']))
			{
				$property = \App\Property::where('id',$request['id_properties'])->get()->toArray();
				$company_id = $property[0]['company_id'];
				$manager_id = $property[0]['manager_id'];
				$contact =  \App\ContactNew::where('id',$manager_id)->get()->toArray();
				$company =  \App\Company::where('id',$company_id)->get()->toArray();
				$state_properties = \App\State::where('id',$property[0]['state_id'])->get()->toArray();
				$state_company = \App\State::where('id',$company[0]['state_id'])->get()->toArray();
				$data = array(
					'id_properties' 		=> $request['id_properties'],
					'properties_name'		=> !empty($property[0]['name'])?$property[0]['name']:'',
					'properties_address'	=> !empty($property[0]['address'])?$property[0]['address']:'',
					'properties_city'		=> !empty($property[0]['city'])?$property[0]['city']:'',
					'properties_state'		=> !empty($state_properties[0]['name'])?$state_properties[0]['name']:'',
					'properties_zipcode'	=> !empty($property[0]['zipcode'])?$property[0]['zipcode']:'',
					'company_id'			=> !empty($company_id)?$company_id:0,
					'company'				=> !empty($company[0]['name'])?$company[0]['name']:'',
					'company_address'		=> !empty($company[0]['address'])?$company[0]['address']:'',
					'company_city'			=> !empty($company[0]['city'])?$company[0]['city']:'',
					'company_state'			=> !empty($state_company[0]['name'])?$state_company[0]['name']:'',
					'company_zipcode'		=> !empty($company[0]['zipcode'])?$company[0]['zipcode']:'',
				);
				return response()->json($data);
			}
		}
	}
	public function getLeadEmailTemplateByID(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
			if(!empty($request['id_template']))
			{
				$template = \App\LeadEmailTemplate::where('id',$request['id_template'])->first();
				return $template->body;
			}
			return '';
        }
    }
	public function uploadFilesAttacheLead(Request $request)
	{
		if(!empty($request->lead_id_detail))
		{
			$file_attached = $request->file('files');
			if(!empty($file_attached))
			{
				$get_files_id = \App\Intake::where('id',$request->lead_id_detail)->first();
				$files_id =  $get_files_id->files_id;
				foreach($file_attached as $item)
				{
					if(!empty($item)) {
						$extension = $item->getClientOriginalExtension();
						$original_filename = $item->getClientOriginalName();
						$file_name_client = explode('.', $original_filename)[0];
						
						$filename = $file_name_client.'_'.time().'.'.$extension;
						
						Storage::disk('public_intake')->put($filename, File::get($item));
						$mime = $item->getClientMimeType();
						$size = $item->getClientSize();
						$file = \App\Files::create([
							'type_file' 	=> $mime,
							'name'			=>$original_filename,
							'extension'		=>$extension,
							'size'			=>$size,
							'path'			=>$filename,
							'created_at' 	=>Carbon::now()->toDateTimeString(),
							'created_by'	=>0,
							'updated_by'	=>0,
							'updated_at'	=>Carbon::now()->toDateTimeString(),
							'is_delete'		=>0
						]);
						$files_id .= ','.$file->id;
					}
				}
				
				$update = \App\Intake::where('id',$request->lead_id_detail)->update(['files_id'=>ltrim($files_id,',')]);
				if($update)
				{
					return $response = [
						'status' => true,
						'message' => 'You have uploades files successfully!',
					];
				}
			}
		}
		return $response = [
				'status' => false,
				'message' => 'An error occurred. Please try again!',
			];
	}
	public function ajaxDeleteFileLead(Request $request)
	{
		if(!empty($request->id_lead) && !empty($request->id_file))
		{
			$get_id_file = \App\Intake::where('id',$request->id_lead)->first();
			$files_id = '';
			if(!empty($get_id_file->files_id))
			{
				$array_files_id = explode(',',$get_id_file->files_id);
				foreach($array_files_id as $key => $value)
				{
					if($value == $request->id_file)
					{
						unset($array_files_id[$key]);
					}
				}
				$files_id = implode(',',$array_files_id);
				$success = \App\Intake::where('id',$request->id_lead)->update(['files_id' => $files_id]);
				if($success)
				{
					return $response = [
						'status' => true,
						'message' => 'You have removed this file successfully!',
					];
				}
			}
		}
		return $response = [
				'status' => false,
				'message' => 'An error occurred. Please try again!',
			];
	}
}
