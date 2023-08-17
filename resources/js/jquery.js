import $ from 'jquery';

$(function () {
  $('.topic-content').each(function () {
    var maxLength = 10;  // 文字数制限
    var content = $(this).text();
    if (content.length > maxLength) {
      var trimmedContent = content.slice(0, maxLength) + '...';
      $(this).text(trimmedContent);
    }
  });
});

