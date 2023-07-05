<?php

namespace App\Jobs\Event;

use App\Actions\Event\EveryDay;
use App\Actions\Event\EveryMonth;
use App\Actions\Event\EveryWeek;
use App\Actions\Event\EveryWeekend;
use App\Actions\Event\EveryWorkingDay;
use App\Actions\Event\EveryYear;
use App\Actions\Event\NeverRepeat;
use App\Enums\EventRepeatSchemeEnum;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RepeatEvent implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private readonly Event   $event,
        private readonly ?Carbon $fromDate = null
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (match ($this->event->repeat_scheme) {
            EventRepeatSchemeEnum::EveryDay => new EveryDay($this->event, $this->fromDate),
            EventRepeatSchemeEnum::EveryWorkingDay => new EveryWorkingDay($this->event, $this->fromDate),
            EventRepeatSchemeEnum::EveryWeekend => new EveryWeekend($this->event, $this->fromDate),
            EventRepeatSchemeEnum::EveryWeek => new EveryWeek($this->event, $this->fromDate),
            EventRepeatSchemeEnum::EveryMonth => new EveryMonth($this->event, $this->fromDate),
            EventRepeatSchemeEnum::EveryYear => new EveryYear($this->event, $this->fromDate),
            EventRepeatSchemeEnum::Never => new NeverRepeat($this->event, $this->fromDate),
        })?->create();
    }
}
