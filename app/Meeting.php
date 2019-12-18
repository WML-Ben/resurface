<?php namespace App;

class Meeting extends BaseModel
{
    protected $table = 'calendar_appointment';
	
    protected $fillable = [
        'id',
        'lead_id',
		'id_properties',
        'meeting_date',
        'meeting_time',
        'created_at',
		'updated_at',
		'created_by',
		'updated_by',
		'is_delete'
    ];
}
