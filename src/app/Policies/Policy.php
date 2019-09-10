<?php

namespace LaravelEnso\Documents\app\Policies;

use Carbon\Carbon;
use LaravelEnso\Core\app\Models\User;
use LaravelEnso\Documents\app\Models\Document;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->isAdmin() || $user->isSupervisor()) {
            return true;
        }
    }

    public function view(User $user, Document $document)
    {
        return $this->ownsDocument($user, $document);
    }
    
    public function share(User $user, Document $document)
    {
        return $this->ownsDocument($user, $document);
    }

    public function destroy(User $user, Document $document)
    {
        return $this->ownsDocument($user, $document)
            && $this->isRecent($document);
    }

    private function ownsDocument(User $user, Document $document)
    {
        return $user->id === (int) $document->file->created_by;
    }

    private function isRecent(Document $document)
    {
        return $document->created_at->diffInSeconds(Carbon::now())
            <= config('enso.documents.deletableTimeLimit');
    }
}
