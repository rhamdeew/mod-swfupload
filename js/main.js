$(document).ready(function() {
var percent = 0;
    
    function uploadSuccess(file, serverData) {
        $('#images').append($(serverData));
    }
    
    function uploadComplete(file) {
        $('#status').append($('<p>Загрузка ' + file.name + ' завершена</p>'));
	percent = 0;
    }
    
    function uploadStart(file) {
        $('#status').append($('<p>Начата загрузка файла ' + file.name + '</p>'));
	$('#showlink').show();
        return true;
    }
    
    function uploadProgress(file, bytesLoaded, bytesTotal) {
	var percent2 = Math.round(bytesLoaded/bytesTotal*100);
	if(percent!=percent2) {
	if(percent==0 && percent2!=0) $('#status').append($('<p>Загружено ' + percent + '% файла ' + file.name + '</p>'));
        $('#status').append($('<p>Загружено ' + percent2 + '% файла! ' + file.name + '</p>'));
	percent = percent2;
	}
    }

    function fileDialogComplete(numFilesSelected, numFilesQueued) {
        $('#status').html($('<p>Выбрано ' + numFilesSelected + ' файл(ов), начинаем загрузку</p>'));
        this.startUpload(); 
    }

    var swfu = new SWFUpload(
        {
            upload_url : "upload.php",
            flash_url : "swfupload.swf",
	button_window_mode : "transparent",
            button_placeholder_id : "uploadButton",
            
            file_size_limit : "10 MB",
            file_types : "*.jpg; *.png; *.jpeg; *.gif",
            file_types_description : "Images",
            file_upload_limit : "15",
            debug: false,

            button_image_url: "button.png",
            button_width : 100,
            button_height : 30,
            button_text_left_padding: 15,
            button_text_top_padding: 2, 
            button_text : "<span class=\"uploadBtn\">Обзор...</span>",
            button_text_style : ".uploadBtn { font-size: 15px; font-family:Verdana; background-color:#CCF;}",
            
            file_dialog_complete_handler : fileDialogComplete,

            upload_success_handler : uploadSuccess,
            upload_complete_handler : uploadComplete,
            upload_start_handler : uploadStart,
            upload_progress_handler : uploadProgress
        }
    );
});

function showlog() {
	$('#status').toggle();
	if($('#showlink').text()=="показать лог") $('#showlink').text("скрыть лог");
	else $('#showlink').text("показать лог");
} 
