//code for alert messages
function alertErrorMessage(message) {
    $.alert({
        title: "Invalid",
        content: message,
        icon: 'fas fa-frown',
        animation: 'scale',
        closeAnimation: 'scale',
        theme: 'supervan',
        autoClose: 'Close|3000',
        type: 'red',
        buttons: {
            Close: {
                btnClass: 'btn-red'
            }
        }
    });
}

function alertSuccessMessage(message) {
    $.alert({
        title: "Success",
        content: message,
        icon: 'fas fa-smile',
        animation: 'scale',
        closeAnimation: 'scale',
        theme: 'supervan',
        autoClose: 'Close|3000',
        type: 'green',
        buttons: {
            Close: {
                btnClass: 'btn-green'
            }
        }
    });
}
//code for alert messages

