<?php namespace App;

use SortableTrait;
use SearchTrait;

class Message extends BaseModel
{
    use SortableTrait, SearchTrait;

    protected $fillable = [
        'sender_id',
        'recipient_id',
        'copy_id',
        'subject',
        'message',
        'is_read',
        'is_responded',
        'disabled',
        'created_at',
        'updated_at'
    ];

    public $sortable = [
        'messages.sender_id|users.first_name',
        'messages.recipient_id|users.first_name',
        'is_read',
        'is_responded',
        'disabled',
        'created_at',
    ];

    public $searchable = [
        'subject'     => 'LIKE',
        'message'     => 'LIKE',
        'created_at'  => 'LIKE',
        'childModels' => [
            'sender'    => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],
            'recipient' => [
                'fields' => [
                    'first_name' => 'LIKE',
                    'last_name'  => 'LIKE',
                ],
            ],

        ],
    ];

    public function sortableColumns()
    {
        return $this->sortable;
    }

    public function searchableColumns()
    {
        return $this->searchable;
    }


    /** relationships */

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    public function copy()
    {
        return $this->belongsTo(Message::class, 'copy_id');
    }


    /** scopes */

    public function scopeInbox($query)
    {
        return $query->where('recipient_id', auth()->user()->id);
    }

    public function scopeSent($query)
    {
        return $query->where('sender_id', auth()->user()->id);
    }

    public function scopeUnread($query)
    {
        return $query->inbox()->where('is_read', 0);
    }

    public function scopeRead($query)
    {
        return $query->inbox()->where('is_read', 1);
    }

    public function scopeNoResponded($query)
    {
        return $query->inbox()->where('is_responded', 0);
    }

    public function scopeResponded($query)
    {
        return $query->inbox()->where('is_responded', 1);
    }


    /** Accessors and Mutators */


    /** Methods */


}
