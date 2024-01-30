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
    @foreach($groupSchedule as $days)
      <div class="schedule-day">
        <div class="day-schedule text-center">
          @if($withIcon)
            <i class="{{$icon}} {{$colorIcon}}"></i>
          @endif
          <p class="name-day {{$colorNameDay}}">
            @if(count($days['days']) > 1)
              @php
                $groupTitle = reset($days['days'])." - ".end($days['days']);
                if ($groupTitle !== $previousGroupTitle) {
                    echo $groupTitle;
                    $previousGroupTitle = $groupTitle;
                }
              @endphp
            @else
              {{ reset($days['days']) }}
            @endif
          </p>
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
