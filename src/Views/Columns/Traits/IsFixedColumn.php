<?php

namespace Rappasoft\LaravelLivewireTables\Views\Columns\Traits;

trait IsFixedColumn
{
    protected bool $isFixed = false;

    protected int $fixedPosition = 0;

    public function fixed(int $position = 1): self
    {
        if ($position < 1 || $position > 3) {
            throw new \InvalidArgumentException('Fixed column position must be between 1 and 3');
        }

        $this->isFixed = true;
        $this->fixedPosition = $position;

        return $this;
    }

    public function isFixed(): bool
    {
        return $this->isFixed ?? false;
    }

    public function getFixedPosition(): int
    {
        return $this->fixedPosition ?? 0;
    }

    public function notFixed(): self
    {
        $this->isFixed = false;
        $this->fixedPosition = 0;

        return $this;
    }
}
