(function($, Dropzone) {
    "use strict";

    $(document).ready(function() {
        const chunks        = base_url + upload + "/chunks";
        const url           = base_url + upload + "/new";
        const video_route   = 'v/';
        const block         = $('#uploader-block > .uploaderSection');
    
        /* File Uploading Event Handlers. */
        function dragOverBlock(e)  { block.addClass('onDrag');  } // When User Drags over The Upload Block
        function dragLeaveBlock(e) { block.removeClass('onDrag');  } // When user Drags out of the Upload Block
    
        function onFileAdd(file)   { $(file.previewElement).removeClass('d-none'); } // When a New File is Added
    
        // When an error is emitted by a file.
        function onFileError(file, message = null) {
            const preview = $(file.previewElement);
            const anchor = preview.find('.success-link');
            const progress = preview.find('.upload-progress');

            anchor.html(message ? message : lang_file_not_allowed);
            anchor.removeClass('d-none');
            progress.addClass('d-none');
        }
    
        // When an upload is finished.
        function onUploadComplete(data, file) {
			console.log(data);
            if(data.type == "success") {
                const response = JSON.parse(file.xhr.response);
                if(response.type == 'success') {
                    const id = data.data.id;
                    const preview = $(file.previewElement);
                    
                    const anchor    = preview.find('.success-link');
                    const progress  = preview.find('.upload-progress');
                    const img       = preview.find('.uploaderImageIcon');
                    const loader    = preview.find('.previewLoader');
    
                    anchor.attr('href', base_url + video_route + id);
                    anchor.html(base_url + video_route + id);
					if(data.s3 == true){
						img.attr('src', data.thumbnail);
					}
					else{
						img.attr('src', base_url + 'i/' + id + '.jpeg');
					}

                    loader.addClass("d-none");
                    img.removeClass("d-none");

                    anchor.removeClass('d-none');
                    progress.addClass('d-none');
                } else 
                    onFileError(file, response.errors);
            } 
        }
    
        // Dropzone Preview Template
        let previewNode = document.querySelector("#droptemplate"); previewNode.id = "";
        let previewTemplate = previewNode.parentNode.innerHTML; previewNode.parentNode.removeChild(previewNode);
        
        // Create a File Uploading Dropzone.
        const dropzone = new Dropzone(
            'div#uploader-block', 
            { 
                url:                    chunks,
                maxFilesize:            fileSize, // megabytes
                method:                 'post',
                paramName:              'uploads',
                previewTemplate:        previewTemplate,
                previewsContainer:      "#upload-previews",
                clickable:              "div#upload-clickable",
                acceptedFiles:          mime_typ,

                timeout:                180000,//
                chunking:               true,
                forceChunking:          true,
                chunkSize:              parseInt(chunkSize),// bytes
                parallelChunkUploads:   false,
                retryChunks:            true,
                retryChunksLimit:       3,

                // parallelUploads: 1,
                chunksUploaded: function(file, done) {
					
                    // All chunks have been uploaded. Perform any other actions
                    let currentFile = file;
                    var dataURI = '';
					
					var fileReader = new FileReader();
					fileReader.onload = function() {
						var blob = new Blob([fileReader.result], {type: file.type});
						var url = URL.createObjectURL(blob);
						var video = document.createElement('video');

						var canvas = document.createElement('canvas');
						var context = canvas.getContext('2d')
						video.addEventListener('loadeddata', function() {
							video.pause();
							canvas.width = 350;
                            canvas.height = 350 * (video.videoHeight / video.videoWidth);
                            context.drawImage(video, 0, 0, canvas.width, canvas.height);
							dataURI = canvas.toDataURL('image/jpeg');
							var img = document.createElement("img");
							img.src = dataURI;
							uploadimage(dataURI);
						}, false);

						video.preload = 'metadata';
						video.muted = true;
						video.playsInline = true;
						video.setAttribute('crossOrigin', 'anonymous');
						video.src = url;
						video.play();
					}
					fileReader.readAsArrayBuffer(file);
                    ////
                    // This calls server-side code to merge all chunks for the currentFile
                    function uploadimage(image){
                        $.ajax({
                            url: url+"/" + currentFile.upload.uuid + "/" + currentFile.upload.totalChunkCount + "/" + currentFile.name.substr( (currentFile.name.lastIndexOf('.') +1) ),
                            type: "POST",
                            data : {"imgCode" : image},
                            success: function (data) {
                                onUploadComplete(JSON.parse(data), file);
                                done();
                            },
                            error: function (msg) {
                                currentFile.accepted = false;
                                dropzone._errorProcessing([currentFile], msg.responseText);
                            }
                        });
                    }
                },

            }
        );
        // Register Events.
        dropzone.on('dragover',  dragOverBlock    );
        dropzone.on('dragleave', dragLeaveBlock   );
        dropzone.on('addedfile', onFileAdd        );
        dropzone.on('error',     onFileError      );
    })
})(jQuery, Dropzone);