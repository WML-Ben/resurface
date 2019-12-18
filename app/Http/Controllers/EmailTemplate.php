<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\LeadEmailTemplate;

use Carbon\Carbon;

class EmailTemplate extends FrontEndBaseController
{
    public function index(Request $request)
    {
		$data = [
			'email_template' => \App\LeadEmailTemplate::all()
		];
		return view('email_template_lead.index',$data);
    }
	public function create()
	{
		return view('email_template_lead.create');
	}
	public function store(Request $request)
	{
		LeadEmailTemplate::create([
			'subject'		=> !empty($request['name'])?$request['name']:'',
			'body'			=> !empty($request['content_send_email'])?$request['content_send_email']:'',
			'created_at'	=>	Carbon::now()->toDateTimeString(),
			'updated_at'	=>	Carbon::now()->toDateTimeString()
		]);
		return redirect()->back()->withSuccess('Email Template has been created successfully!');
	}
	public function update(Request $request)
	{
		if(empty($request['id_email']))
		{
			return redirect()->back()->with('error', 'Error.Please try again!'); 
		}
		if(!empty($request['name']))
		{
			LeadEmailTemplate::where('id',$request['id_email'])->update(['subject'=>$request['name']]);
		}
		if(!empty($request['content_send_email']))
		{
			LeadEmailTemplate::where('id',$request['id_email'])->update(['body'=>$request['content_send_email']]);
		}
		
		LeadEmailTemplate::where('id',$request['id_email'])->update(['updated_at'=>Carbon::now()->toDateTimeString()]);
		
		return redirect()->back()->withSuccess('You have updated successfully');  
	}
}
