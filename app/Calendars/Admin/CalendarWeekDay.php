<?php

namespace App\Calendars\Admin;

use Carbon\Carbon;
use App\Models\Calendars\ReserveSettings;

class CalendarWeekDay
{
  protected $carbon;

  function __construct($date)
  {
    $this->carbon = new Carbon($date);
  }

  function getClassName()
  {
    return "day-" . strtolower($this->carbon->format("D"));
  }

  function render()
  {
    // 追記
    $dayOfWeek = $this->carbon->dayOfWeek; // 0 = 日曜, 6 = 土曜
    $class = '';

    if ($dayOfWeek == 0) {
      $class = 'day-sun';
    } elseif ($dayOfWeek == 6) {
      $class = 'day-sat';
    }

    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function everyDay()
  {
    return $this->carbon->format("Y-m-d");
  }

  function dayPartCounts($ymd)
  {
    $html = [];
    $one_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
    $two_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    $three_part = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

    $html[] = '<div class="text-left">';
    if ($one_part) {
      $html[] = '<p class="day_part m-0 pt-1">';
      // 追加
      $html[] = '<a href="/calendar/' . $ymd . '/1" class="text-primary">1部</a> ';
      $html[] = '<span>' . $one_part->users->count() . '</span>';
      $html[] = '</p>';
    }
    if ($two_part) {
      $html[] = '<p class="day_part m-0 pt-1">';
      // 追加
      $html[] = '<a href="/calendar/' . $ymd . '/2" class="text-primary">2部</a> ';
      $html[] = '<span>' . $two_part->users->count() . '</span>';
      $html[] = '</p>';
    }
    if ($three_part) {
      $html[] = '<p class="day_part m-0 pt-1">';
      // 追加
      $html[] = '<a href="/calendar/' . $ymd . '/3" class="text-primary">3部</a> ';
      $html[] = '<span>' . $three_part->users->count() . '</span>';
      $html[] = '</p>';
    }
    $html[] = '</div>';

    return implode("", $html);
  }


  function onePartFrame($day)
  {
    $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first();
    if ($one_part_frame) {
      $one_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '1')->first()->limit_users;
    } else {
      $one_part_frame = "20";
    }
    return $one_part_frame;
  }
  function twoPartFrame($day)
  {
    $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first();
    if ($two_part_frame) {
      $two_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '2')->first()->limit_users;
    } else {
      $two_part_frame = "20";
    }
    return $two_part_frame;
  }
  function threePartFrame($day)
  {
    $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first();
    if ($three_part_frame) {
      $three_part_frame = ReserveSettings::where('setting_reserve', $day)->where('setting_part', '3')->first()->limit_users;
    } else {
      $three_part_frame = "20";
    }
    return $three_part_frame;
  }

  //
  function dayNumberAdjustment()
  {
    $html = [];
    $html[] = '<div class="adjust-area">';
    $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="1" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="2" type="text" form="reserveSetting"></p>';
    $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="3" type="text" form="reserveSetting"></p>';
    $html[] = '</div>';
    return implode('', $html);
  }
}
