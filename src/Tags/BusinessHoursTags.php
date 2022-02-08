<?php

namespace LuckyMedia\BusinessHours\Tags;

use Carbon\Carbon;
use Statamic\Tags\Tags;
use LuckyMedia\BusinessHours\Blueprints\BusinessHours;

class BusinessHoursTags extends Tags
{
    protected static $handle = 'business_hours';
    protected $defaults;
    protected $time_format;

    public function __construct()
    {
        $this->defaults = BusinessHours::augmentedValues();

        $this->time_format = config('statamic.business_hours.time_format');
    }

    public function index(): array
    {
        $days = collect($this->defaults['hours']->value())
            ->map(function ($day, $key) {
                return [
                    'is_open' => $this->isOpen($day, $key),
                    'is_past' => $this->isPast($day, $key),
                    'enabled' => $day['enabled']->value(),
                    'weekday' => $day['weekday']->value(),
                    'start_time' => $this->formatDate($day['start_time']->value()),
                    'end_time' => $this->formatDate($day['end_time']->value()),
                    '24_hours' => $day['24_hours']->value(),
                    'closed' => $day['closed']->value(),
                ];
            })
            ->filter(function($day) {
                return $day['enabled'] === true;
            });

        return $days->toArray();
    }

    public function exception()
    {
        $days = collect($this->defaults['exceptions']->value())
            ->first(function ($day) {
                $is_enabled = $day['enable_date']->value();
                $start_date = Carbon::create($day['start_date']->value());
                $end_date = Carbon::create($day['end_date']->value());

                if (! $is_enabled) return false;

                if ($start_date->isToday() || $end_date->isToday()) return true;

                if (! $start_date->isPast()) return false;

                if (! $end_date->isFuture()) return false;

                return false;
            });

        if (empty($days)) {
            return false;
        }

        return $days;
    }

    public function exceptions(): array
    {
        return $this->defaults['exceptions']->value();
    }

    protected function isPast($day, $key): bool
    {
        if ($this->isToday($key)) {
            $end_today = $this->formatToTime($day['end_time']);

            if (! now()->gt($end_today)) {
                return false;
            }

            return true;
        }

        if (! (($key + 1) < now()->dayOfWeekIso)) return false;

        return true;
    }

    protected function isOpen($day, $key): bool
    {
        $start_today = $this->formatToTime($day['start_time']);
        $end_today = $this->formatToTime($day['end_time']);

        if (! $this->checkIfOpen($key, $start_today, $end_today)) {
            return false;
        }

        return true;
    }

    protected function checkIfOpen($key, $start, $end): bool
    {

        if (! $this->isToday($key)) {
            return false;
        }

        if (! empty($this->exception())) {
            return false;
        }

        if(! now()->between($start, $end)) {
            return false;
        }

        return true;
    }

    protected function isToday($key): bool
    {
        return ($key + 1) === now()->dayOfWeekIso;
    }

    protected function formatToTime($day) {
        [$hour, $minute] = explode(':', $day->value());

        return Carbon::createFromTime($hour, $minute);
    }

    protected function formatDate(string $day): string
    {
        if ($this->time_format === 12) {
            return Carbon::create($day)->format('g:i a');
        }

        return Carbon::create($day)->format('H:i');
    }
}
