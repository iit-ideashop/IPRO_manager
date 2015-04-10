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
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-5 col-xs-offset-1 verticalDivider">
            <div style="text-align: center">
                <a onclick="$('#posterUpload').click();" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> Select poster .pdf</a>
                {{ Form::file("posterupload[]",array("style"=>"display:none;","id"=>"posterUpload","multiple"=>true)) }}
            </div>
        </div>
        <div class="col-xs-5">
            <div style="text-align: center">
                <a onclick="$('#brochureUpload').click();" class="btn btn-primary"><i class="fa fa-file-pdf-o"></i> Select brochure .pdf</a>
                {{ Form::file("brochureupload[]",array("style"=>"display:none;","id"=>"brochureUpload","multiple"=>true)) }}
            </div>
        </div>
    </div>

    <div class="page-header"><h3>Files uploaded for {{ $class->Name }}</h3></div>
    <table class="table table-striped" id="fileuploadstable">
        <tr>
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
        var controlVar = 1;
        var processingUploads = false;
        $(document).on("ready",function(){
            $("#brochureUpload").on("change",function(event){
                //Verify the selected file is a .pdf
                //We need to loop through the files array
                var fileArr = event.target.files;
                acceptFiles(fileArr, "Brochure");
            });
            $("#posterUpload").on("change",function(event){
                //Verify the selected file is a .pdf
                //We need to loop through the files array
                var fileArr = event.target.files;
                acceptFiles(fileArr, "Poster");
            });
            //Pull down the current file listing
            $.ajax({
                url: '{{ URL::route('project.printSubmission.getfiles',$class->id) }}',
                type: 'GET',
                dataType: 'json',
                processData: false,
                success: function(data){
                    if(data.error){
                        //If the array contains an error we have to show an error.
                        alert(data.error);
                        return false;
                    }
                    //Let's take the file object returned and do magic with it
                    for(var i =0; i < data.length; i++){

                        addUploadedMaterialLine(data[i].filename, data[i].link, data[i].filesize, data[i].uploaded_by, data[i].upload_time, data[i].dimensions, data[i].thumbnail,data[i].fileid, data[i].needs_override, data[i].textstatus, 0);
                    }
                },
                error: function(data, textStatus, jqXHR){
                    console.log(data);
                    console.log(textStatus);
                }
            });
        });

        function acceptFiles(fileArray, fileType){
            for(var i=0; i < fileArray.length; i++){
                var fileHasErrors = false;
                //Create a new line in the files table for this upload
                $("#fileuploadstable").append('<tr id="upload'+controlVar+'row">'+
                '<td>'+fileArray[i].name+'</td>'+
                '<td>'+(fileArray[i].size/1048576).toPrecision(2)+'MB</td>'+
                '<td colspan="4" id="upload'+controlVar+'statusBlock"></td>'+
                '</tr>');

                if(fileArray[i].type != "application/pdf"){
                    $("#upload"+controlVar+"statusBlock").html("The selected file is not a PDF");
                    fileHasErrors = true;
                }
                //We have a pdf!
                if(fileArray[i].size > 150000000){
                    //Greater than 150mb
                    $("#upload"+controlVar+"statusBlock").html("The selected file is greater than the 100mb limit");
                    fileHasErrors = true;
                }
                if(!fileHasErrors) {
                    //File is validated, add it to the file upload array and add a progress bar
                    $("#upload"+controlVar+"statusBlock").append('<div class="progress">'+
                    '<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="upload'+controlVar+'progress">'+
                    '</div>'+
                    '</div>');
                    var fileObj = {controlid: controlVar, filename: fileArray[i].name, file: fileArray[i], fileType: fileType};
                    filesArray.push(fileObj);
                }
                controlVar = controlVar + 1;
            }
            processUploads();
        }

        //When we process uploads we have to take the upload array and process it one by one
        //each time this function runs it will take 1 file from the fileuploads array and upload it
        function processUploads(){
            //Take the first item from the uploads array
            if(filesArray.length == 0){
                return false;
            }
            if(!processingUploads){
                processingUploads = true;
            }else{
                return false;
            }

            var fileObj = filesArray.shift();
            var fd = new FormData();
            fd.append("fileUpload",fileObj.file);
            fd.append("fileType",fileObj.fileType);
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
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                            updateFileUploadPercentage(percentComplete*100, fileObj.controlid);
                        }
                    }, false);
                    return xhr;
                },
                success: function(data, textStatus, jqXHR){
                    completeFileUpload(fileObj.controlid);
                    //Process the incoming data and make sure we don't have an array with error
                    if(data.error){
                        //If the array contains an error we have to show an error.
                        alert(data.error);
                        return false;
                    }
                    //Let's take the file object returned and do magic with it
                    addUploadedMaterialLine(data.filename, data.link, data.filesize, data.uploaded_by, data.upload_time, data.dimensions, data.thumbnail,data.fileid, data.needs_override, data.textstatus, fileObj.controlid);
                },
                error: function(data, textStatus, jqXHR){
                    console.log(data);
                    console.log(textStatus);
                },
                complete: function(jqXHR, textStatus){
                    processingUploads = false;
                    //Run this function again in 100ms
                    setTimeout(processUploads(), 100);
                }
            });
        }

        function updateFileUploadPercentage(percentage,barid){
            var percent = Math.ceil(percentage);
            $("#upload"+barid+"progress").attr("aria-valuenow", percent);
            $("#upload"+barid+"progress").css("width", percent+"%");
            //Only show progress > 10%
            if((percent > 10) && (percent < 100)) {
                $("#upload"+barid+"progress").html("Upload Progress: " + percent + "%");
            }else if(percent == 100){
                $("#upload"+barid+"progress").html("Processing upload...");
            }else{
                $("#upload"+barid+"progress").html("...");
            }
        }

        function completeFileUpload(barid){
            $("#upload"+barid+"progress").addClass("progress-bar-success");
            $("#upload"+barid+"progress").removeClass("active");
            $("#upload"+barid+"progress").removeClass("progress-bar-striped");
            $("#upload"+barid+"progress").html("Complete!");
        }

        function addUploadedMaterialLine(filename, link, filesize, uploaded_by, uploaded_timestamp, dimensions, thumbnail_url, file_id, show_override, textstatus, replace_id){
            replace_id = replace_id || 0;
            if(replace_id != 0){
                //Need to replace an object
                $("#upload"+replace_id+"row").replaceWith('<tr id="file'+file_id+'row">' +
                '<td><a href="'+link+'">'+filename+'</a></td>' + //filename
                '<td>'+filesize+'</td>' + // filesize
                '<td>'+dimensions+'</td>' + //dimensions
                '<td>'+uploaded_by+'</td>' + //uploader
                '<td>'+uploaded_timestamp+'</td>' + //time of upload
                '<td id="file'+file_id+'status"></td>' + //override or status
                '</tr>');
            }else{
                //Adding a new file object from other call
                //Need to replace an object
                $("#fileuploadstable").append('<tr id="file'+file_id+'row">' +
                '<td><a href="'+link+'">'+filename+'</a></td>' + //filename
                '<td>'+filesize+'</td>' + // filesize
                '<td>'+dimensions+'</td>' + //dimensions
                '<td>'+uploaded_by+'</td>' + //uploader
                '<td>'+uploaded_timestamp+'</td>' + //time of upload
                '<td id="file'+file_id+'status"></td>' + //override or status
                '</tr>');
            }
            if(show_override){
                $("#file"+file_id+"status").html('<div style="text-align: center">The file you uploaded does not meet our dimension specifications.'+
                        'If you are certain the file you uploaded has correct dimensions you can override this message and submit the file.'+
                        'If we find that the file dimensions are not correct we will reject your submission and you will have to resubmit the file. '+
                        '<br>Override? <br><button class="btn btn-danger" onclick="deleteSubmission('+file_id+')">No, Delete this submission</button> ' +
                        '<button class="btn btn-success" onclick="approveSubmission('+file_id+')">Yes, override my file</button></div>');
            }else{
                $("#file"+file_id+"status").html(textstatus);
            }
        }

        function deleteSubmission(fileid){
            overrideUpload(fileid, "Delete");
        }

        function approveSubmission(fileid){
            overrideUpload(fileid,"Approve");
        }

        function overrideUpload(fileid, action){

            var fd = new FormData();
            fd.append("fileid",fileid);
            fd.append("action",action);

            $.ajax({
                url: '{{ URL::route('project.printSubmission.override',$class->id) }}',
                type: 'POST',
                data: fd,
                cache: false,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(data){
                    //check for success data
                    console.log(data);
                    if(data.success == "true"){
                        //Action has been completed, run different code based on action
                        if(data.action == "Delete"){
                            removeFile(fileid);
                        }else if(data.action == "Approve"){
                            updateStatus(fileid,data.newstatus);
                        }
                    }
                    else if(data.error){
                        console.log("Your request could not be processed at this time");
                    }
                },
                error: function(data, textStatus){
                    console.log(data);
                    console.log(textStatus);
                }
            });
        }

        function updateStatus(fileid, newstatus){
            $("#file"+fileid+"status").html(newstatus);
        }

        function removeFile(fileid){
            $("#file"+fileid+"row").fadeOut(1000);
        }
    </script>
@stop
