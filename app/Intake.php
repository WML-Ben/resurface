<?php

namespace App;
use SearchTrait;
use SortableTrait;
class Intake extends BaseModel
{
	 use SortableTrait, SearchTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $table = 'lead'; 
	
    protected $fillable = [
        'id',
		'properties_id',
		'first_name',
        'last_name',
        'email',
        'phone',
		'date_intake',
		'id_service',
		'other_service',
		'hear_about',
		'hear_about_other',
		'id_sales_manager',
		'id_sales_person',
		'comment',
		'files_id',
        'created_by',
		'updated_by',
		'created_at',
		'updated_at',
		'is_delete'
    ];
	public $searchable = [
        'first_name'  => 'LIKE',
		'last_name'  => 'LIKE',
        'email'       => 'LIKE',
        'phone'       => 'LIKE',
    ];
	    public $sortable = [
		'first_name',
        'last_name',
        'email',
        'phone',
    ];
	public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }
}
