<?php

namespace LaravelEnso\DocumentsManager\app\Policies;

use Carbon\Carbon;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use LaravelEnso\DocumentsManager\app\Models\Document;

class DocumentPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function download(User $user, Document $document)
    {
        return $this->userOwnsDocument($user, $document);
    }

    public function destroy(User $user, Document $document)
    {
        return $this->userOwnsDocument($user, $document)
            && $this->documentIsRecent($document);
    }

    private function userOwnsDocument(User $user, Document $document)
    {
        return $user->id === intval($document->created_by);
    }

    private function documentIsRecent(Document $document)
    {
        return $document->created_at->diffInHours(Carbon::now()) <= config('enso.documents.deletableTimeLimitInHours');
    }
}
