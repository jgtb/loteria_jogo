/**
 * Override the default yii confirm dialog. This function is 
 * called by yii when a confirmation is requested.
 *
 * @param string message the message to display
 * @param string ok callback triggered when confirmation is true
 * @param string cancelCallback callback triggered when cancelled
 */
yii.confirm = function (message, okCallback, cancelCallback) {
    swal({
        title: message,
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#8CD4F5",
        confirmButtonText: "OK",
        cancelButtonText: "Cancel",
        allowOutsideClick: true,
        closeOnConfirm: true,
        closeOnCancel: true
    }, okCallback);
};