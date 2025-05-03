<?php

namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView
{

  private $carbon;
  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  public function getTitle()
  {
    return $this->carbon->format('Y年n月');
  }

  function render()
  {
    $html = [];
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border day-sat">土</th>';
    $html[] = '<th class="border day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';
    $weeks = $this->getWeeks();
    foreach ($weeks as $week) {
      $html[] = '<tr class="' . $week->getClassName() . '">';

      $days = $week->getDays();

      foreach ($days as $day) {
        $ymd = $day->everyDay();
        // 空白マス処理（日付がない枠）
        if (!$ymd) {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . ' border" style="background-color:#d3d3d3;"></td>';
          continue;
        }

        $isPast = $ymd <= Carbon::today()->format('Y-m-d');
        $reserve = $day->authReserveDate($ymd)->first();

        // 背景色つきTD
        if ($isPast) {
          $html[] = '<td class="calendar-td border" style="background-color:#eeeeee;">';
        } else {
          $html[] = '<td class="calendar-td ' . $day->getClassName() . ' border">';
        }

        // 日付数字
        // $html[] = $day->render();

        // 空白マス処理後
        $dayOfWeek = \Carbon\Carbon::parse($ymd)->dayOfWeek;

        // 背景色つきTDの後
        if ($dayOfWeek == 0) {
          $html[] = '<div class="day-sun">' . \Carbon\Carbon::parse($ymd)->day . '日</div>';
        } elseif ($dayOfWeek == 6) {
          $html[] = '<div class="day-sat">' . \Carbon\Carbon::parse($ymd)->day . '日</div>';
        } else {
          $html[] = '<div>' . \Carbon\Carbon::parse($ymd)->day . '日</div>';
        }



        // 中身表示ロジック
        if ($isPast) {
          if ($reserve) {
            $html[] = '<div class="">' . $reserve->setting_part . '部参加</div>';
          } else {
            $html[] = '<div class="">受付終了</div>';
          }
          $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
        } else {
          if ($reserve) {
            $reserveLabel = 'リモ' . $reserve->setting_part . '部';
            $html[] = '<button type="button" class="btn btn-danger cancel-modal-open p-0 w-75"'
              . ' style="font-size:12px"'
              . ' data-date="' . $ymd . '"'
              . ' data-time="' . $reserveLabel . '">'
              . $reserveLabel
              . '</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          } else {
            $html[] = $day->selectPart($ymd);
          }
        }


        $html[] = $day->getDate();
        $html[] = '</td>';
      }
      $html[] = '</tr>';
    }
    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">' . csrf_field() . '</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">' . csrf_field() . '</form>';

    return implode('', $html);
  }

  protected function getWeeks()
  {
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;
    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while ($tmpDay->lte($lastDay)) {
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
