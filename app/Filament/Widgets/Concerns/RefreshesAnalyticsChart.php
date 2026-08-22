<?php

namespace App\Filament\Widgets\Concerns;

use Livewire\Attributes\Locked;

trait RefreshesAnalyticsChart
{
    /**
     * @var array{datasets: list<array<string, mixed>>, labels: list<string>}|null
     */
    protected ?array $cachedChartData = null;

    #[Locked]
    public ?string $chartDataChecksum = null;

    public function mountRefreshesAnalyticsChart(): void
    {
        $this->chartDataChecksum = $this->generateChartDataChecksum();
    }

    public function rendering(): void
    {
        $this->refreshAnalyticsChartData();
    }

    public function updateChartData(): void
    {
        $this->refreshAnalyticsChartData();
    }

    protected function refreshAnalyticsChartData(): void
    {
        $this->cachedChartData = null;

        $newChecksum = $this->generateChartDataChecksum();

        if ($newChecksum === $this->chartDataChecksum) {
            return;
        }

        $this->chartDataChecksum = $newChecksum;
        $this->dispatch('updateChartData', data: $this->getCachedChartData());
    }

    /**
     * @return array{datasets: list<array<string, mixed>>, labels: list<string>}
     */
    protected function getCachedChartData(): array
    {
        return $this->cachedChartData ??= $this->getChartData();
    }

    protected function generateChartDataChecksum(): string
    {
        return md5((string) json_encode($this->getCachedChartData()));
    }

    protected function analyticsChartWireKey(string $prefix): string
    {
        $range = $this->normalizedAnalyticsDateRange();

        return $prefix.'-'.$range['from']->toDateString().'-'.$range['to']->toDateString();
    }

    /**
     * @return array{datasets: list<array<string, mixed>>, labels: list<string>}
     */
    abstract public function getChartData(): array;
}
