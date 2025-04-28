
$(function () {
  // 検索条件エリアの開閉と矢印回転
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();
    $(this).find('.arrow-icon').toggleClass('rotate');
  });

  // 選択科目編集エリアの開閉と矢印回転
  $('.subject_edit_btn_wrapper').click(function () {
    $('.subject_inner').slideToggle();
    $(this).find('.arrow-icon').toggleClass('rotate');
  });

  // ✅ チェックボックスクリック時は親へのイベント伝播を止める
  $('.subject_inner input[type="checkbox"]').click(function (e) {
    e.stopPropagation();
  });
});
