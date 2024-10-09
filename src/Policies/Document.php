<?php

namespace LaravelEnso\Documents\Policies;

use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;
use LaravelEnso\Documents\Models\Document as Model;
use LaravelEnso\Users\Models\User;

class Document
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isSuperior()) {
            return true;
        }
    }

    public function store(User $user, Model $document)
    {
        return true;
    }

    public function view(User $user, Model $document)
    {
        return $this->ownsDocument($user, $document);
    }

    public function share(User $user, Model $document)
    {
        return $this->ownsDocument($user, $document);
    }

    public function destroy(User $user, Model $document)
    {
        return $this->ownsDocument($user, $document)
            && $this->isRecent($document);
    }

    protected function ownsDocument(User $user, Model $document)
    {
        return $user->id === (int) $document->file->created_by;
    }

    private function isRecent(Model $document)
    {
        return (int) $document->created_at->diffInSeconds(Carbon::now(), true)
            <= (int) Config::get('enso.documents.deletableTimeLimit');
    }
}
