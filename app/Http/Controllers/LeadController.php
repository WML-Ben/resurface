<?php 

namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\Http\Requests\SearchRequest;

use Carbon\Carbon;

use App\Intake;

use App\ServiceCategory;

use App\ContactNew;

use App\Meeting;

use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\File;

use Mail;

use SearchTrait;

use SortableTrait;

class LeadController extends FrontEndBaseController
{
	
    public function index(Request $request)
    {
		
    }
	public function test(Request $request)
	{
		return view('efinder.create');
	}
	public function lists(Request $request)
    {
		$perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

		$data_lead = Intake::where('is_delete',0)->orderBy('id', 'desc')->paginate($perPage);
		
		$array_lead = Intake::where('is_delete',0)->get();

		$detail_appoinment = array();
		$detail_lead = array();
		foreach($array_lead as $item)
		{	
			$string_service = '';
			/*get service and sales*/
			$getSalesManager = \App\User::where('id',$item->id_sales_manager)->first();
			$getSalesPerson = \App\User::where('id',$item->id_sales_person)->first();
			$array_service = $item->id_service;
			$array_service = explode(',',$array_service);
			$getService = \App\ServiceCategory::wherein('id',$array_service)->get();
			foreach($getService as $service)
			{
				$string_service .= $service->name.', ';
			}
			$data_service_sales[$item->id] = array(
					'sales_manager'   =>	!empty($getSalesManager)?$getSalesManager->first_name.' '.$getSalesManager->last_name:'No assigned',
					'sales_person'	  =>	!empty($getSalesPerson)?$getSalesPerson->first_name.' '.$getSalesPerson->last_name:'',
					'service'		  =>	!empty($string_service)?rtrim($string_service,', '):'No services',
			);
			/*end*/
			$detail_lead[$item->id] = Meeting::where('lead_id',$item->id)->orderBy('id','desc')->limit(1)->get();
			if(!empty($detail_lead[$item->id]))
			{
				foreach($detail_lead[$item->id] as $item_appoint)
				{
					$getProperty = \App\Property::where('id',$item_appoint->id_properties)->first();
					if(!empty($getProperty))
					{
						$getCompany = \App\Company::where('id',$getProperty->company_id)->first();
					}
					$detail_properties[$item->id][$item_appoint->id] = array(
						'id_lead' => $item_appoint->id,
						'properties_name' => 	!empty($getProperty)?$getProperty->name:'',
						'company_name'	  => 	!empty($getCompany)?$getCompany->name:'',
					);
				}
			}
		}

		$data = [
            'lead_data' 			=>  $data_lead,
			'property_data' 		=>	$detail_properties,
			'detail_lead'			=>  $detail_lead,
			'data_service_sales' 	=>	$data_service_sales,
            'needle'        => null,
			'seo'           => [
                'pageTitle' => 'Leads',
            ],
        ];
        return view('intake.index', $data);
    }
	public function search(SearchRequest $request)
    {
        /*if (auth()->user()->isNotAllowTo('search-company')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }*/

        $this->validate($request, [
            'needle' => 'min:3',
        ]);

        $needle = $request->needle;
        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $intake = Intake::search($needle)->sortable()->paginate($perPage);
		$array_lead = Intake::where('is_delete',0)->get();
		
		$detail_appoinment = array();
		$detail_lead = array();
		foreach($array_lead as $item)
		{	
			$string_service = '';
			/*get service and sales*/
			$getSalesManager = \App\User::where('id',$item->id_sales_manager)->first();
			$getSalesPerson = \App\User::where('id',$item->id_sales_person)->first();
			$array_service = $item->id_service;
			$array_service = explode(',',$array_service);
			$getService = \App\ServiceCategory::wherein('id',$array_service)->get();
			foreach($getService as $service)
			{
				$string_service .= $service->name.', ';
			}
			$data_service_sales[$item->id] = array(
					'sales_manager'   =>	!empty($getSalesManager)?$getSalesManager->first_name.' '.$getSalesManager->last_name:'No assigned',
					'sales_person'	  =>	!empty($getSalesPerson)?$getSalesPerson->first_name.' '.$getSalesPerson->last_name:'',
					'service'		  =>	!empty($string_service)?rtrim($string_service,', '):'No services',
			);
			/*end*/
			$detail_lead[$item->id] = Meeting::where('lead_id',$item->id)->orderBy('id','desc')->limit(1)->get();
			if(!empty($detail_lead[$item->id]))
			{
				foreach($detail_lead[$item->id] as $item_appoint)
				{
					$getProperty = \App\Property::where('id',$item_appoint->id_properties)->first();
					if(!empty($getProperty))
					{
						$getCompany = \App\Company::where('id',$getProperty->company_id)->first();
					}
					$detail_properties[$item->id][$item_appoint->id] = array(
						'id_lead' => $item_appoint->id,
						'properties_name' => 	!empty($getProperty)?$getProperty->name:'',
						'company_name'	  => 	!empty($getCompany)?$getCompany->name:'',
					);
				}
			}
		}

        $data = [
            'lead_data' 	=>  $intake,
			'property_data' =>	$detail_properties,
			'detail_lead'	=>  $detail_lead,
			'data_service_sales' 	=>	$data_service_sales,
            'needle'                     => $needle,
            'seo'                        => [
                'pageTitle' => 'Lead - Search Results',
            ],
        ];

        return view('intake.index', $data);
    }
	public function createIntakeForm(Request $request)
    {
        if (auth()->user()->isNotAllowTo('create-proposal')) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $property = !empty($request->property_id) ? \App\Property::with(['company'])->find($request->property_id) : null;

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

        return view('intake.create', $data);
    }
	public function storeIntakeForm(Request $request)
    {
		if(empty($request['email']) || empty($request['properties_id'])){
			return redirect()->back()->with('error', 'Email Address or Properties cannot be empty!');
		}

		if(empty($request['date_intake']))
		{
			$date_intake = Carbon::now()->toDateTimeString();
		}
		else
		{
			$date_intake = date('Y-m-d H:i:s',strtotime($request['date_intake']));
		}
		$string_service = '';
		$service_category = '';
		$array_attached_service = array();
		$files_id_service = '';
		$files_id_lead = '';
		$files_id_total = '';
		if(!empty($request['service_id'])){
			$service = $request['service_id'];
			foreach($service as $item)
			{
				$string_service .= $item.',';
			}
			$string_service = rtrim($string_service,',');
			$service_array = ServiceCategory::all()->toArray();
			foreach($service_array as $item)
			{
				if(in_array($item['id'],$service))
				{
					$service_category .= $item['name'].',';
					if(!empty($item['file_attached']))
					{
						$array_attached_service[] = $item['file_attached'];
					}
					if(!empty($item['files_id']))
					{
						$files_id_service .= $item['files_id'].',';
					}
				}
			}
			$files_id_service = rtrim($files_id_service,',');
			$service_category = rtrim($service_category,',');
		}
		$file_attached = $request->file('file_upload');
        $file_attached_path = '';
        if(!empty($file_attached)) {
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
					$files_id_lead .= ','.$file->id;
				}
			}
        }
		$files_id_total = ltrim($files_id_service.$files_id_lead,',');
	   /*end upload file*/
		try 
		{
			if(!empty($request['meeting_date']) && !empty($request['meeting_time']))
			{
				$fullday = $request['meeting_date'].' '. $request['meeting_time'];
				$meeting_date = date('Y-m-d',strtotime($fullday));
				$meeting_time = date('H:i:s',strtotime($fullday));
			}
			$lead_id = 0;

			$check_email = Intake::where('email',$request['email'])->where('properties_id',$request['properties_id'])->first();
			
			if(!empty($check_email))
			{
				$lead_id = $check_email->id;
				if($check_exist_properties > 0)
				{
					if(!empty($request['firstname']))
					{
						Intake::where('email', $request['email'])->update(['first_name' => $request['firstname']]);
					}
					if(!empty($request['lastname']))
					{
						Intake::where('email', $request['email'])->update(['last_name' => $request['lastname']]);
					}
					if(!empty($request['phone']))
					{
						Intake::where('email', $request['email'])->update(['phone' => $request['phone']]);
					}
					if(!empty($request['date_intake']))
					{
						Intake::where('email', $request['email'])->update(['date_intake' => $date_intake]);
					}
					if(!empty($request['id_service']))
					{
						Intake::where('email', $request['email'])->update(['id_service' => $string_service]);
					}
					if(!empty($request['other_service']))
					{
						Intake::where('email', $request['email'])->update(['other_service' => $request['service_other_des']]);
					}
					if(!empty($request['hear_about']))
					{
						Intake::where('email', $request['email'])->update(['hear_about' => $request['hear_about']]);
					}
					if(!empty($request['if_referral']))
					{
						Intake::where('email', $request['email'])->update(['hear_about_other' => $request['if_referral']]);
					}
					if(!empty($request['id_sales_manager']))
					{
						Intake::where('email', $request['email'])->update(['id_sales_manager' => $request['id_sales_manager']]);
					}
					if(!empty($request['id_sales_person']))
					{
						Intake::where('email', $request['email'])->update(['id_sales_person' => $request['id_sales_person']]);
					}
					if(!empty($files_id_total))
					{
						Intake::where('email', $request['email'])->update(['files_id' => $files_id_total]);
					}
					Intake::where('email', $request['email'])->update(['updated_at' => Carbon::now()->toDateTimeString()]);
				}
				else
				{
					$intake = Intake::create([
						'properties_id'				=> !empty($request['properties_id']) ? $request['properties_id'] : 0,
						'first_name'				=> !empty($request['firstname']) ? $request['firstname'] : '',
						'last_name'					=> !empty($request['lastname']) ? $request['lastname'] : '',
						'email'						=> !empty($request['email']) ? $request['email'] : '',
						'phone'						=> !empty($request['phone']) ? $request['phone'] : '',
						'date_intake'   			=> $date_intake,
						'id_service'				=> !empty($string_service) ? $string_service : '',
						'other_service'				=> !empty($request['service_other_des']) ? $request['service_other_des'] : '',
						'hear_about'				=> !empty($request['hear_about']) ? $request['hear_about'] : '',
						'hear_about_other'			=> !empty($request['if_referral']) ? $request['if_referral'] : '',
						'id_sales_manager'			=> !empty($request['id_sales_manager']) ? $request['id_sales_manager'] : 0,
						'id_sales_person'			=> !empty($request['id_sales_person']) ? $request['id_sales_person'] : 0,
						'files_id'					=> $files_id_total,
						'comment'					=>'',
						'created_by'				=> 0,
						'updated_by'				=> 0,
						'is_delete'					=> 0,
						'created_at'				=> Carbon::now()->toDateTimeString(),
						'updated_at'				=> Carbon::now()->toDateTimeString()
					]);
					if($intake)
					{
						$lead_id =  $intake->id;
					}
				}	
			}
			else
			{
				$intake = Intake::create([
					'properties_id'				=> !empty($request['properties_id']) ? $request['properties_id'] : 0,
					'first_name'				=> !empty($request['firstname']) ? $request['firstname'] : '',
					'last_name'					=> !empty($request['lastname']) ? $request['lastname'] : '',
					'email'						=> !empty($request['email']) ? $request['email'] : '',
					'phone'						=> !empty($request['phone']) ? $request['phone'] : '',
					'date_intake'   			=> $date_intake,
					'id_service'				=> !empty($string_service) ? $string_service : '',
					'other_service'				=> !empty($request['service_other_des']) ? $request['service_other_des'] : '',
					'hear_about'				=> !empty($request['hear_about']) ? $request['hear_about'] : '',
					'hear_about_other'			=> !empty($request['if_referral']) ? $request['if_referral'] : '',
					'id_sales_manager'			=> !empty($request['id_sales_manager']) ? $request['id_sales_manager'] : 0,
					'id_sales_person'			=> !empty($request['id_sales_person']) ? $request['id_sales_person'] : 0,
					'files_id'					=> $files_id_total,
					'comment'					=>'',
					'created_by'				=> 0,
					'updated_by'				=> 0,
					'is_delete'					=> 0,
					'created_at'				=> Carbon::now()->toDateTimeString(),
					'updated_at'				=> Carbon::now()->toDateTimeString()
				]);
				if($intake)
				{
					$lead_id =  $intake->id;
				}
			}
			$appointment = Meeting::create([
				'lead_id'					=> $lead_id,
				'id_properties'				=> !empty($request['properties_id']) ? $request['properties_id'] : 0,
				'meeting_date'				=> $meeting_date, 
				'meeting_time' 				=> $meeting_time,
				'created_by'				=> 0,
				'updated_by'				=> 0,
				'is_delete'					=> 0,
				'created_at'				=> Carbon::now()->toDateTimeString(),
				'updated_at'				=> Carbon::now()->toDateTimeString()
			]);
			
			$string_address = '';
			if(!empty($request['address_properties']))
			{
				$string_address .= $request['address_properties'].' ';
			}
			if(!empty($request['city']))
			{
				$string_address .= $request['city'].', ';
			}
			if(!empty($request['state']))
			{
				$string_address .= $request['state'].', ';
			}
			if(!empty($request['zipcode']))
			{
				$string_address .= $request['zipcode'];
			}
			$data_sendmail = array(
				'first_name'				=>!empty($request['firstname']) ? $request['firstname'] : '',
				'meeting_date'				=> $meeting_date,				
				'meeting_time' 				=> $meeting_time,
				'address'					=> $string_address
			);
			
			$email = $request['email'];
			$firstname = $request['firstname'];
			Mail::send('emails.template_email_1', $data_sendmail, function($message) use($email,$firstname,$array_attached_service){
				$message->to($email, $firstname)->subject('You have appointed from AllPaving');
				foreach($array_attached_service as $item)
				{
					$message->attach(public_path('uploads/services/'.$item));
				}
			});

			return redirect()->route('intake_list')->with('success', 'Lead created.');
		}
		catch (\Exception $e) {
			return redirect()->back()->with('error', 'Error.Please try again!');
		}
	}
	
	public function detail($ID)
    {
		if(!empty($ID))
		{
			
			$intake = Intake::where('id',$ID)->first();
			$fullname_sales_managers = '';
			$fullname_sales_person = '';
			if(!empty($intake))
			{
				$service = ServiceCategory::all()->toArray();
				$properties = \App\Property::where('id',$intake->properties_id)->first();
				$data_properties = array();
				if(!empty($properties))
				{
					$state_properties = \App\State::where('id',$properties->state_id)->first();
					$company = \App\Company::where('id',$properties->company_id)->first();
					$sales_manager = \App\User::where('id',$intake->id_sales_manager)->first();
					if($sales_manager)
					{
						$fullname_sales_managers =  $sales_manager->first_name.' '.$sales_manager->last_name;
					}
					$sales_person = \App\User::where('id',$intake->id_sales_person)->first();
					if($sales_person)
					{
						$fullname_sales_person =  $sales_person->first_name.' '.$sales_person->last_name;
					}
					if(!empty($company ))
					{
						$state_company = \App\State::where('id',$company->state_id)->first();
					}
					$data_properties = array(
						'sales_manager'			=> $fullname_sales_managers,
						'sales_person'			=> $fullname_sales_person,
						'id_properties' 		=> $properties->id,
						'properties_name'		=> $properties->name,
						'properties_address'	=> $properties->address,
						'properties_city'		=> $properties->city,
						'properties_state_id'	=> !empty($properties->state_id)?$properties->state_id:'',
						'properties_state'		=> !empty($state_properties)?$state_properties->name:'',
						'properties_zipcode'	=> $properties->zipcode,
						'company_id'			=> !empty($properties->company_id)?$properties->company_id:0,
						'company'				=> !empty($company->name)?$company->name:'',
						'company_address'		=> !empty($company->address)?$company->address:'',
						'company_city'			=> !empty($company->city)?$company->city:'',
						'company_state_id'		=> !empty($company->state_id)?$company->state_id:0,
						'company_state'			=> !empty($state_company)?$state_company->name:'',
						'company_zipcode'		=> !empty($company->zipcode)?$company->zipcode:'',
					);
				}
				$meeting = Meeting::where('lead_id',$ID)->where('id_properties',$intake->properties_id)->orderBy('id','desc')->limit(1)->get()->toArray();
				/*get email template*/
				/*end*/
				/*get File attached*/
				/**/
				$data_file_attached = array();
				if(!empty($intake->files_id))
				{
					$files_id = explode(',',$intake->files_id);
					$files = \App\Files::whereIn('id', $files_id)->get();
					
					foreach($files as $item_file)
					{
						$data_file_attached[] = array(
								"name" => $item_file->path,
								"type" => 'application/pdf',
								"size" => $item_file->size,
								"file" => $item_file->path,
								"data" => array(
									'id'=> $item_file->id,
									"url" => url('/').'/uploads/intake/'.$item_file->path,
									"thumbnail" => url('/').'/uploads/intake/'.$item_file->path,
								),
						
						);
					}
				}
				$data = [
					'lead_data'                  => $intake,
					'properties_data'			 =>$data_properties,
					'lead_detail'				 =>	$meeting,
					'service'					 => $service,
					'email_template'			 => \App\LeadEmailTemplate::all(),
					'data_file_attached'        =>$data_file_attached,
					'needle'                     => null,
					'seo'                        => [
						'pageTitle' => 'Intake - Detail',
					],
				];
				return view('intake.detail', $data);
			}
			else
			{
				return Redirect::to('intake/lists');
			}
		}
		else
		{
			return Redirect::to('intake/lists');
		}
	}
	public function update(Request $request)
    {
		if(!empty($request['id_appointment'])&& !empty($request['lead_id_detail']))
		{
			
			if(!empty($request['email_detail'])) {
				$user = Intake::where('email', $request['email_detail'])->where('properties_id',$request['community_detail'])->whereNotIn('id', [$request['lead_id_detail']])->count();
				if ($user > 0) {
					return redirect()->back()->with('error', 'Email already exists!.');
				}
			}
			if(empty($request['date_intake_detail']))
			{
				$date_intake = Carbon::now()->toDateTimeString();
			}
			else
			{
				$date_intake = date('Y-m-d H:i:s',strtotime($request['date_intake_detail']));
			}
			
			$string_service = '';
			if(!empty($request['id_service_detail'])){
				$service = $request['id_service_detail'];
				foreach($service as $item)
				{
					$string_service .= $item.',';
				}
				$string_service = rtrim($string_service,',');
			}
			try {

				if(!empty($request['first_name_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['first_name' => $request['first_name_detail']]);
				}
				if(!empty($request['first_name_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['last_name' => $request['last_name_detail']]);
				}
				if(!empty($request['email_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['email' => $request['email_detail']]);
				}
				if(!empty($request['phone_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['phone' => $request['phone_detail']]);
				}
				if(!empty($request['hear_about_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['hear_about' => $request['hear_about_detail']]);
				}
				if(!empty($request['hear_about_other_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['hear_about_other' => $request['hear_about_other_detail']]);
				}
				if(!empty($string_service))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['id_service' => $string_service]);
				}
				if(!empty($request['other_service_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['other_service' => $request['other_service_detail']]);
				}
				if(!empty($request['id_sales_manager_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['id_sales_manager' => $request['id_sales_manager_detail']]);
				}
				if(!empty($request['id_sales_person_detail']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['id_sales_person' => $request['id_sales_person_detail']]);
				}
				if(!empty($request['comment']))
				{
					Intake::where('id', $request['lead_id_detail'])->update(['comment' => $request['comment']]);
				}
				
				
				Intake::where('id', $request['lead_id_detail'])->update(['updated_at' =>  Carbon::now()->toDateTimeString()]);
			
				if(!empty($request['meeting_date_detail']) && !empty($request['meeting_time_detail']))
				{
					$fullday = $request['meeting_date_detail'].' '. $request['meeting_time_detail'];
					$meeting_date = date('Y-m-d',strtotime($fullday));
					$meeting_time = date('H:i:s',strtotime($fullday));
					Meeting::where('id', $request['id_appointment'])->update(['meeting_date' => $meeting_date]);
					Meeting::where('id', $request['id_appointment'])->update(['meeting_time' => $meeting_time]);
					Meeting::where('id', $request['id_appointment'])->update(['updated_at' =>  Carbon::now()->toDateTimeString()]);
				};
				return redirect()->back()->with('success','Intake has been updated succefully.');
			}
			catch (\Exception $e) {
				return redirect()->back()->with('error', 'Error.Please try again!');
			}

		}
		else
		{
			return Redirect::to('intake/lists');
		}
	}
	public function sentEmailtoCustomer(Request $request){
		if(!empty($request['email']))
		{
			$data_sendmail = array(
				'firstname'=>$request['content'],
				'content'  =>!empty($request['content'])?$request['content']:'',
				'email'	   =>!empty($request['email'])?$request['email']:'',
				'subject'  =>!empty($request['subject'])?$request['subject']:''
			);
			Mail::send('emails.new_template_email', $data_sendmail, function($message) use($data_sendmail){
				$message->to($data_sendmail['email'], $data_sendmail['firstname'])->subject($data_sendmail['subject']);
			});
			$response = [
				'status' => true,
				'message' => 'Email sent to '.$request['email'].' !.',
			];
		}
		else
		{
			$response = [
				'status' => false,
				'message' => 'Email cannot be empty!.',
			];
		}
		return $response;
	}
	public function addCommenttoCumstomer(Request $request)
	{
		if(!empty($request['id_appointment']))
		{
			if(!empty($request['comment']))
			{
				Meeting::where('id', $request['id_appointment'])->update(['comment' => $request['comment']]);
			}
			$response = [
				'status' => true,
				'message' => 'You have added comment succefully!',
			];
		}
		else
		{
			$response = [
				'status' => false,
				'message' => 'An error occurred. Please try again!.',
			];
		}
		return $response;
	}
}
