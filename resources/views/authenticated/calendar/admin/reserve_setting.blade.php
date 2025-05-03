<x-sidebar>
  <div class="pt-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:10px; background:#FFF; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <p class="text-center">{{ $calendar->getTitle() }}</p>

      {!! $calendar->render() !!}
      <div class="adjust-table-btn m-auto text-right">
        <input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm('登録してよろしいですか？')">
      </div>
    </div>
  </div>
</x-sidebar>
