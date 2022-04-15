
$(function () {
       $("#btnAdd").bind("click", function () {
              console.log('Raj Kanani');

              var div = $("<tr />");
              div.html(GetDynamicTextBox(""));
              $("#TextBoxContainer").append(div);
       });
       $("body").on("click", ".remove", function () {
              $(this).closest("tr").remove();
       });
});
function GetDynamicTextBox(value) {
       console.log('Raj Kanani');

       return '<td><textarea name = "DynamicTextBox[]" placeholder="Content" type="text" value = "' + value + '" class="form-control"></textarea></td>'
               + '<td><select name="content_type[]" class="form-control"><option value="0"> Text</option><option value="1">Service</option></select></td>'
               + '<td><button type="button" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove-sign"></i></button></td>'
}



$("form").on("submit", function (event) {
       console.log('Raj Kanani');

       var fileInput = document.getElementById('article_image');
       var file = fileInput.files[0];
       var formData = new FormData();
       formData.append('file', file);

       var articleData = [];


       $('table#articleTable tr').each(function (index, item) {
              var rowCount = $('#articleTable tr').length - 1;
              var i = 0;
//        var articleData = [];
              var content = $(item).find("textarea[name='DynamicTextBox[]']").val();
              var service = $(item).find("select[name='content_type[]']").val();

              if (index > 0 && index != rowCount) {
                     var data = {};
                     data.content = content;
                     data.service = service;
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

       var str = $("#defaultFormNameModalEx").val();

       console.log(str);
       console.log($("#formArticle").serialize());


       $.ajax({
              url: '/YiloYilo/Add_article',
              method: 'post',
              data: {
                     "data": JSON.stringify(articleData),
                     "title": str,
                     "image": file,
              }, // prefer use serialize method

              success: function (data) {
                     console.log(data);
              }
       });

});