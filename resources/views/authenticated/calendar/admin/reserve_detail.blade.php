<x-sidebar>
  <div class="vh-100 d-flex" style="align-items:center; justify-content:flex-start;">
    <div class="w-50 h-75 custom-container-wrapper">
      <p><span>{{ \Carbon\Carbon::parse($date)->format('Y年m月d日') }}</span><span class="ml-3">{{ $part }}部</span></p>
      <div class="custom-container">

        <div class="h-75 border  custom-table-container">
          <table class="table custom-table">
            <tr class="text-center">
              <th class="w-25">ID</th>
              <th class="w-25">名前</th>
              <th class="w-25">場所</th>
            </tr>

            <!-- 追記 -->
            {{-- 複数の設定がある場合を考慮してループ --}}
            @foreach($reservePersons as $setting)
            @foreach($setting->users as $user)
            <tr class="text-center">
              <td class="w-25">{{ $user->id }}</td>
              <td class="w-25">{{ $user->over_name }}{{ $user->under_name }}</td>
              <td class="w-25">リモート</td> {{-- 仮でリモートと固定表示。必要ならカラムを取得 --}}
            </tr>
            @endforeach
            @endforeach

          </table>
        </div>
      </div>

    </div>
  </div>
</x-sidebar>
