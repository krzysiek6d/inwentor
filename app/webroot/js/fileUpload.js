$(document).ready(function() {
    $('#ImageFile').bind('change', function() {
        var exten = this.files[0].name.substr( (this.files[0].name.lastIndexOf('.') +1) );
        var size = this.files[0].size;
        var message = "";
        var sizeInMB = 4;
        var isSizeOk = size <= sizeInMB*1048576;
        var isEtenOk = exten == 'jpg'  ||  exten == 'JPG' ||  exten == 'png' ||  exten == 'PNG';
        if(!isSizeOk || !isEtenOk) 
        {
            message += "Niezaakceptowano pliku " + this.files[0].name + ": \n";
            if(!isSizeOk){
                message += "#plik powyzej "+sizeInMB+"MB ";
                $("#ImageFile").replaceWith( $("#ImageFile").val('').clone( true ) );
            }
            if(!isEtenOk){
                message += "#nieprawidłowy typ pliku";
                $("#ImageFile").replaceWith( $("#ImageFile").val('').clone( true ) );  
            }
            $("#ImageFileError").css("display", "block");
        }
        else
            $("#ImageFileError").css("display", "none")
        $("#ImageFileError").text(message);

    });
});