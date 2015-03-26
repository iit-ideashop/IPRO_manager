@extends('layouts.master')
@include('layouts.fontawesome')
@section('content')
    @include('project.projectNavigation')
    <div class="page-header">
        <h1>Print IPRO Day materials</h1>
    </div>
    <div class="row">
        <div class="col-xs-5 col-xs-offset-1 verticalDivider">
            <div style="text-align: center">
                <i class="fa fa-photo fa-5x"></i><br>
                <h3 style="text-decoration: underline">Submit a Poster</h3>
                <p>Approved sizes</p>
                <p>36 x 24</p>
                <p>24 x 36</p>
                <p>36 x 48</p>
                <p>48 x 36</p>
                <p>Other upon approval</p>
                <a onclick="$('#posterupload').click();" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> Select poster .pdf</a>
                {{ Form::file("posterupload",array("style"=>"display:none;","id"=>"posterUpload","multiple"=>true)) }}
            </div>
        </div>
        <div class="col-xs-5">
            <div style="text-align: center">
                <i class="fa fa-file-o fa-5x"></i><br>
                <h3 style="text-decoration: underline">Submit a Brochure</h3>
                <p>Approved sizes</p>
                <p>8.5 x 11</p>
                <p>11 x 8.5</p>
                <p>Other upon approval</p>
                <a onclick="$('#brochureUpload').click();" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> Select brochure .pdf</a>
                {{ Form::file("brochureupload[]",array("style"=>"display:none;","id"=>"brochureUpload","multiple"=>true)) }}
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="progress">
                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="fileUploadPercentage">
                </div>
            </div>
        </div>
    </div>
    <br>
    <table class="table table-striped" id="fileuploadstable">
        <tr>
            <th> </th>
            <th>Filename</th>
            <th>Size</th>
            <th>Dimensions</th>
            <th>Uploaded By</th>
            <th>Uploaded Date/Time</th>
            <th>Status</th>
        </tr>
    </table>
@stop
@section('javascript_bottom')
    <script>
        var filesArray = [];
        var controlVar = 0;
        var processingUploads = false;
        $(document).on("ready",function(){
            $("#brochureUpload").on("change",function(event){
                //Verify the selected file is a .pdf
                //We need to loop through the files array
                var fileArr = event.target.files;
                //For loop each file
                for(var i=0; i < fileArr.length; i++){
                    var fileHasErrors = false;
                    //Create a new line in the files table for this upload
                    $("#fileuploadstable").append('<tr id="upload'+controlVar+'row">'+
                    '<td></td>'+
                    '<td>'+fileArr[i].name+'</td>'+
                    '<td>'+(fileArr[i].size/1048576).toPrecision(2)+'MB</td>'+
                    '<td colspan="4" id="upload'+controlVar+'statusBlock"></td>'+
                    '</tr>');

                    if(fileArr[i].type != "application/pdf"){
                        $("#upload"+controlVar+"statusBlock").html("The selected file is not a PDF");
                        fileHasErrors = true;
                    }
                    //We have a pdf!
                    if(fileArr[i].size > 100000000){
                        //Greater than 100mb
                        $("#upload"+controlVar+"statusBlock").html("The selected file is greater than the 100mb limit");
                        fileHasErrors = true;
                    }
                    if(!fileHasErrors) {
                        //File is validated, add it to the file upload array and add a progress bar
                        $("#upload"+controlVar+"statusBlock").append('<div class="progress">'+
                        '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="upload'+controlVar+'progress">'+
                        '</div>'+
                        '</div>');
                        var fileObj = {controlid: controlVar, filename: fileArr[i].name, file: fileArr[i]}
                        filesArray.push(fileObj);
                    }
                    controlVar = controlVar + 1;
                }
            });
        });


        //When we process uploads we have to take the upload array and process it one by one
        //each time this function runs it will take 1 file from the fileuploads array and upload it
        function processUploads(){
            var fd = new FormData();
            fd.append("fileUpload",file);
            $.ajax({
                url: '{{ URL::route('project.printSubmission.files',$class->id) }}',
                type: 'POST',
                data: fd,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        console.log(evt);
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            updateFileUploadPercentage(percentComplete*100);
                        }
                    }, false);
                    return xhr;
                },
                success: function(data, textStatus, jqXHR){
                    alert(data);
                },
                error: function(data, textStatus, jqXHR){
                    alert("error");
                    console.log(data);
                    console.log(textStatus);
                }
            });
        }

        function updateFileUploadPercentage(percentage,barid){
            var percent = Math.ceil(percentage);
            $("#fileUploadPercentage").attr("aria-valuenow", percent);
            $("#fileUploadPercentage").css("width", percent+"%");
            //Only show progress > 10%
            if((percent > 10) && (percent < 100)) {
                $("#fileUploadPercentage").html("Upload Progress: " + percent + "%");
            }else if(percent == 100){
                $("#fileUploadPercentage").html("Processing upload...");
            }else{
                $("#fileUploadPercentage").html("...");
            }
        }

        function addUploadedMaterialLine(filename, filesize, uploaded_by, dimensions, show_override){
            $("#fileuploadstable").append('<tr>' +
            '<td></td>' + //Thumbnail
            '<td>'+filename+'</td>' + //filename
            '<td>'+filesize+'</td>' + // filesize
            '<td>'+dimensions+'</td>' + //dimensions
            '<td>'+uploaded_by+'</td>' + //uploader
            '<td></td>' + //override
            '</tr>');
        }
    </script>
@stop
