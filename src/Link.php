<?php

namespace Spatie\CalendarLinks;

use DateTime;
use Spatie\CalendarLinks\Generators\Ics;
use Spatie\CalendarLinks\Generators\Outlookcom;
use Spatie\CalendarLinks\Generators\Yahoo;
use Spatie\CalendarLinks\Generators\Google;
use Spatie\CalendarLinks\Generators\WebOutlook;
use Spatie\CalendarLinks\Exceptions\InvalidLink;
use function var_dump;

/**
 * @property-read string $title
 * @property-read \DateTime $from
 * @property-read \DateTime $to
 * @property-read string $description
 * @property-read string $address
 * @property-read bool $allDay
 */
class Link
{
    /** @var string */
    protected $title;

    /** @var \DateTime */
    protected $from;

    /** @var \DateTime */
    protected $to;

    /** @var string */
    protected $description;

    /** @var bool */
    protected $allDay;

    /** @var string */
    protected $address;

    public function __construct($title, $from, $to, $allDay = false)
    {
        $this->title = $title;
        $this->allDay = $allDay;

        if ($to < $from) {
            throw InvalidLink::invalidDateRange($from, $to);
        }

        $this->from = clone $from;
        $this->to = clone $to;
    }

    /**
     * @param string $title
     * @param \DateTime $from
     * @param \DateTime $to
     * @param bool $allDay
     *
     * @return static
     * @throws InvalidLink
     */
    public static function create($title, $from, $to, $allDay = false)
    {
        return new static($title, $from, $to, $allDay);
    }

    /**
     * @param string   $title
     * @param DateTime $fromDate
     * @param int      $numberOfDays
     *
     * @return Link
     * @throws InvalidLink
     */
    public static function createAllDay($title, DateTime $fromDate, $numberOfDays = 1)
    {
        $fromTemp  = clone $fromDate;
        $toTemp = (clone $from);
        $from = $fromTemp->modify('midnight');
        $to = $toTemp->modify("+$numberOfDays days");

        return new self($title, $from, $to, true);
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $address
     *
     * @return $this
     */
    public function address($address)
    {
        $this->address = $address;

        return $this;
    }

    public function google()
    {
        return (new Google())->generate($this);
    }

    public function ics()
    {
        return (new Ics())->generate($this);
    }

    public function yahoo()
    {
        return (new Yahoo())->generate($this);
    }

    public function webOutlook()
    {
        return (new WebOutlook())->generate($this);
    }

    public function officeOutlook()
    {
        return (new Outlookcom())->generate($this);
    }

    public function __get($property)
    {
        return $this->$property;
    }
}
