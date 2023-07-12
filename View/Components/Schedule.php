<?php

namespace Modules\Ischedulable\View\Components;

use Illuminate\View\Component;

class Schedule extends Component
{
  public $view;
  public $layout;
  public $item;
  public $groupSchedule;
  public $daySchedule;
  public $formatHour;
  public $symbolToUniteDays;
  public $symbolToUniteHours;
  public $title;
  public $description;
  public $withTitle;
  public $withDescription;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($view = null, $layout = 'schedule-layout-1', $item = null, $groupDays = false,
                              $formatHour = null, $symbolToUniteDays = 'a', $symbolToUniteHours = 'a',
                              $title = 'Horarios De Atención', $description = null, $withTitle = false,
                              $withDescription = false)
  {
    $this->item = $item ?? setting('ischedulable::siteSchedule') ?? [];
    $this->layout = $layout;
    $this->view = $view ?? "ischedulable::frontend.components.schedule.layouts.{$this->layout}.index";
    $this->formatHour = $formatHour ?? 'g:i a';
    $this->symbolToUniteDays = $symbolToUniteDays;
    $this->symbolToUniteHours = $symbolToUniteHours;
    $this->title = $title;
    $this->description = $description;
    $this->withTitle = $withTitle;
    $this->withDescription = $withDescription;


    $firstDate = 0;
    $i = 1;
    $relatedOpenHour = null;
    $relatedCloseOur = null;
    $minDay = null;
    $maxDay = null;
    $closeDay = null;
    $openDay = null;
    $this->groupSchedule = null;
    if (isset($this->item->schedules)) {
      foreach ($this->item->schedules as $day) {
        if ($day->schedules != 0) {
          $dateOpen = strtotime($day->schedules[0]->from);
          $openHour = date($this->formatHour, $dateOpen);
          $dateClose = strtotime($day->schedules[0]->to);
          $closeHour = date($this->formatHour, $dateClose);
          if ($relatedOpenHour == $openHour && $relatedCloseOur == $closeHour && $groupDays ||
            is_null($relatedOpenHour) && is_null($relatedCloseOur) && $groupDays) {
            $relatedOpenHour = $openHour;
            $relatedCloseOur = $closeHour;
            if (is_null($minDay)) {
              $minDay = $day->iso;
            } else {
              $maxDay = $day->iso;
            }
            switch ($maxDay) {
              case '1' :
                $closeDay = trans('ischedulable::common.day.monday');
                break;
              case '2' :
                $closeDay = trans('ischedulable::common.day.tuesday');
                break;
              case '3' :
                $closeDay = trans('ischedulable::common.day.wednesday');
                break;
              case '4' :
                $closeDay = trans('ischedulable::common.day.thursday');
                break;
              case '5' :
                $closeDay = trans('ischedulable::common.day.friday');
                break;
              case '6' :
                $closeDay = trans('ischedulable::common.day.saturday');
                break;
              case '7' :
                $closeDay = trans('ischedulable::common.day.sunday');
                break;
            }
            switch ($minDay) {
              case '1' :
                $openDay = trans('ischedulable::common.day.monday');
                break;
              case '2' :
                $openDay = trans('ischedulable::common.day.tuesday');
                break;
              case '3' :
                $openDay = trans('ischedulable::common.day.wednesday');
                break;
              case '4' :
                $openDay = trans('ischedulable::common.day.thursday');
                break;
              case '5' :
                $openDay = trans('ischedulable::common.day.friday');
                break;
              case '6' :
                $openDay = trans('ischedulable::common.day.saturday');
                break;
              case '7' :
                $openDay = trans('ischedulable::common.day.sunday');
                break;
            }
            $this->groupSchedule["openDay"] = $openDay;
            $this->groupSchedule["closeDay"] = $closeDay;
            $this->groupSchedule["openHour"] = $relatedOpenHour;
            $this->groupSchedule["closeHour"] = $relatedCloseOur;
          } else {
            switch ($day->iso) {
              case '1' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.monday');
                break;
              case '2' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.tuesday');
                break;
              case '3' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.wednesday');
                break;
              case '4' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.thursday');
                break;
              case '5' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.friday');
                break;
              case '6' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.saturday');
                break;
              case '7' :
                $this->daySchedule[$i]['day'] = trans('ischedulable::common.day.sunday');
                break;
            }
            $this->daySchedule[$i]['openHour'] = $openHour;
            $this->daySchedule[$i]['closeHour'] = $closeHour;
            $i = $i + 1;
          }
        }
      }
    }
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
