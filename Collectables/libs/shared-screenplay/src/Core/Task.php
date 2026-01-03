<?php
namespace SharedScreenplay\Core;

interface Task
{
    /**
     * Execute work on behalf of an actor.
     *
     * Example usage inside a test:
     * ```php
     * $actor->attempts(new ViewStore());
     * ```
     */
    public function performAs(Actor $actor): mixed;
}
