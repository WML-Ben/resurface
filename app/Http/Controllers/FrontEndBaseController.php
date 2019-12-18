<?php namespace App\Http\Controllers;

class FrontEndBaseController extends CommonController
{
    public function __construct()
    {
        $this->middleware('auth');

        parent::__construct();

        $this->middleware(function ($request, $next) {

            $myIncompleteTasks = [
                'total' => \App\Task::own()->incomplete()->count(),
                'tasks' => \App\Task::own()->incomplete()->orderBy('due_at', 'ASC')->with('createdBy')->take(5)->get(),
            ];
            view()->share('myIncompleteTasks', $myIncompleteTasks);

            $myUnreadMessages = [
                'total'    => \App\Message::unread()->count(),
                'messages' => \App\Message::unread()->orderBy('created_at', 'DESC')->take(5)->get(),
            ];
            view()->share('myUnreadMessages', $myUnreadMessages);
			
			$myCompany = [
                'json_html_property_management_companies_cb'    => \App\Company::jsonHtmlCompaniesCB(),
            ];
            view()->share('myCompany', $myCompany);
			
			$myProperties = [
                'json_html_property'    => \App\Property::jsonHtmlPropertiesCB(),
            ];
            view()->share('myProperties', $myProperties);
			
			$serviceCateogry = [
                'serviceCateogry'    =>  \App\ServiceCategory::categoriesCB(null, [10]),
            ];
            view()->share('serviceCateogry', $serviceCateogry);

			$saleManager = [
                'saleManager'    =>  \App\Employee::where('role_id',7)->orderBy('first_name', 'ASC')->get()->toArray(),
            ];
            view()->share('saleManager', $saleManager);
			
			$salePerson = [
                'salePerson'    =>  \App\Employee::where('role_id',8)->orderBy('first_name', 'ASC')->get()->toArray(),
            ];
            view()->share('salePerson', $salePerson);

            return $next($request);
        });


    }
}
