<x-sidebar>

  <div class="pt-5 pb-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:10px; background:#FFF; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <div class="w-75 m-auto" style="border-radius:5px;">
        <p class="text-center">{{ $calendar->getTitle() }}</p>
        <p>{!! $calendar->render() !!}</p>
      </div>
    </div>
  </div>

</x-sidebar>
