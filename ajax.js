function clear() {
    $("#word_count").text("");
    $("#link").text("");
    $("#money").text("");
    $("#error").text("");
}

$("#doc_form").submit(function(event) {
    event.preventDefault();
    clear();
    var form = new FormData(this);

    $.ajax({
        url: "upload.php",
        type: "POST",
        data: form,
	    processData: false,
        contentType: false,
        success: function(response) {
            if(response["status"] == "ok") {
                $("#word_count").text(`Количество слов в документе: ${response["word_count"]}`);
                $("#link").text(`Ссылка на сервере: ${response["link"]}`);
                $("#money").text(`Стоимость: ${response["money"]} руб.`);
            }
            else {
                $("#error").text(response["status"]);
            }
        },
        error: function(jqXHR, execption) {
            console.error(jqXHR + "\n" + execption);
        }
    });
})
