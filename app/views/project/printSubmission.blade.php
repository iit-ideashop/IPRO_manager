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
                {{ Form::file("posterupload",array("class"=>"btn btn-primary","value"=>"Upload .pdf")) }}
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
                {{ Form::file("brochureupload",array("style"=>"display:none;","id"=>"brochureUpload")) }}

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
            <th> </th>
        </tr>

    </table>

@stop

@section('javascript_bottom')
    <script>
        $(document).on("ready",function(){
            $("#brochureUpload").on("change",function(event){
                //Verify the selected file is a .pdf
                file = event.target.files[0];
                if(file.type != "application/pdf"){
                    alert("Please upload a pdf file");
                    return false;
                }
                //We have a pdf!
                if(file.size > 100000000){
                    //Greater than 100mb
                    alert("Files must be smaller than 100mb. Please upload a smaller file or contact ipro.iit.edu for help.");
                    return false;
                }
                //File is pdf and is 100mb or smaller.
                var fd = new FormData();
                fd.append("fileUpload",file);
                console.log(fd);
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



            });



        });

        function updateFileUploadPercentage(percentage){
            var percent = Math.ceil(percentage);
            $("#fileUploadPercentage").attr("aria-valuenow", percent);
            $("#fileUploadPercentage").css("width", percent+"%");
            //Only show progress > 10%
            if(percent > 10) {
                $("#fileUploadPercentage").html("Upload Progress: " + percent + "%");
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
