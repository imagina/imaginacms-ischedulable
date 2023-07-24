<div class="schedule schedule-layout-1 py-3">
  <div class="container">
    <div class="row">
      <div class="col-12">
        @if($withTitle)
          <div class="title-section py-3 text-center">
            {{$title}}
          </div>
        @endif
        @if($withDescription)
          <div class="description-section pb-3 text-center">
            {{$description}}
          </div>
        @endif
        @if (!is_null($groupSchedule))
          @foreach($groupSchedule as $schedule)
            <div class="schedule-day">
              <div class="day-schedule text-center">
                @if($schedule['minDay'] == $schedule['maxDay'])
                  {{$schedule['minDay']}}
                @else
                  {{$schedule["minDay"]." ".$symbolToUniteDays." ".$schedule["maxDay"]}}
                @endif
              </div>
              <div class="hours-schedule text-center pb-2">
                {{$schedule['openHour']." ".$symbolToUniteHours." ".$schedule['closeHour']}}
              </div>
            </div>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</div>