<?php
namespace SharedScreenplay\Core;

use RuntimeException;

class Actor
{
    /** @var array<string, Ability> */
    private array $abilities = [];

    /**
     * Create an actor with a readable name.
     *
     * Example:
     * ```php
     * $actor = new Actor('Customer');
     * ```
     */
    public function __construct(private string $name)
    {
    }

    /**
     * Grant an ability to this actor.
     *
     * Example (HTTP ability):
     * ```php
     * $actor->can(new HttpAbility(new FakeHttpClient()));
     * ```
     */
    public function can(Ability $ability): self
    {
        $this->abilities[$ability::class] = $ability;
        return $this;
    }

    /**
     * Retrieve an ability by class name.
     *
     * Example:
     * ```php
     * $http = $actor->ability(HttpAbility::class);
     * ```
     *
     * @template T of Ability
     * @param class-string<T> $abilityClass
     * @return T
     */
    public function ability(string $abilityClass): Ability
    {
        if (!array_key_exists($abilityClass, $this->abilities)) {
            throw new RuntimeException("{$this->name} does not have ability {$abilityClass}");
        }
        return $this->abilities[$abilityClass];
    }

    /**
     * Perform a task as this actor.
     *
     * Example:
     * ```php
     * $body = $actor->attempts(new Login('user', 'pass'));
     * ```
     */
    public function attempts(Task $task): mixed
    {
        return $task->performAs($this);
    }

    /**
     * Ask a question as this actor.
     *
     * Example:
     * ```php
     * $status = $actor->asks(new LastResponseStatus());
     * ```
     */
    public function asks(Question $question): mixed
    {
        return $question->askAs($this);
    }

    /**
     * Name of the actor (useful for reporting).
     */
    public function name(): string
    {
        return $this->name;
    }
}
