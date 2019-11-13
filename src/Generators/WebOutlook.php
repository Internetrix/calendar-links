<?php

namespace Spatie\CalendarLinks\Generators;

use DateTimeZone;
use Spatie\CalendarLinks\Link;
use Spatie\CalendarLinks\Generator;

/**
 * @see https://github.com/InteractionDesignFoundation/add-event-to-calendar-docs/blob/master/services/outlook-live.md
 */
class WebOutlook implements Generator
{

    protected $url = 'https://outlook.live.com/owa/?path=/calendar/action/compose&rru=addevent';

    /** {@inheritdoc} */
    public function generate(Link $link)
    {
        $url = $this->url;

        $dateTimeFormat = $link->allDay ? 'Ymd' : "Ymd\THis";

        $fromTemp  = clone $link->from;
        $toTemp = clone $link->to;

        $utcStartDateTime = $fromTemp->setTimezone(new DateTimeZone('UTC'));
        $utcEndDateTime = $toTemp->setTimezone(new DateTimeZone('UTC'));

        $url .= '&startdt='.$utcStartDateTime->format($dateTimeFormat);

        $isSingleDayEvent = $link->to->diff($link->from)->d < 2;
        $canOmitEndDateTime = $link->allDay && $isSingleDayEvent;
        if (! $canOmitEndDateTime) {
            $url .= '&enddt='.$utcEndDateTime->format($dateTimeFormat);
        }

        if ($link->allDay) {
            $url .= '&allday=true';
        }

        $url .= '&subject='.urlencode($link->title);

        if ($link->description) {
            $url .= '&body='.urlencode($link->description);
        }

        if ($link->address) {
            $url .= '&location='.urlencode($link->address);
        }

        return $url;
    }
}
