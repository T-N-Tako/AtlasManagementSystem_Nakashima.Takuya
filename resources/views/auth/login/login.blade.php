<x-guest-layout>

  <div class="text-center pt-3">
    <img src="{{ asset('image/atlas-black.png') }}" alt="Atlas Logo" style=" height: 90px; margin:200px 0px 50px 0px;">
  </div>
  <form action="{{ route('loginPost') }}" method="POST">
    <div class="w-100  d-flex" style="align-items:center; justify-content:center;">
      <div class="border vh-50  login-content">
        <div class="w-75 m-auto pt-5">
          <label class="d-block m-0" style="font-size:13px; 	font-weight: bold;">メールアドレス</label>
          <div class="border-bottom border-primary w-100">
            <input type="text" class="w-100 border-0" name="mail_address">
          </div>
        </div>
        <div class="w-75 m-auto pt-5">
          <label class="d-block m-0" style="font-size:13px; font-weight: bold;">パスワード</label>
          <div class="border-bottom border-primary w-100">
            <input type="password" class="w-100 border-0" name="password">
          </div>
        </div>
        <div class="text-right m-3">
          <input type="submit" class="btn btn-primary" value="ログイン">
        </div>
        <div class="text-center">
          <a href="{{ route('registerView') }}">新規登録はこちら</a>
        </div>
      </div>
      {{ csrf_field() }}
    </div>
  </form>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
  <script src="{{ asset('js/register.js') }}" rel="stylesheet"></script>
</x-guest-layout>
