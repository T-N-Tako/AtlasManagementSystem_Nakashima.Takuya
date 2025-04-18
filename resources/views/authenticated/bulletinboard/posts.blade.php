<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">
    <div class="post_view w-75 mt-5">
      <p class="w-75 m-auto">投稿一覧</p>
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
        <div class="post_bottom_area d-flex">
          <div class="d-flex post_status">
            <div class="mr-5">
              <i class="fa fa-comment"></i><span class="">{{ $post->postComments->count() }}</span>
            </div>
            <div>
              @if(Auth::user()->is_Like($post->id))
              <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span></p>
              @else
              <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likes->count() }}</span></p>
              @endif
            </div>
          </div>

        </div>
        {{-- サブカテゴリー表示 --}}
        <div class="d-flex align-items-center">
          @foreach($post->subCategories as $subCategory)
          <span class="badge badge-secondary ml-1">{{ $subCategory->sub_category }}</span>
          @endforeach
        </div>
      </div>
      @endforeach
    </div>
    <div class="other_area border w-25">
      <div class="border m-4">
        <div class=""><a href="{{ route('post.input') }}">投稿</a></div>
        <div class="">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="category_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">
        <ul>
          @foreach($categories as $category)
          <li class="main_categories" category_id="{{ $category->id }}">
            <div class="d-flex justify-content-between" align-items-center">
              <span>{{ $category->main_category }}<span>
                  <span class="arrow-toggle down"></span> {{-- CSSで矢印を表示 --}}
            </div>
            {{-- サブカテゴリー表示（追記） --}}
            @if($category->subCategories && $category->subCategories->isNotEmpty())
            <ul class="pl-3 category_num{{ $category->id }}" style="display: none;">
              @foreach($category->subCategories as $sub)
              <li class="sub_category_item" sub_category_id="{{ $sub->id }}">
                {{-- サブカテゴリー名クリックでリンク --}}
                <a href="{{ route('post.show', ['sub_category_id' => $sub->id]) }}">
                  {{ $sub->sub_category }}
                </a>

                <!-- <span>{{ $sub->sub_category }}</span> -->
              </li>
              @endforeach
            </ul>
            @endif

          </li>
          @endforeach
        </ul>
      </div>
    </div>
    <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
  </div>
</x-sidebar>
