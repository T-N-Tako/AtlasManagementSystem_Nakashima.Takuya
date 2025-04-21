$(function () {
  // 追記
$('.cancel-modal-open').on('click', function () {
    const reserveDate = $(this).data('date');
    const reserveTime = $(this).data('time');

    $('.js-cancel-date').text(reserveDate);
    $('.js-cancel-time').text(reserveTime);
    $('.js-cancel-date-hidden').val(reserveDate);

    $('.js-cancel-modal').fadeIn();
    return false;
  });

  $('.js-cancel-close').on('click', function () {
    $('.js-cancel-modal').fadeOut();
    return false;
  });
});
