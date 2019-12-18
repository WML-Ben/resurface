<?php namespace App;

class Files extends BaseModel
{
    protected $table = 'files';
	
    protected $fillable = [
        'id',
        'type_file',
		'name',
        'extension',
        'size',
        'path',
		'created_at',
		'created_by',
		'updated_by',
		'updated_at',
		'is_delete'
    ];
}
