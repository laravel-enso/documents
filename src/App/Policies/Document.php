<?php

namespace LaravelEnso\Documents\App\Policies;

use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\Core\App\Models\User;
use LaravelEnso\Documents\App\Models\Document as Model;

class Document
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
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
        return $document->created_at->diffInSeconds(Carbon::now())
            <= (int) config('enso.documents.deletableTimeLimit');
    }
}
