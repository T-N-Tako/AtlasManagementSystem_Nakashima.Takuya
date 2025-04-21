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
            $getPart = $request->getPart;
            $getDate = $request->getData;
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach ($reserveDays as $key => $value) {
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }


    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $deleteDate = $request->input('delete_date');
            $userId = Auth::id();

            // 指定日付の予約を取得（複数部数ある可能性も考慮）
            $reserved = ReserveSettings::where('setting_reserve', $deleteDate)
                ->whereHas('users', function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                })
                ->get();

            foreach ($reserved as $setting) {
                // 人数を戻す
                $setting->increment('limit_users');
                // ユーザーとの関連を削除（中間テーブルから削除）
                $setting->users()->detach($userId);
            }

            DB::commit();
            return redirect()->route('calendar.general.show', ['user_id' => $userId])
                ->with('success', '予約をキャンセルしました。');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'キャンセル処理に失敗しました。');
        }
    }
}
