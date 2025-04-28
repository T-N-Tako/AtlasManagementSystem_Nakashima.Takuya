$(function () {
  $('.main_categories').click(function () {
    var category_id = $(this).attr('category_id');
    $('.category_num' + category_id).slideToggle();

    // 矢印を回転（CSSクラスの切り替え）・追記
    $(this).children('.arrow-icon').toggleClass('rotate');
  });

  $(document).on('click', '.like_btn', function (e) {
    e.preventDefault();

    $(this).removeClass('like_btn far').addClass('un_like_btn fas');
    // $(this).addClass('un_like_btn');
    // $(this).removeClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);
    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/like/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      console.log(res);
      $('.like_counts' + post_id).text(countInt + 1);
    }).fail(function (res) {
      console.log('fail');
    });
  });

  $(document).on('click', '.un_like_btn', function (e) {
    e.preventDefault();

    $(this).removeClass('un_like_btn fas').addClass('like_btn far');
    // $(this).removeClass('un_like_btn');
    // $(this).addClass('like_btn');
    var post_id = $(this).attr('post_id');
    var count = $('.like_counts' + post_id).text();
    var countInt = Number(count);

    $.ajax({
      headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
      method: "post",
      url: "/unlike/post/" + post_id,
      data: {
        post_id: $(this).attr('post_id'),
      },
    }).done(function (res) {
      $('.like_counts' + post_id).text(countInt - 1);
    }).fail(function () {

    });
  });

  $('.edit-modal-open').on('click',function(){
    $('.js-modal').fadeIn();
    var post_title = $(this).attr('post_title');
    var post_body = $(this).attr('post_body');
    var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(post_title);
    $('.modal-inner-body textarea').text(post_body);
    $('.edit-modal-hidden').val(post_id);
    return false;
  });
  $('.js-modal-close').on('click', function () {
    $('.js-modal').fadeOut();
    return false;
  });

});


document.addEventListener("DOMContentLoaded", function() {
  const mainCategories = document.querySelectorAll(".main_category");

  mainCategories.forEach(category => {
      category.addEventListener("click", function() {
          const subCategoryList = category.querySelector(".sub_category_list");
          const arrowIcon = category.querySelector(".arrow-icon");

          if (subCategoryList) {
              // サブカテゴリーの表示・非表示を切り替え
              subCategoryList.style.display = subCategoryList.style.display === "none" ? "block" : "none";

              // 矢印アイコンの回転を切り替え
              arrowIcon.classList.toggle("rotate");
          }
      });
  });
});
