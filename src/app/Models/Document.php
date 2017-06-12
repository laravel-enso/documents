<?php

namespace LaravelEnso\DocumentsManager\app\Models;

use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;

class Document extends Model
{
	use CreatedBy;

    protected $fillable = ['original_name', 'saved_name', 'size'];
    protected $appends = ['owner', 'is_downloadable', 'is_deletable'];

    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by', 'id');
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public function getOwnerAttribute()
    {
        $attribute = [
            'full_name'   => $this->user->full_name,
            'avatar_link' => $this->user->avatar_link,
        ];

        unset($this->user);

        return $attribute;
    }

    public function getIsDownloadableAttribute()
    {
        return request()->user()->can('download', $this);
    }

    public function getIsDeletableAttribute()
    {
        return request()->user()->can('destroy', $this);
    }
}
