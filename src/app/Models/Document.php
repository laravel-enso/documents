<?php

namespace LaravelEnso\DocumentsManager\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\ActivityLog\app\Traits\LogActivity;
use LaravelEnso\DocumentsManager\app\Classes\Storer;
use LaravelEnso\DocumentsManager\app\Classes\Presenter;
use LaravelEnso\DocumentsManager\app\Classes\ConfigMapper;

class Document extends Model
{
    use CreatedBy, LogActivity;

    protected $fillable = ['original_name', 'saved_name', 'size'];

    protected $appends = ['owner', 'isAccessible', 'isDeletable'];

    protected $loggableLabel = 'original_name';

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function documentable()
    {
        return $this->morphTo();
    }

    public function getOwnerAttribute()
    {
        $owner = [
            'fullName' => $this->user->fullName,
            'avatarId' => $this->user->avatar ? $this->user->avatar->id : null,
        ];

        unset($this->user);

        return $owner;
    }

    public function getIsAccessibleAttribute()
    {
        return request()->user()->can('access', $this);
    }

    public function getIsDeletableAttribute()
    {
        return request()->user()->can('destroy', $this);
    }

    public static function create(array $files, $attributes)
    {
        return (new Storer($files, $attributes))
            ->run();
    }

    public function temporaryLink()
    {
        return \URL::temporarySignedRoute(
            'core.documents.share',
            now()->addSeconds(config('enso.documents.linkExpiration')),
            ['document' => $this->id]
        );
    }

    public function inline()
    {
        return (new Presenter($this))
            ->inline();
    }

    public function download()
    {
        return (new Presenter($this))
            ->download();
    }

    public function scopeFor($query, array $request)
    {
        $query->whereDocumentableId($request['documentable_id'])
            ->whereDocumentableType(
                (new ConfigMapper($request['documentable_type']))
                    ->model()
            );
    }

    public function getLoggableMorph()
    {
        return config('enso.documents.loggableMorph');
    }
}
