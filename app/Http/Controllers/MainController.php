<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

use App\OrderService;
use App\Proposal;
use App\WorkOrder;
use App\Intake;
use App\Meeting;
class MainController extends FrontEndBaseController
{
    public function dashboard()
    {
        $agePeriods = \App\AgePeriod::get();

        foreach ($agePeriods as $agePeriod) {
            $agePeriod->total_proposals = Proposal::getPeriodActiveCount($agePeriod->initial_day, $agePeriod->final_day);
        }

        $activeProposalTotals['age_periods'] = $agePeriods;
        $activeProposalTotals['total'] = Proposal::proposalActive()->count();

        $processingWorkOrders = WorkOrder::workOrderProcessing()->count();
        $activeWorkOrders = WorkOrder::workOrderActive()->count();
        $billingWorkOrders = WorkOrder::workOrderBilling()->count();
		/*Lead*/
		$lead = Intake::where('is_delete',0)->limit(10)->get();
		$activeLead = array();
		foreach($lead as $item)
		{
			$meeting = Meeting::where('lead_id',$item->id)->orderBy('id','desc')->limit(1)->get();
			$date_format  = date('m/d/Y',strtotime($meeting[0]->meeting_date.' '.$meeting[0]->meeting_time));
			$time_format  = date('H:i',strtotime($meeting[0]->meeting_date.' '.$meeting[0]->meeting_time));
			$activeLead[]  = array(
				'full_name' 	=>$item->first_name.' '.$item->last_name,
				'email' 		=>$item->email,
				'meeting_date'	=>$date_format,
				'meeting_time'	=>$time_format,
				'phone'	=>$item->phone
			);
		}
		/**/
        $activeWorkorderTotals = [
            'total'      => $processingWorkOrders + $activeWorkOrders + $billingWorkOrders,
            'processing' => $processingWorkOrders,
            'active'     => $activeWorkOrders,
            'billing'    => $billingWorkOrders,
        ];

        $activeServiceTotals = [
            'total'        => OrderService::orderServiceActive()->withWorkOrderActive()->count(),
            'no_scheduled' => OrderService::orderServiceActive()->withWorkOrderActive()->orderServiceNoScheduled()->count(),
            'scheduled'    => OrderService::orderServiceActive()->withWorkOrderActive()->orderServiceScheduled()->count(),
        ];

        $appointments = \App\Appointment::basedOnRole()
            ->todayOn()
            ->orderBy('started_at')
            ->with(['createdBy', 'property' => function ($q) {
                $q->with('manager');
            }])
            ->take(5)
            ->get();

        $incompleteTasks = \App\Task::basedOnRole()
            ->incomplete()
            ->orderBy('due_at', 'ASC')
            ->with('createdBy')
            ->take(5)
            ->get();

        $data = [
            'activeProposalTotals'  => $activeProposalTotals,
            'activeWorkorderTotals' => $activeWorkorderTotals,
            'activeServiceTotals'   => $activeServiceTotals,
            'appointments'          => $appointments,
			'activelead'			=>$activeLead,
            'incompleteTasks'       => $incompleteTasks,
            'seo'                   => [
                'pageTitlePrefix' => '',
            ],
        ];

        return view('dashboard', $data);
    }

    public function search(Request $request)
    {
        if (!auth()->user()->hasPrivileges(['search-property', 'search-company', 'search-contact'])) {
            return redirect()->back()->with('error', self::ERROR_NOT_ENOUGH_PRIVILEGES);
        }

        $globalNeedle = $request->global_needle ?? session()->get('global_needle');

        $validator = \Validator::make(
            ['global_needle' => $globalNeedle],
            ['global_needle' => 'required|plainText|min:3']
        );

        if ($validator->fails()) {
            return redirect()->back()->withError($validator->messages()->first());
        }

        if (!session()->has('global_needle') || session()->get('global_needle') != $request->global_needle) {

            $companies = \App\Company::active()->select(\DB::raw('`id` AS id, `name` AS name, `email` AS email, `phone` AS phone, "Company" AS type'))->where('name', 'LIKE', "%" . $globalNeedle . "%")->get();

            $users = \App\User::active()->select(\DB::raw('DISTINCT(`id`) AS id, TRIM(CONCAT(`first_name`, " ", `last_name`)) AS name, `email` AS email, `phone` AS phone, IF(`is_employee` = 1, "Employee", "Contact") AS type'))->where(function ($q) use ($globalNeedle) {
                $q->orWhere('first_name', 'LIKE', "%" . $globalNeedle . "%")->orWhere('last_name', 'LIKE', "%" . $globalNeedle . "%");
            })->get();

            $properties = \App\Property::active()->select(\DB::raw('`id` AS id, `name` AS name, `email` AS email, `phone` AS phone, "Property" AS type'))->where('name', 'LIKE', "%" . $globalNeedle . "%")->get();

            if (empty($companies) && empty($users) && empty($properties)) {
                return redirect()->back()->withInfo('No results found searching for "<strong>' . $globalNeedle . '"</strong>.');
            }

            $globalSearchResults = $companies->concat($users);
            $globalSearchResults = $globalSearchResults->concat($properties);

            session()->put('globalSearchResults', $globalSearchResults->toArray());

            session()->put('global_needle', $globalNeedle);
        }

        $sql = "CREATE TEMPORARY TABLE `mem_global_search_results` (";
        $sql .= "`id` int(10) UNSIGNED NOT NULL,";
        $sql .= "`name` VARCHAR(255) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,";
        $sql .= "`email` VARCHAR(150) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,";
        $sql .= "`phone` VARCHAR(50) COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,";
        $sql .= "`type` VARCHAR(10) COLLATE utf8mb4_unicode_ci NOT NULL,";
        $sql .= "KEY `id` (`id`),";
        $sql .= "KEY `name` (`name`),";
        $sql .= "KEY `email` (`email`),";
        $sql .= "KEY `phone` (`phone`),";
        $sql .= "KEY `type` (`type`)";
        $sql .= ")";

        if (!\DB::insert(\DB::raw($sql))) {
            return redirect()->back()->withErrorInfo('Error creating mem table.');
        }

        $memTable = new \App\MemGlobalSearchResult;

        $memTable->insert(session()->get('globalSearchResults'));

        $perPage = $request->perPage ?? ($this->conf->itemsPerPage ?? 10);

        $globalSearchResults = $memTable::sortable()->paginate($perPage);

        $data = [
            'globalSearchResults' => $globalSearchResults,
            'globalNeedle'        => $globalNeedle,
            'seo'                 => [
                'pageTitle' => 'Global Search Results',
            ],
        ];

        return view('global_search_results', $data);
    }

}
