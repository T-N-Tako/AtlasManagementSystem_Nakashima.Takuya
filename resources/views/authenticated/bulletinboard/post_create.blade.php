<x-sidebar>
  <div class="post_create_container d-flex">
    <div class="post_create_area border w-50 m-5 p-5">
      <div class="">
        <p class="mb-0">カテゴリー</p>
        <select class="w-100" form="postCreate" name="post_category_id">
          @foreach($main_categories as $main_category)
          <optgroup label="{{ $main_category->main_category }}">
            <!-- サブカテゴリー表示 -->
            <!-- 追記 -->
            @foreach($main_category->subCategories as $sub)
            <option value="{{ $sub->id }}">{{ $sub->sub_category }}</option>
            @endforeach

          </optgroup>
          @endforeach
        </select>
      </div>
      <div class="mt-3">
        @if($errors->has('post_title'))
        <span class="error_message">{{ $errors->first('post_title') }}</span>
        @endif
        <p class="mb-0">タイトル</p>
        <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
      </div>
      <div class="mt-3">
        @if($errors->has('post_body'))
        <span class="error_message">{{ $errors->first('post_body') }}</span>
        @endif
        <p class="mb-0">投稿内容</p>
        <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
      </div>
      <div class="mt-3 text-right">
        <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
      </div>
      <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
    </div>
    @can('admin')
    <div class="w-25 ml-auto mr-auto">
      <div class="category_area mt-5 p-5">

        <div class="">
          @if($errors->has('main_category_name'))
          <span class="text-danger" style="font-size:13px;">{{ $errors->first('main_category_name') }}</span>
          @endif
          <p class="m-0">メインカテゴリー</p>
          <input type="text" class="w-100" name="main_category_name" form="mainCategoryRequest">
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="mainCategoryRequest">
          <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">{{ csrf_field() }}</form>
        </div>

        <!-- サブカテゴリー追加 -->
        {{-- サブカテゴリー --}}
        <div class="mb-3">
          @if($errors->has('main_category_id'))
          <span class="error_message">{{ $errors->first('main_category_id') }}</span>
          @endif
          @if($errors->has('sub_category_name'))
          <span class="text-danger" style="font-size:13px;">{{ $errors->first('sub_category_name') }}</span>
          @endif
          <p class="m-0">サブカテゴリー</p>
          {{-- メインカテゴリー選択用セレクトボックス --}}
          <select name="main_category_id" class="w-100 mb-2" form="subCategoryRequest">
            <option value="none">---</option>
            @foreach($main_categories as $main_category)
            <option value="{{ $main_category->id }}">{{ $main_category->main_category}}</option>
            @endforeach
          </select>

          {{-- サブカテゴリー名入力 --}}
          <input type="text" class="w-100 mb-2" name="sub_category_name" form="subCategoryRequest">

          {{-- 追加ボタン --}}
          <input type="submit" value="追加" class="w-100 btn btn-primary p-0" form="subCategoryRequest">
        </div>
        <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">
          {{ csrf_field() }}
        </form>

      </div>
    </div>
    @endcan
  </div>
</x-sidebar>
