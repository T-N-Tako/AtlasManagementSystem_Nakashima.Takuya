<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show()
    {
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request)
    {
        DB::beginTransaction();
        try {
            $getPart = (array) $request->get('getPart', []);
            $getDate = (array) $request->get('getData', []);



            // logger()->info('【初期状態】', [
            //     'getPart' => $getPart,
            //     'getPart_type' => gettype($getPart),
            //     'getDate' => $getDate,
            //     'getDate_type' => gettype($getDate),
            // ]);


            // ✅ 空の getPart[] を除外し、$getDate[] の対応も消す
            foreach ($getPart as $index => $val) {
                if ($val === null || $val === '' || !isset($getDate[$index]) || $getDate[$index] === '') {

                    logger()->warning("index $index の値が空のため削除", [
                        'getPart_val' => $val,
                        'getDate_val' => $getDate[$index] ?? null,
                    ]);

                    unset($getPart[$index], $getDate[$index]);
                }
            }

            // ✅ 再インデックスでズレを解消
            $getPart = array_values($getPart);
            $getDate = array_values($getDate);


            // logger()->info('【再構成後】', [
            //     'count_getPart' => count($getPart),
            //     'count_getDate' => count($getDate),
            // ]);


            if (count($getPart) !== count($getDate)) {
                logger()->error('予約データの数が一致していません', [
                    'getPart' => $getPart,
                    'getDate' => $getDate,
                ]);
                return back()->with('error', '予約情報の送信に失敗しました。もう一度お試しください。');
            }





            $reserveDays = array_combine($getDate, $getPart);

            foreach ($reserveDays as $date => $part) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $date)
                    ->where('setting_part', $part)
                    ->first();

                if ($reserve_settings) {
                    $reserve_settings->decrement('limit_users');
                    $reserve_settings->users()->attach(Auth::id());
                }
            }




            // $reserveDays = array_filter(array_combine($getDate, $getPart));
            // foreach ($reserveDays as $key => $value) {
            //     $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
            //     $reserve_settings->decrement('limit_users');
            //     $reserve_settings->users()->attach(Auth::id());
            // }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
