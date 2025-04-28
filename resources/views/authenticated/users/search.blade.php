<x-sidebar>
  <!-- <p>ユーザー検索</p> -->
  <div class="search_content w-100 d-flex">
    <div class="reserve_users_area">
      @foreach($users as $user)
      <div class="border one_person">
        <div>
          <span>ID : </span><span>{{ $user->id }}</span>
        </div>
        <div class="names">
          <div><span>名前 : </span>
            <a href="{{ route('user.profile', ['id' => $user->id]) }}">
              <span>{{ $user->over_name }}</span>
              <span>{{ $user->under_name }}</span>
            </a>
          </div>
          <div>
            <span>カナ : </span>
            <span>({{ $user->over_name_kana }}</span>
            <span>{{ $user->under_name_kana }})</span>
          </div>
        </div>
        <div>
          @if($user->sex == 1)
          <span>性別 : </span><span>男</span>
          @elseif($user->sex == 2)
          <span>性別 : </span><span>女</span>
          @else
          <span>性別 : </span><span>その他</span>
          @endif
        </div>
        <div class="birth_day">
          <span>生年月日 : </span><span>{{ $user->birth_day }}</span>
        </div>
        <div>
          @if($user->role == 1)
          <span>権限 : </span><span>教師(国語)</span>
          @elseif($user->role == 2)
          <span>権限 : </span><span>教師(数学)</span>
          @elseif($user->role == 3)
          <span>権限 : </span><span>講師(英語)</span>
          @else
          <span>権限 : </span><span>生徒</span>
          @endif
        </div>


        <div>
          @if($user->role == 4)
          <span>選択科目 : </span>
          @if($user->subjects->contains('subject', '国語'))
          <span>国語</span>
          @endif
          @if($user->subjects->contains('subject', '数学'))
          <span>数学</span>
          @endif
          @if($user->subjects->contains('subject', '英語'))
          <span>英語</span>
          @endif
          @endif
        </div>

      </div>
      @endforeach
    </div>
    <div class="search_area ">
      <div class="">
        <p class="search">検索</p>
        <div>
          <input type="text" class="free_word" name="keyword" placeholder="キーワードを検索" form="userSearchRequest">
        </div>
        <div class="search-condition-wrapper">
          <label class="search-condition">カテゴリ</label>
          <select form="userSearchRequest" name="category">
            <option value="name">名前</option>
            <option value="id">社員ID</option>
          </select>
        </div>
        <div class="search-condition-wrapper">
          <label class="search-condition">並び替え</label>
          <select name="updown" form="userSearchRequest">
            <option value="ASC">昇順</option>
            <option value="DESC">降順</option>
          </select>
        </div>
        <div class="">
          <p class="m-0 search_conditions" style="cursor: pointer;">
            <span class="search-addition">検索条件の追加</span>
            <i class="arrow-icon fas fa-chevron-down"></i>
          </p>
          <div class="search_conditions_inner" style="display: none;">
            <div class="search-gender">
              <label class="search-condition">性別</label>
              <span>男</span><input type="radio" name="sex" value="1" form="userSearchRequest">
              <span>女</span><input type="radio" name="sex" value="2" form="userSearchRequest">
              <span>その他</span><input type="radio" name="sex" value="3" form="userSearchRequest">
            </div>
            <div>
              <label class="search-condition">権限</label>
              <select name="role" form="userSearchRequest" class="engineer">
                <option selected disabled>----</option>
                <option value="1">教師(国語)</option>
                <option value="2">教師(数学)</option>
                <option value="3">教師(英語)</option>
                <option value="4" class="">生徒</option>
              </select>
            </div>
            <div class="selected_engineer">
              <label class="search-condition">選択科目</label>
              <div class="subject-list">
                @foreach($subjects as $subject)
                <div class="subject-item">
                  <label>{{ $subject->subject }} <input type="checkbox" name="subject[]" value="{{ $subject->id }}" form="userSearchRequest"></label>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="search-btn-wrapper">
          <input type="submit" name="search_btn" class="search-btn" value="検索" form="userSearchRequest">
        </div>
        <div>
          <input type="reset" value="リセット" form="userSearchRequest">
        </div>
      </div>
      <form action="{{ route('user.show') }}" method="get" id="userSearchRequest"></form>
    </div>
  </div>
</x-sidebar>
