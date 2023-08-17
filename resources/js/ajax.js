// import $ from 'jquery';

// $(function () {
//   $(document).on('click', '#like-button', function (event) {
//     event.preventDefault();
//     const topicId = $(this).data('topicId');
//     console.log(topicId);
//     $.ajax({
//       url: `/topic/like/${topicId}`,
//       method: 'POST',
//       headers: {
//         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // CSRFトークン
//       }
//     }).done(function (response) {
//       console.log(response.liked);
//       if (response.liked) {
//         $(`#like-button > .like-text`).text('いいね!!を取り消す');
//       } else {
//         $(`#like-button > .like-text`).text('いいね!!');
//       }
//       // 「いいね」の数を更新
//       const currentCount = parseInt($(`.like-count`).text());
//       $(`.like-count`).text(response.liked ? currentCount + 1 : currentCount - 1);
//     }).fail(function (jqXHR, textStatus, errorThrown) {
//       console.log("Error:", jqXHR, textStatus, errorThrown);
//       alert("いいねの処理に失敗しました。");
//     });
//   });
// });

