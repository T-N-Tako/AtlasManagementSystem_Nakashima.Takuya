<?php

namespace App\Calendars\General;

use App\Models\Calendars\ReserveSettings;
use Carbon\Carbon;
use Auth;

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

  function pastClassName()
  {
    return;
  }

  /**
   * @return
   */

  function render()
  {
    return '<p class="day">' . $this->carbon->format("j") . '日</p>';
  }

  function selectPart($ymd)
  {
    // 今日の日付をCarbonで取得
    $today = Carbon::today();
    // $today = Carbon::parse('2025-04-25');
    $targetDate = Carbon::parse($ymd);

    // 自分の予約からこの日付に参加しているか確認
    $reserved = Auth::user()->reserveSettings
      ->filter(function ($setting) use ($ymd) {
        return Carbon::parse($setting->setting_reserve)->format('Y-m-d') === $ymd;
      })
      ->pluck('setting_part')
      ->toArray();




    // ✅ 過去日 && 予約あり → 各部ごとに hidden input + 表示（1つだけ）
    if ($targetDate->lessThanOrEqualTo($today) && !empty($reserved)) {
      $html = [];
      foreach ($reserved as $part) {
        // 表示用（例：1部参加）
        $html[] = '<span class="text-primary font-weight-bold">' . $part . '部参加</span>';

        // 予約登録用（送信用 hidden input）
        $html[] = '<input type="hidden" name="getPart[]" value="' . $part . '"/>';
        $html[] = '<input type="hidden" name="getData[]" value="' . $targetDate->toDateString() . '"/>';
      }
      return implode('', $html);
    }


    // ✅ 過去日 && 未予約 → name をつけない hidden で送信されないようにする
    if ($targetDate->lessThanOrEqualTo($today) && empty($reserved)) {
      return '<span class="text-secondary">受付終了</span>';

      // return '<span class="text-secondary">受付終了</span>' .
      //   '<input type="hidden" name="getData[]" value="' . $targetDate->toDateString() . '"/>' .
      //   '<input type="hidden" name="getPart[]" value=""/>';
    }


    // ✅ 未来日 && 予約あり → バッジ表示 + hidden input
    if (!empty($reserved)) {
      $html = [];

      foreach ($reserved as $part) {
        $html[] = '<span class="badge badge-danger">リモ' . $part . '部</span>';
        $html[] = '<input type="hidden" name="getPart[]" value="' . $part . '"/>';
        $html[] = '<input type="hidden" name="getData[]" value="' . $targetDate->toDateString() . '"/>';
      }

      return implode('', $html);
    }



    // ✅ 当日以降かつ未予約 → 通常の選択肢を表示
    // ここから下はそのままでOK
    $one_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '1')->first();
    $two_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '2')->first();
    $three_part_frame = ReserveSettings::with('users')->where('setting_reserve', $ymd)->where('setting_part', '3')->first();

    $one_part_frame = $one_part_frame ? $one_part_frame->limit_users : '0';
    $two_part_frame = $two_part_frame ? $two_part_frame->limit_users : '0';
    $three_part_frame = $three_part_frame ? $three_part_frame->limit_users : '0';

    $html = [];
    $html[] = '<select name="getPart[]" class="border-primary" style="width:70px; border-radius:5px;" form="reserveParts">';
    $html[] = '<option value="" selected></option>';
    $html[] = $one_part_frame == "0"
      ? '<option value="1" disabled>リモ1部(残り0枠)</option>'
      : '<option value="1">リモ1部(残り' . $one_part_frame . '枠)</option>';
    $html[] = $two_part_frame == "0"
      ? '<option value="2" disabled>リモ2部(残り0枠)</option>'
      : '<option value="2">リモ2部(残り' . $two_part_frame . '枠)</option>';
    $html[] = $three_part_frame == "0"
      ? '<option value="3" disabled>リモ3部(残り0枠)</option>'
      : '<option value="3">リモ3部(残り' . $three_part_frame . '枠)</option>';
    $html[] = '</select>';

    // 日付の hidden を追加
    // $html[] = '<input type="hidden" name="getData[]" value="' . $targetDate->toDateString() . '"/>';

    return implode('', $html);
  }

  function getDate()
  {
    return '<input type="hidden" value="' . $this->carbon->format('Y-m-d') . '" name="getData[]" form="reserveParts">';
  }

  function everyDay()
  {
    return $this->carbon->format('Y-m-d');
  }

  function authReserveDay()
  {
    return Auth::user()->reserveSettings->pluck('setting_reserve')->toArray();
  }

  function authReserveDate($reserveDate)
  {
    return Auth::user()->reserveSettings->where('setting_reserve', $reserveDate);
  }
}
