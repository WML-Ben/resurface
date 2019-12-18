<?php namespace App\Http\Controllers;

use App\Http\Requests;

use Illuminate\Http\Request;

use App\AgePeriod;
use App\Http\Requests\AgePeriodRequest;
use App\Http\Requests\SearchRequest;

class TmpController extends FrontEndBaseController
{
    public function index()
    {
        $dummyData = 'Hello';

        $data = [
            'dummyData' => $dummyData,
            'seo'        => [
                'pageTitle' => 'Test Page',
            ],
        ];

        return view('_tmp.empty_page', $data);
    }

}
