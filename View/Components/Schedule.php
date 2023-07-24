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

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($view = null, $layout = 'schedule-layout-1', $item = null, $groupDays = true,
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
    $this->groupDays = $groupDays;

    $groupedDays = [];
    $dayGroup = null;

    if (isset($this->item->schedules)) {
      foreach ($this->item->schedules as $day) {
        if (!empty($day->schedules)) {
          $dateOpen = strtotime($day->schedules[0]->from);
          $openHour = date($this->formatHour, $dateOpen);
          $dateClose = strtotime($day->schedules[0]->to);
          $closeHour = date($this->formatHour, $dateClose);
          $currentDay = $day->iso;

          if ($dayGroup !== null && $openHour === $dayGroup['openHour']
            && $closeHour === $dayGroup['closeHour'] && $groupDays) {
            // Agrupar con el día anterior
            $groupedDays[count($groupedDays) - 1]['maxDay'] = $currentDay;
          } else {
            // Iniciar un nuevo grupo de días
            $dayGroup = [
              'minDay' => $currentDay,
              'maxDay' => $currentDay,
              'openHour' => $openHour,
              'closeHour' => $closeHour,
            ];
            $groupedDays[] = $dayGroup;
          }
        }
      }
    }
    $dayTranslations = [
      '1' => trans('ischedulable::common.day.monday'),
      '2' => trans('ischedulable::common.day.tuesday'),
      '3' => trans('ischedulable::common.day.wednesday'),
      '4' => trans('ischedulable::common.day.thursday'),
      '5' => trans('ischedulable::common.day.friday'),
      '6' => trans('ischedulable::common.day.saturday'),
      '7' => trans('ischedulable::common.day.sunday'),
    ];

    // Actualizar los valores de días en $groupedDays utilizando el array auxiliar
    foreach ($groupedDays as &$days) {
      $days['minDay'] = $dayTranslations[$days['minDay']];
      $days['maxDay'] = $dayTranslations[$days['maxDay']];
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