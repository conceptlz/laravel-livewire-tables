<?php

namespace Rappasoft\LaravelLivewireTables\Traits\Configuration;

trait FixedColumnSelectConfiguration
{
    public function setFixedColumnSelectStatus(bool $status): self
    {
        $this->fixedColumnSelectStatus = $status;

        return $this;
    }

    public function setFixedColumnSelectEnabled(): self
    {
        $this->setFixedColumnSelectStatus(true);

        return $this;
    }

    public function setFixedColumnSelectDisabled(): self
    {
        $this->setFixedColumnSelectStatus(false);

        return $this;
    }

    public function setMaxFixedColumns(int $max): self
    {
        $this->maxFixedColumns = min(max($max, 1), 3);

        return $this;
    }

    /**
     * Set default fixed columns. These will be used as initial values
     * but can be overridden by user selections (via persistence).
     * 
     * @param array $columnSlugs Array of column slugs to pin by default
     * @return self
     */
    public function setDefaultFixedColumns(array $columnSlugs): self
    {
        // Store defaults - they'll be applied in setupFixedColumnSelect() 
        // only if no persisted selection exists
        $this->defaultFixedColumns = array_slice($columnSlugs, 0, $this->maxFixedColumns);

        return $this;
    }
}
