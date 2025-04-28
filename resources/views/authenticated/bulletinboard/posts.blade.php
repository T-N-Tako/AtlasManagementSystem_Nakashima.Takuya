<x-sidebar>
  <div class="board_area w-100 border m-auto d-flex">

    <div class="post_view w-75 mt-5">
      <!-- <p class="w-75 m-auto">投稿一覧</p> -->
      @foreach($posts as $post)
      <div class="post_area border w-75 m-auto p-3">
        <p class="name"><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
        <p class="post_title"><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
        <div class="post_bottom_area d-flex">
          {{-- サブカテゴリー表示 --}}
          <div class="d-flex align-items-center">
            @foreach($post->subCategories as $subCategory)
            <span class="sub_categories">{{ $subCategory->sub_category }}</span>
            @endforeach
          </div>
          <div class="d-flex post_status">
            <div class="icon-with-count mr-5">
              <i class="fa fa-comment"></i><span class="comment-count">{{ $post->postComments->count() }}</span>
            </div>
            <div class="icon-with-count">
              @if(Auth::user()->is_Like($post->id))
              <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }} like_counts">{{ $post->likes->count() }}</span>
              @else
              <i class="far fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts like_counts{{ $post->id }} ">{{ $post->likes->count() }}</span>
              @endif
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <div class="other_area border w-25">
      <div class="m-4">
        <div><a href="{{ route('post.input') }}" class="post_submit">投稿</a></div>
        <div class="search_keyword">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>
        <input type="submit" name="like_posts" class="category_like_btn" value="いいねした投稿" form="postSearchRequest">
        <input type="submit" name="my_posts" class="category_btn" value="自分の投稿" form="postSearchRequest">

        <p>カテゴリー検索</p>

        <ul class="category_list">
          @foreach($categories as $category)
          <li class="main_category" category_id="{{ $category->id }}">
            <span class="category_title">{{ $category->main_category }}</span>
            <i class="arrow-icon fas fa-chevron-down"></i>
            {{-- サブカテゴリー表示（追記） --}}
            @if($category->subCategories && $category->subCategories->isNotEmpty())
            <ul class="sub_category_list pl-3 category_num{{ $category->id }}" style="display: none;">
              @foreach($category->subCategories as $sub)
              <li class="sub_category_item" sub_category_id="{{ $sub->id }}">
                {{-- サブカテゴリー名クリックでリンク --}}
                <a href="{{ route('post.show', ['sub_category_id' => $sub->id]) }}">
                  {{ $sub->sub_category }}
                </a>
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
