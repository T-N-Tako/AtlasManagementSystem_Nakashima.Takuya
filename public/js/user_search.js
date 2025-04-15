$(function () {
  $('.search_conditions').click(function () {
    $('.search_conditions_inner').slideToggle();

    // 矢印の方向を切り替え・追加
    $(this).find('.arrow-toggle').toggleClass('down');
  });

  $('.subject_edit_btn').click(function () {
    $('.subject_inner').slideToggle();
  });
});
