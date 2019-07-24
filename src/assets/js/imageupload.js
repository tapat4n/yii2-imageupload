$(document).ready(function() {  

    Dropzone.autoDiscover = false;
    Dropzone.imageUploadPreviewTemplate = '<div class="dz-preview dz-file-preview">\
        <div class="dz-image-upload">\
            <img class="data-dz-thumbnail" />\
        </div>\
        <div class="dz-details">\
            <div class="dz-button dz-button-crop">\
                <span class="btn fa fa-crop"></span>\
            </div>\
            <div class="dz-button dz-button-remove">\
                <span class="btn fa fa-trash-o"></span>\
            </div>\
        </div>\
        <div class="dz-progress">\
            <span class="dz-upload" data-dz-uploadprogress></span>\
        </div>\
        <div class="dz-error-message">\
            <span data-dz-errormessage></span>\
        </div>\
        <div class="dz-success-mark">\
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\
                <title>Check</title>\
                <defs></defs>\
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\
                    <path d="M23.5,31.8431458 L17.5852419,25.9283877 C16.0248253,24.3679711 13.4910294,24.366835 11.9289322,25.9289322 C10.3700136,27.4878508 10.3665912,30.0234455 11.9283877,31.5852419 L20.4147581,40.0716123 C20.5133999,40.1702541 20.6159315,40.2626649 20.7218615,40.3488435 C22.2835669,41.8725651 24.794234,41.8626202 26.3461564,40.3106978 L43.3106978,23.3461564 C44.8771021,21.7797521 44.8758057,19.2483887 43.3137085,17.6862915 C41.7547899,16.1273729 39.2176035,16.1255422 37.6538436,17.6893022 L23.5,31.8431458 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" stroke-opacity="0.198794158" stroke="#747474" fill-opacity="0.816519475" fill="#FFFFFF" sketch:type="MSShapeGroup"></path>\
                </g>\
            </svg>\
        </div>\
        <div class="dz-error-mark">\
            <svg width="54px" height="54px" viewBox="0 0 54 54" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns">\
                <title>Error</title>\
                <defs></defs>\
                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" sketch:type="MSPage">\
                    <g id="Check-+-Oval-2" sketch:type="MSLayerGroup" stroke="#747474" stroke-opacity="0.198794158" fill="#FFFFFF" fill-opacity="0.816519475">\
                        <path d="M32.6568542,29 L38.3106978,23.3461564 C39.8771021,21.7797521 39.8758057,19.2483887 38.3137085,17.6862915 C36.7547899,16.1273729 34.2176035,16.1255422 32.6538436,17.6893022 L27,23.3431458 L21.3461564,17.6893022 C19.7823965,16.1255422 17.2452101,16.1273729 15.6862915,17.6862915 C14.1241943,19.2483887 14.1228979,21.7797521 15.6893022,23.3461564 L21.3431458,29 L15.6893022,34.6538436 C14.1228979,36.2202479 14.1241943,38.7516113 15.6862915,40.3137085 C17.2452101,41.8726271 19.7823965,41.8744578 21.3461564,40.3106978 L27,34.6568542 L32.6538436,40.3106978 C34.2176035,41.8744578 36.7547899,41.8726271 38.3137085,40.3137085 C39.8758057,38.7516113 39.8771021,36.2202479 38.3106978,34.6538436 L32.6568542,29 Z M27,53 C41.3594035,53 53,41.3594035 53,27 C53,12.6405965 41.3594035,1 27,1 C12.6405965,1 1,12.6405965 1,27 C1,41.3594035 12.6405965,53 27,53 Z" id="Oval-2" sketch:type="MSShapeGroup"></path>\
                    </g>\
                </g>\
            </svg>\
        </div>\
    </div>';
});

function imageUploadInit(modelName, valueInputName, parametersInputName, cropSettings, crossOrigin) {
    var dzSelector = "div.dz-" + valueInputName;
    var dz = $(dzSelector);

    //image upload settings
    var form = dz.parents("form");
    var valueInput = form.find('input[name="' + modelName + '[' + valueInputName + ']"]');
    var parametersInput = form.find('input[name="' + modelName + '[' + parametersInputName + ']"]');
    var imageDownloadUrl = dz.attr('image-url') + '/';
    var imageDownloadPath = dz.attr('image-path');

    var cropImage = $('#' + valueInputName + '-upload-image');
    if (crossOrigin) {
        cropImage.attr('crossOrigin', crossOrigin);
    }
    var cropModal = $('#' + valueInputName + '-upload-modal');

    // dropzone
    dz.dropzone({
        paramName: "ImageFileModel[file]",
        acceptedFiles: ".jpeg,.jpg,.png,.gif",
        maxFilesize: 2.0,
        url: dz.attr('upload-action'),
        //withCredentials: false,
        addRemoveLinks : false,
        uploadMultiple: false,
        dictResponseError: 'Image download error',
        maxFiles: 1,        
        previewTemplate: Dropzone.imageUploadPreviewTemplate,
        createImageThumbnails: true,

        init: function() {
            this.on("sending", function(file, xhr, formData) {
                formData.append("_csrf", form.find('input[name="_csrf"]').val());
                formData.append("path", imageDownloadPath);
            });

            this.on("maxfilesexceeded", function(file) {
                this.removeAllFiles();
                this.addFile(file);
            });

            this.on("addedfile", function(file) {
                dz.find(".dz-button-crop").click(function(){
                    cropImage.cropper("enable", true);
                    cropModal.modal("show");
                    cropModal.find('#crop_apply_button').click(function(e){
                        setPreview();
                    });
                });
                dz.find(".dz-button-remove").click(function(){
                    dz.css('border', '2px dashed #888');
                    cropImage.cropper("destroy");
                    Dropzone.forElement(dzSelector).removeAllFiles();
                    valueInput.val('');
                    parametersInput.val('');
                    cropImage.trigger('imageChange');
                });
                dz.css('border', 'none'); 
            });

            this.on("success", function(file, responseText) {
                var fileData = JSON.parse(responseText);
                if(fileData.uploaded){
                    var fileName = fileData.name+'.'+fileData.extension;
                    valueInput.val(fileName);
                    parametersInput.val("{}"); 
                    cropperInit(cropSettings, valueInput, parametersInput, cropImage, dzSelector + ' .dz-image-upload');                    
                }
            });

            // show uploaded image
            if (valueInput.val()!=""){
                var file = {name: imageDownloadUrl + valueInput.val()};
                this.emit("addedfile", file);
                this.files.push(file);
                this.emit("thumbnail", file, file.name);
                this.emit("complete", file);
                cropperInit(cropSettings, valueInput, parametersInput, cropImage, dzSelector + ' .dz-image-upload');
            }
        }
    });

    function cropperInit(cropSettings, value, params, img, preview){ 
        if (value.val()!=''){
            var cropState =JSON.parse(params.val());
            img.attr('src', imageDownloadUrl + value.val());
            img.cropper({
                aspectRatio: cropSettings.aspectRatio,
                autoCropArea: 1,
                strict: true,
                crop: function(data) {},
                minContainerWidth: 600,
                minContainerHeight: 500,
                ready: function () {
                    img.cropper("setCropBoxData", cropState.cropbox);
                    img.cropper("setCanvasData", cropState.canvas);
                    img.cropper("setData", cropState.date);
                    setPreview();                    
                    img.cropper("disable",true);
                }
            });
        }
    }

    function setPreview(){
        var canvas = cropImage.cropper('getCroppedCanvas');
        var png = canvas.toDataURL("image/png");
        $(dzSelector + ' .dz-image-upload > img').attr({'src':png, 'style':'max-height: ' + cropSettings.height + 'px; height:auto; max-width: ' + cropSettings.width + 'px; width:auto;'});

        var cropData = {
            data:cropImage.cropper("getData"),
            canvas:cropImage.cropper("getCanvasData"),
            cropbox:cropImage.cropper("getCropBoxData")
        };
        parametersInput.val(JSON.stringify(cropData));
        cropImage.trigger('imageChange');
    }

    cropModal.on('hidden.bs.modal', function() {
        cropImage.cropper("disable", true);
    });
}
