<?php

namespace LaravelEnso\DocumentsManager;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $documentable_id
 * @property string $documentable_type
 * @property string $name
 * @property string $saved_name
 * @property \Carbon\Carbon $created_at
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $documentable
 *
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereDocumentableId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereDocumentableType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereSavedName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Document whereCreatedAt($value)
 * @mixin \Eloquent
 */
class Document extends Model
{
    protected $fillable = ['original_name', 'saved_name', 'size'];

    public function documentable()
    {
        return $this->morphTo();
    }
}
