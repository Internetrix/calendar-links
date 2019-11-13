<?php

namespace Spatie\CalendarLinks\Generators;

use DateTimeZone;
use Spatie\CalendarLinks\Link;
use Spatie\CalendarLinks\Generator;

/**
 * @see https://github.com/InteractionDesignFoundation/add-event-to-calendar-docs/blob/master/services/outlook-live.md
 */
class Outlookcom extends WebOutlook
{
    protected $url = 'https://outlook.office.com/owa/?path=/calendar/action/compose&rru=addevent';


}
