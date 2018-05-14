<?php

namespace LaravelEnso\DocumentsManager\app\Models;

use LaravelEnso\Core\app\Models\User;
use Illuminate\Database\Eloquent\Model;
use LaravelEnso\TrackWho\app\Traits\CreatedBy;
use LaravelEnso\DocumentsManager\app\Classes\Storer;
use LaravelEnso\DocumentsManager\app\Classes\Presenter;
use LaravelEnso\DocumentsManager\app\Classes\ConfigMapper;

class Document extends Model
{
    use CreatedBy;

    protected $fillable = ['original_name', 'saved_name', 'size'];

    protected $appends = ['owner', 'isDownloadable', 'isDeletable'];

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

    public function getIsDownloadableAttribute()
    {
        return request()->user()->can('download', $this);
    }

    public function getIsDeletableAttribute()
    {
        return request()->user()->can('destroy', $this);
    }

    public static function create(array $files, string $type, int $id)
    {
        return (new Storer($files, $type, $id))
            ->run();
    }

    public function inline()
    {
        return (new Presenter($this))->inline();
    }

    public function download()
    {
        return (new Presenter($this))->download();
    }

    public function scopeFor($query, array $request)
    {
        $query->whereDocumentableId($request['id'])
            ->whereDocumentableType(
                (new ConfigMapper($request['type']))->class()
            );
    }
}
