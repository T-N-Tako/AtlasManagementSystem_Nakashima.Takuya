<x-sidebar>
  <div class="pt-5 pb-5" style="background:#ECF1F6;">
    <div class="w-80 m-auto" style="border-radius:5px;">
      <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:10px; background:#FFF; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); ">
        <div class="w-80 m-auto" style="border-radius:5px;">
          <p class="text-center">{{ $calendar->getTitle() }}</p>
          <div class="w-75 m-auto ">
            {!! $calendar->render() !!}
          </div>
        </div>
        <div class="text-right w-75 m-auto">
          <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
        </div>
      </div>
    </div>

    <!-- ✅ モーダル本体 -->
    <div class="modal js-cancel-modal">
      <div class="modal__bg js-cancel-close"></div>
      <div class="modal__content">
        <form action="{{ route('deleteParts') }}" method="post">
          <div class="w-100 px-4 py-3">
            <!-- ✅ 左寄せ＆横並び表示 -->
            <div class="modal-inner-title mb-2 d-flex">
              <p class="fw-bold mr-2 mb-0">予約日：</p>
              <p class="js-cancel-date mb-0"></p>
            </div>
            <div class="modal-inner-body mb-3 d-flex">
              <p class="fw-bold mr-2 mb-0">時間：</p>
              <p class="js-cancel-time mb-0"></p>
            </div>

            <!-- ✅ 確認メッセージ（左寄せ） -->
            <div class="mb-4">
              <p class="mb-0">上記の予約をキャンセルしてもよろしいですか？</p>
            </div>

            <!-- ✅ ボタン配置：左と右に分ける -->
            <div class="d-flex justify-content-between">
              <a class="js-cancel-close btn btn-secondary" href="">閉じる</a>
              <div>
                <input type="hidden" class="js-cancel-date-hidden" name="delete_date" value="">
                <input type="submit" class="btn btn-danger" value="キャンセル">
              </div>
            </div>
          </div>
          {{ csrf_field() }}
        </form>
      </div>
    </div>

  </div>

</x-sidebar>
