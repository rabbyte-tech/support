<?php

namespace RabbyteTech\Support\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Rabbyte\Contracts\Authorization\AbilityResolver;

abstract class DomainAction
{
    public function __construct(protected AbilityResolver $abilityResolver) {}

    abstract protected function ability(): ?string;

    protected function authorize(?object $actor = null): void
    {
        $ability = $this->ability();

        if ($ability === null || $ability === '') {
            return;
        }

        $actor ??= auth()->user() ?? request()->user();

        if (! $this->abilityResolver->allows($actor, $ability)) {
            throw new AuthorizationException();
        }
    }
}
