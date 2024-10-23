<?php

namespace Modules\Ischedulable\View\Components;

use Illuminate\View\Component;

class Schedule extends Component
{
  public $view;
  public $layout;
  public $item;
  public $groupSchedule;
  public $formatHour;
  public $symbolToUniteDays;
  public $symbolToUniteHours;
  public $title;
  public $description;
  public $withTitle;
  public $withDescription;
  public $groupDays;
  public $withIcon;
  public $icon;
  public $colorIcon;
  public $colorNameDay;
  public $colorHours;
  public $withIconTitle;
  public $holidays;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($view = null, $layout = 'schedule-layout-1', $item = null, $groupDays = true,
                              $formatHour = null, $symbolToUniteDays = 'a', $symbolToUniteHours = 'a',
                              $title = 'Horarios De Atención', $description = null, $withTitle = false,
                              $withDescription = false, $withIcon = false, $icon = "fa-regular fa-clock",
                              $colorIcon = '', $colorNameDay = '', $colorHours = '', $withIconTitle = false,
                              $holidays = false)
  {
    $this->item = $item ?? json_decode(setting('ischedulable::siteSchedule')) ?? [];
    if (isset($this->item->workTimes) && !is_null($this->item->workTimes)) {
      $this->item->workTimes = (collect($this->item->workTimes));
      if (is_null($item) && !is_null(json_decode(setting('ischedulable::siteSchedule')))) {
        $this->item->workTimes = $this->item->workTimes->sortBy("dayId");
      } else {
        $this->item->workTimes = $this->item->workTimes->sortBy("day_id");
      }
    }
    $this->layout = $layout;
    $this->view = $view ?? "ischedulable::frontend.components.schedule.layouts.{$this->layout}.index";
    $this->formatHour = $formatHour ?? 'g:i a';
    $this->symbolToUniteDays = $symbolToUniteDays;
    $this->symbolToUniteHours = $symbolToUniteHours;
    $this->title = $title;
    $this->description = $description;
    $this->withTitle = $withTitle;
    $this->withDescription = $withDescription;
    $this->groupDays = $groupDays;
    $this->withIcon = $withIcon;
    $this->icon = $icon;
    $this->colorIcon = $colorIcon;
    $this->colorNameDay = $colorNameDay;
    $this->colorHours = $colorHours;
    $this->withIconTitle = $withIconTitle;
    $this->holidays = $holidays;

    $groupedDays = [];

    if (isset($this->item->workTimes)) {
      foreach ($this->item->workTimes as $day) {
        $dateOpen = strtotime($day->startTime ?? $day->start_time);
        $openHour = date($this->formatHour, $dateOpen);
        $dateClose = strtotime($day->endTime ?? $day->end_time);
        $closeHour = date($this->formatHour, $dateClose);
        $currentDay = $day->dayId ?? $day->day_id;

        $foundGroup = false;

        if ($groupDays) {
          foreach ($groupedDays as &$existingGroup) {
            if (in_array($openHour, $existingGroup['openHours']) && in_array($closeHour, $existingGroup['closeHours'])) {
              $foundGroup = true;
              if (!in_array($currentDay, $existingGroup['days'])) {
                $existingGroup['days'][] = $currentDay;
              }
              break;
            }
          }
        }
        if (!$foundGroup) {
          $groupedDays[] = [
            'days' => [$currentDay],
            'openHours' => [$openHour],
            'closeHours' => [$closeHour],
          ];
        }
      }
    }

    usort($groupedDays, function ($a, $b) {
      $minDayA = min($a['days']);
      $minDayB = min($b['days']);

      return $minDayA - $minDayB;
    });

    if ($this->holidays) {
      $titleSunday = trans('ischedulable::common.day.sundayAndHolidays');
    } else {
      $titleSunday = trans('ischedulable::common.day.sunday');
    }

    $dayTranslations = [
      '1' => trans('ischedulable::common.day.monday'),
      '2' => trans('ischedulable::common.day.tuesday'),
      '3' => trans('ischedulable::common.day.wednesday'),
      '4' => trans('ischedulable::common.day.thursday'),
      '5' => trans('ischedulable::common.day.friday'),
      '6' => trans('ischedulable::common.day.saturday'),
      '7' => $titleSunday,
    ];

    foreach ($groupedDays as &$days) {
      $translatedDays = [];
      foreach ($days['days'] as $day) {
        $translatedDays[] = $dayTranslations[$day];
      }
      $days['days'] = $translatedDays;
    }
    $this->groupSchedule = $groupedDays;
  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\View\View|string
   */
  public function render()
  {
    return view($this->view);
  }
}