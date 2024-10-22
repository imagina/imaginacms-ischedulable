<div class="schedule schedule-layout-1 py-3">
  @if($withTitle)
    <div class="title-section">
      @if($withIconTitle)
        <i class="{{$icon}} {{$colorIcon}}"></i>
      @endif
      {{$title}}
    </div>
  @endif
  @if($withDescription)
    <div class="description-section pb-3 text-center">
      {{$description}}
    </div>
  @endif
  @if (!empty($groupSchedule))
    @php
      $previousGroupTitle = '';
    @endphp
    @foreach($groupSchedule as $key => $days)
      <div class="schedule-day">
        <div class="day-schedule text-center">
          @if($withIcon)
            <i class="{{$icon}} {{$colorIcon}}"></i>
          @endif
          @if(count($days['days']) > 1)
            <p class="name-day {{$colorNameDay}}">
              @php
                $groupTitle = reset($days['days'])." - ".end($days['days']);
                if ($groupTitle !== $previousGroupTitle) {
                    echo $groupTitle;
                    $previousGroupTitle = $groupTitle;
                }
              @endphp
            </p>
          @else
            @php
              static $previousDay = null;
              $currentDay = reset($days['days']);
            @endphp
            @if( isset($groupSchedule[$key]) && $groupSchedule[$key] !== $currentDay && $currentDay !== $previousDay )
              <p class="name-day {{$colorNameDay}}">
                {{ $currentDay }}
              </p>
            @endif
            @php($previousDay = $currentDay)
          @endif
        </div>
        <div class="hours-schedule {{$colorHours}} text-center pb-2">
          @foreach ($days['openHours'] as $index => $openHour)
            @if ($index > 0)
              -
            @endif
            {{$openHour." ".$symbolToUniteHours." ".$days['closeHours'][$index]}}
          @endforeach
        </div>
      </div>
    @endforeach
  @endif
</div>
