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
          <div class="schedule-group">
            <div class="days-schedule text-center">
              {{$groupSchedule["openDay"]." ".$symbolToUniteDays." ".$groupSchedule["closeDay"]}}
            </div>
            <div class="hours-schedule text-center pb-2">
              {{$groupSchedule["openHour"]." ".$symbolToUniteHours." ".$groupSchedule["closeHour"]}}
            </div>
          </div>
        @endif
        @foreach($daySchedule as $schedule)
          <div class="schedule-day">
            <div class="day-schedule text-center">
              {{$schedule['day']}}
            </div>
            <div class="hours-schedule text-center pb-2">
              {{$schedule['openHour']." ".$symbolToUniteHours." ".$schedule['closeHour']}}
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </div>
</div>