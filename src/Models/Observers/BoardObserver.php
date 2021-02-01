<?php

namespace WalkerChiu\MorphBoard\Models\Observers;

class BoardObserver
{
    /**
     * Handle the entity "retrieved" event.
     *
     * @param  $entity
     * @return void
     */
    public function retrieved($entity)
    {
        //
    }

    /**
     * Handle the entity "creating" event.
     *
     * @param  $entity
     * @return void
     */
    public function creating($entity)
    {
        //
    }

    /**
     * Handle the entity "created" event.
     *
     * @param  $entity
     * @return void
     */
    public function created($entity)
    {
        //
    }

    /**
     * Handle the entity "updating" event.
     *
     * @param  $entity
     * @return void
     */
    public function updating($entity)
    {
        //
    }

    /**
     * Handle the entity "updated" event.
     *
     * @param  $entity
     * @return void
     */
    public function updated($entity)
    {
        //
    }

    /**
     * Handle the entity "saving" event.
     *
     * @param  $entity
     * @return void
     */
    public function saving($entity)
    {
        if (config('wk-core.class.morph-board.board')::where('id', '<>', $entity->id)
                                                     ->where('host_type', $entity->host_type)
                                                     ->where('host_id', $entity->host_id)
                                                     ->where('identifier', $entity->identifier)
                                                     ->exists())
            return false;
    }

    /**
     * Handle the entity "saved" event.
     *
     * @param  $entity
     * @return void
     */
    public function saved($entity)
    {
        //
    }

    /**
     * Handle the entity "deleting" event.
     *
     * @param  $entity
     * @return void
     */
    public function deleting($entity)
    {
        //
    }

    /**
     * Handle the entity "deleted" event.
     *
     * Its Lang will be automatically removed by database.
     *
     * @param  $entity
     * @return void
     */
    public function deleted($entity)
    {
        if ($entity->isForceDeleting()) {
            $entity->langs->withTrashed()->forceDelete();
            if ( config('wk-morph-board.onoff.morph-comment') && !empty(config('wk-core.class.morph-comment.comment')) ) {
                $entity->comments->withTrashed()->forceDelete();
            }
            if ( config('wk-morph-board.onoff.morph-image') && !empty(config('wk-core.class.morph-image.image')) ) {
                $entity->images->withTrashed()->forceDelete();
            }
        }
    }

    /**
     * Handle the entity "restoring" event.
     *
     * @param  $entity
     * @return void
     */
    public function restoring($entity)
    {
        if (config('wk-core.class.morph-board.board')::where('id', '<>', $entity->id)
                                                     ->where('host_type', $entity->host_type)
                                                     ->where('host_id', $entity->host_id)
                                                     ->where('identifier', $entity->identifier)
                                                     ->exists())
            return false;
    }

    /**
     * Handle the entity "restored" event.
     *
     * @param  $entity
     * @return void
     */
    public function restored($entity)
    {
        //
    }
}
