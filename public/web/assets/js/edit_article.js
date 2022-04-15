
$(function () {
       $("#btnAdd").bind("click", function () {
              var div = $("<tr />");
              div.html(GetDynamicTextBox(""));
              $("#TextBoxContainer").append(div);
       });
       $("body").on("click", ".remove", function () {
              $(this).closest("tr").remove();
       });
//    $("body").on("click", ".remove2", function () {
//        $(this).closest("tr").remove();
//        $(this).attr('tr','disabled');
//        $('tr').prop('disabled', 'disabled').css('background-color', 'grey');
//    });
});
function GetDynamicTextBox(value) {
       return '<input type="hidden" id="article_data_id" name="article_data_id" value="">' +
               '<td><textarea name = "DynamicTextBox[]" placeholder="Content" type="text" value = "' + value + '" class="form-control"></textarea></td>'
               + '<td><select name="content_type[]" class="form-control"><option value="0"> Text</option><option value="1">Service</option></select></td>'
               + '<td><button type="button" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove-sign"></i></button></td>'
}



$("#btnSubmit").bind("click", function () {
       var articleData = [];

       $('table#articleTable tr').each(function (index, item) {
              var rowCount = $('#articleTable tr').length - 1;
              var i = 0;
//        var articleData = [];
              var myFile = $('#fileinput').prop('files');
              var content = $(item).find("textarea[name='DynamicTextBox[]']").val();
              var article_data_id = $(item).find("input[name='article_data_id']").val();
              var service = $(item).find("select[name='content_type[]']").val();
//        var article_data_id = $("#article_data_id").val();

              if (index > 0 && index != rowCount) {
                     var data = {};
                     data.content = content;
                     data.service = service;
                     data.article_data_id = article_data_id;
                     articleData.push(data);
                     i++
              }
       });


       var total = 0;

       for (var i = 0; i < articleData.length; i++) {
              if (articleData[i].service == 1)
                     total++;
       }

       if (total > 5) {
              alert("You Cant Add More Than 5 Service In 1 Article");
       }


       JSON.stringify(articleData);
       var str = $("#defaultFormNameModalEx").val();
       var article_id = $("#article_id").val();
       var article_data_id = $("#article_data_id").val();

       $.ajax({
              url: '/YiloYilo/Edit_article/' + article_id,
              method: 'post',
              data: {
                     "data": JSON.stringify(articleData),
                     "title": str,
                     "article_id": article_id,
              }, // prefer use serialize method
              success: function (data) {
                     console.log(data);
                     location.reload();
              }
       });

       console.log("article_data_id: " + article_data_id);



       console.log(JSON.stringify(articleData));
       console.log(str);
       console.log(articleData);
});