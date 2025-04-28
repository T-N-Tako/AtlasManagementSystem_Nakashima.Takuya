<x-sidebar>
  <div class="vh-100 d-flex">
    <div class="w-50 mt-5">
      <div class="m-3 detail_container">
        <div class="p-3">
          <div class="detail_inner_head">
            <div>
            </div>
            <div>
              @if (Auth::id() == $post->user_id)
              <span class="btn btn-primary btn-sm edit-modal-open" post_title="{{ $post->post_title }}" post_body="{{ $post->post }}" post_id="{{ $post->id }}">編集</span>
              <!-- トースト -->
              <a href="{{ route('post.delete', ['id' => $post->id]) }}" class="btn btn-danger btn-sm"
                onclick="return confirm('この投稿を削除します。よろしいでしょうか？')">削除</a>
              @endif
            </div>
          </div>

          {{-- 編集バリデーション用のエラー表示 --}}
          @if ($errors->has('post_title') || $errors->has('post_body'))
          <div class="text-danger mt-2" style="font-size:13px;">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          {{-- 投稿に紐づくサブカテゴリーの表示 --}}
          @if($post->subCategories && $post->subCategories->isNotEmpty())
          <div class="mt-2">
            @foreach($post->subCategories as $subCategory)
            <span class="sub_categories">{{ $subCategory->sub_category }}</span>
            @endforeach
          </div>
          @endif
          <div class="contributor d-flex">
            <p>
              <span>{{ $post->user->over_name }}</span>
              <span>{{ $post->user->under_name }}</span>
              さん
            </p>
            <!-- <span class="ml-5">{{ $post->created_at ?? '登録日時なし'}}</span> -->
          </div>
          <div class="detsail_post_title">{{ $post->post_title }}</div>
          <div class="mt-3 detsail_post">{{ $post->post }}</div>
        </div>
        <div class="p-3">
          <div class="comment_container">
            <span class="">コメント</span>
            @foreach($post->postComments as $comment)
            <div class="comment_area border-top">
              <p>
                <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
                <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
              </p>
              <p>{{ $comment->comment }}</p>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="w-50 p-3">
      <div class="comment_container border m-5">
        <div class="comment_area p-3">
          @if($errors->has('comment'))
          <span class="text-danger">{{ $errors->first('comment') }}</span>
          @endif
          <p class="m-0">コメントする</p>
          <textarea class="w-100" name="comment" form="commentRequest"></textarea>
          <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
          <div class="mt-3 text-right">
            <input type="submit" class="btn btn-primary" value="投稿" form="commentRequest">
          </div>
          <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}</form>
        </div>
      </div>
    </div>
  </div>
  <div class="modal js-modal">
    <div class="modal__bg js-modal-close"></div>
    <div class="modal__content">
      <form action="{{ route('post.edit') }}" method="post">
        <div class="w-100">
          <div class="modal-inner-title w-50 m-auto">
            <input type="text" name="post_title" placeholder="タイトル" class="w-100">
          </div>
          <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
            <textarea placeholder="投稿内容" name="post_body" class="w-100"></textarea>
          </div>
          <div class="w-50 m-auto edit-modal-btn d-flex">
            <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
            <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
            <input type="submit" class="btn btn-primary d-block" value="編集">
          </div>
        </div>
        {{ csrf_field() }}
      </form>
    </div>
  </div>
</x-sidebar>
