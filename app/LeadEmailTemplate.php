<?php
namespace App;

class LeadEmailTemplate extends BaseModel
{
	protected $table = 'lead_email_template'; 
	
    protected $fillable = [
        'id',
		'subject',
        'body',
		'created_at',
		'updated_at'
    ];
}
