
$(function () {
    $("#btnAdd").bind("click", function () {
        var div = $("<tr />");
        div.html(GetDynamicTextBox(""));
        $("#TextBoxContainer").append(div);
    });
    $("body").on("click", ".remove", function () {
        $(this).closest("tr").remove();
    });
});
  
function GetDynamicTextBox(value) {  
    return '<td style="width: 95px;"><div class="">\n\
        <input type="text" name="add_more1[]" id="add_more1" placeholder="Title"  placeholder="" value = "' + value + '"  required="required" class="form-control">\n\
    </div>\n\
</td>'
            + '<td><div class="form-group">\n\
        <div class="col-md-12">\n\
            <input type="text" name="add_more[]" id="add_more" value = "' + value + '"  required="required" class="form-control">\n\
        </div>\n\
    </div>\n\
</td>'
            + '<td>\n\
    <button type="button" class="btn btn-danger remove"><i class="glyphicon glyphicon-remove-sign"></i></button>\n\
</td>\n\
'
}



$("form").on("submit", function (event) {

//    var fileInput = document.getElementById('article_image');
//    var file = fileInput.files[0];
//    var formData = new FormData();
//    formData.append('file', file);

console.log(event);

    var articleData = [];

    $('table#articleTable tr').each(function (index, item) {
        var rowCount = $('#articleTable tr').length - 1;
        var i = 0;
//        var articleData = [];
        var content = $(item).find("textarea[name='add_more1[]']").val();
        var service = $(item).find("select[name='add_more[]']").val();

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
        if (articleData[i].service)
            total++;
    }

    if (total > 5) {
        alert("You Cant Add More Than 5 Service In 1 Article");
    }

    var str = $("#defaultFormNameModalEx").val();

    console.log(str);


    console.log($("#formArticle").serialize());

//    $.ajax({
//        url: '/YiloYilo/api/file_upload',
//        method: 'post',
//        processData: false,
//        contentType: false,
//        data: {
//            "data": JSON.stringify(articleData),
//            "title": str,
//            "img": $("#formArticle").serialize()
//        },
//        success: function (err, data) {
//            if (err) {
//                console.log(err);
//            } else {
//                console.log(data);
//            }
//        }
//    });

//    $.ajax({
//        url: '/YiloYilo/Add_article',
//        method: 'post',
////        data: {
////            "data": JSON.stringify(articleData),
////            "title": str,
////            "image": file,
////        }, // prefer use serialize method
//        data:file,
//        success: function (data) {
//            console.log(data);
//        }
//    });

});