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

class IntakeController extends FrontEndBaseController
{
	
    public function index(Request $request)
    {
		
    }
	public function test()
    {
		
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
			$detail_lead[$item->id] = Meeting::where('lead_id',$item->id)->get();
			if(!empty($detail_lead[$item->id]))
			{
				foreach($detail_lead[$item->id] as $item_appoint)
				{
					$getProperty = \App\Property::where('id',$item_appoint->id_properties)->first();
					if(!empty($getProperty))
					{
						$getCompany = \App\Company::where('id',$getProperty->company_id)->first();
					}
					
					$getSalesManager = \App\User::where('id',$item_appoint->id_sales_manager)->first();
					
					$getSalesPerson = \App\User::where('id',$item_appoint->id_sales_person)->first();
					
					$detail_properties[$item->id][$item_appoint->id] = array(
						'id_lead' => $item_appoint->id,
						'properties_name' => 	!empty($getProperty)?$getProperty->name:'',
						'company_name'	  => 	!empty($getCompany)?$getCompany->name:'ss',
						'sales_manager'   =>	!empty($getSalesManager)?$getSalesManager->first_name.' '.$getSalesManager->last_name:'',
						'sales_person'	  =>	!empty($getSalesPerson)?$getSalesPerson->first_name.' '.$getSalesPerson->last_name:'',
					);
				}
			}
		}
		$data = [
            'lead_data' 	=>  $data_lead,
			'property_data' =>	$detail_properties,
			'detail_lead'	=>  $detail_lead,
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

        $data = [
            'lead_data'                  => $intake,
            'needle'                     => $needle,
            'seo'                        => [
                'pageTitle' => 'Lead - Search Results',
            ],
        ];

        return view('intake.index', $data);
    }
	public function create(Request $request)
    {
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
				}
			}
			$service_category = rtrim($service_category,',');
		}
		$file_attached = $request->file('file_upload');
        $file_attached_path = '';
        if(!empty($file_attached)) {
            $extension = $file_attached->getClientOriginalExtension();
            Storage::disk('public_intake')->put($file_attached->getFilename() . '.' . $extension, File::get($file_attached));
			$file_attached->mime              = $file_attached->getClientMimeType();
            $file_attached->original_filename = $file_attached->getClientOriginalName();
            $file_attached->filename          = $file_attached->getFilename() . '.' . $extension;
            $file_attached_path = '/uploads/intake/' . $file_attached->filename;
        }
	
		try 
		{
			if(!empty($request['meeting_date']) && !empty($request['meeting_time']))
			{
				$fullday = $request['meeting_date'].' '. $request['meeting_time'];
				$meeting_date = date('Y-m-d',strtotime($fullday));
				$meeting_time = date('H:i:s',strtotime($fullday));
			}
			$lead_id = 0;
			if(!empty($request['email']))
			{
				$check_email = Intake::where('email',$request['email'])->first();
				if(!empty($check_email))
				{
					$lead_id = $check_email->id;
					if(!empty($request['first_name']))
					{
						Intake::where('email', $request['email'])->update(['first_name' => $request['first_name']]);
					}
					if(!empty($request['first_name']))
					{
						Intake::where('email', $request['email'])->update(['last_name' => $request['last_name']]);
					}
					if(!empty($request['first_name']))
					{
						Intake::where('email', $request['email'])->update(['phone' => $request['phone']]);
					}
					Intake::where('email', $request['email'])->update(['updated_at' => Carbon::now()->toDateTimeString()]);
				}
				else
				{
					$intake = Intake::create([
						'first_name'				=> !empty($request['firstname']) ? $request['firstname'] : '',
						'last_name'					=> !empty($request['lastname']) ? $request['lastname'] : '',
						'email'						=>	!empty($request['email']) ? $request['email'] : '',
						'phone'						=> !empty($request['phone']) ? $request['phone'] : '',
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
 
			$appointment = Meeting::create([
				'lead_id'					=>$lead_id,
				'id_properties'				=> !empty($request['properties_id']) ? $request['properties_id'] : 0,
				'date_intake'   			=> $date_intake,
				'meeting_date'				=> $meeting_date, 
				'meeting_time' 				=> $meeting_time,
				'id_service'				=> !empty($string_service) ? $string_service : '',
				'other_service'				=> !empty($request['service_other_des']) ? $request['service_other_des'] : '',
				'hear_about'				=> !empty($request['hear_about']) ? $request['hear_about'] : '',
				'hear_about_other'			=> !empty($request['if_referral']) ? $request['if_referral'] : '',
				'id_sales_manager'			=> !empty($request['id_sales_manager']) ? $request['id_sales_manager'] : 0,
				'id_sales_person'			=> !empty($request['id_sales_person']) ? $request['id_sales_person'] : 0,
				'file_attached'				=> !empty($file_attached_path) ? $file_attached_path : '',
				'comment'					=>'',
				'created_by'				=> 0,
				'updated_by'				=> 0,
				'is_delete'					=> 0,
				'created_at'				=> Carbon::now()->toDateTimeString(),
				'updated_at'				=> Carbon::now()->toDateTimeString()
			]);
			/*$data_sendmail = array(
								   'date_intake'        =>$request['date_intake'],
								   'firstname'			=>$request['firstname'],
								   'lastname'			=>$request['lastname'],
								   'email'				=>$request['email'],
								   'phone' 				=>$request['phone'],
								   'community'			=>$request['community'],
								   'street_address'		=>$request['address'],
								   'state'				=>$request['state'],
								   'city'				=>$request['city'],
								   'zipcode'			=>$request['zipcode'],
								   'name_company'		=>$request['name_company'],
								   'street_address_com'	=>$request['street_address_com'],
								   'state_com'			=>$request['state_com'],
								   'city_com'			=>$request['city_com'],
								   'zipcode_com'		=>$request['zipcode_com'],
								   'hear_about' 		=>$request['hear_about'],
								   'if_referral'		=>$request['if_referral'],
								   'service_category' 	=>$service_category,
								   'service_other_des'  =>$request['service_other_des']
								   
							);			
			Mail::send('layouts.mail', $data_sendmail, function($message) use ($avata_path) {
				$message->to('nguyenhoangthai391995@gmail.com', 'Intake')
				->subject('New Intake has been created!')
				->attach( public_path() . $avata_path);
			});*/
			$response = [
				'status' => true,
				'message' => 'Intake has been created successfully!.',
			];
		}
		catch (\Exception $e) {
			$response = [
				'status' => false,
				'message' => $e->getMessage(),
			];
		}
		return response()->json($response);
	}
	/*public function detail($ID)
    {
		if(!empty($ID))
		{
			$meeting = Meeting::where('id',$ID)->first();
			if(!empty($meeting))
			{
				$intake = Intake::where('id',$meeting->lead_id)->first();
				$lead_data = array(
					'first_name' => $intake->first_name,
					'last_name' =>	$intake->first_name,
					'phone' =>$intake->phone,
					'email' =>$intake->email
				);
				$service = ServiceCategory::all()->toArray();
				$properties = \App\Property::where('id',$meeting->id_properties)->first();
				$data_properties = array();
				if(!empty($properties))
				{
					$state_properties = \App\State::where('id',$properties->state_id)->first();
					$company = \App\Company::where('id',$properties->company_id)->first();
					$sales_manager = \App\User::where('id',$meeting->id_sales_manager)->first();
					if(!empty($company ))
					{
						$state_company = \App\State::where('id',$company->state_id)->first();
					}
					$data_properties = array(
						'sales_manager'			=> $sales_manager->first_name.' '.$sales_manager->last_name,
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
				$data = [
					'lead_data'                  => $intake,
					'properties_data'			 =>$data_properties,
					'lead_detail'				 =>	$meeting,
					'service'					 => $service,
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
	}*/
	public function detail($ID)
    {
		if(!empty($ID))
		{
			
			$intake = Intake::where('id',$ID)->first();
			if(!empty($intake))
			{
				$meeting = Meeting::where('lead_id',$ID)->get()->toArray();
				
				if(!empty($meeting[0]))
				{
					$service = ServiceCategory::all()->toArray();
					$properties = \App\Property::where('id',$meeting[0]['id_properties'])->first();
					$data_properties = array();
					if(!empty($properties))
					{
						$state_properties = \App\State::where('id',$properties->state_id)->first();
						$company = \App\Company::where('id',$properties->company_id)->first();
						$sales_manager = \App\User::where('id',$meeting[0]['id_sales_manager'])->first();
						if(!empty($company ))
						{
							$state_company = \App\State::where('id',$company->state_id)->first();
						}
						$data_properties = array(
							'sales_manager'			=> $sales_manager->first_name.' '.$sales_manager->last_name,
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
				}
				$data = [
					'lead_data'                  => $intake,
					'properties_data'			 =>$data_properties,
					'lead_detail'				 =>	$meeting,
					'service'					 => $service,
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
				$user = Intake::where('email', $request['email_detail'])->whereNotIn('id', [$request['lead_id_detail']])->first();
				if (!empty($user)) {
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
				Intake::where('id', $request['lead_id_detail'])->update(['updated_at' =>  Carbon::now()->toDateTimeString()]);
				Meeting::where('id', $request['id_appointment'])->update(['date_intake' => $date_intake]);
				if(!empty($request['community_detail']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['id_properties' => $request['community_detail']]);
				}
				if(!empty($request['hear_about_detail']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['hear_about' => $request['hear_about_detail']]);
				}
				if(!empty($request['hear_about_other_detail']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['hear_about_other' => $request['hear_about_other_detail']]);
				}
				if(!empty($string_service))
				{
					Meeting::where('id', $request['id_appointment'])->update(['id_service' => $string_service]);
				}
				if(!empty($request['other_service_detail']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['other_service' => $request['other_service_detail']]);
				}
				if(!empty($request['meeting_date_detail']) && !empty($request['meeting_time_detail']))
				{
					$fullday = $request['meeting_date_detail'].' '. $request['meeting_time_detail'];
					$meeting_date = date('Y-m-d',strtotime($fullday));
					$meeting_time = date('H:i:s',strtotime($fullday));
					Meeting::where('id', $request['id_appointment'])->update(['meeting_date' => $meeting_date]);
					Meeting::where('id', $request['id_appointment'])->update(['meeting_time' => $meeting_time]);
				}
				if(!empty($request['id_sales_manager_detail']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['id_sales_manager' => $request['id_sales_manager_detail']]);
				}
				if(!empty($request['id_sales_person_detail']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['id_sales_person' => $request['id_sales_person_detail']]);
				}
				if(!empty($request['comment']))
				{
					Meeting::where('id', $request['id_appointment'])->update(['comment' => $request['comment']]);
				}
				Meeting::where('id', $request['id_appointment'])->update(['updated_at' =>  Carbon::now()->toDateTimeString()]);
			}
			catch (\Exception $e) {
			}
			return redirect()->back()->withSuccess('Intake has been updated succefully.');
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
				'content'=>$request['content'],
			);
			Mail::send('emails.email_from_detail_intake', $data_sendmail, function($message){
				$message->to('nguyenhoangthai391995@gmail.com', 'thai')->subject('New notification from admin');
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
