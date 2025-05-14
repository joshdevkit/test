// shared.js

// Define buttons
const approveButton = $('<button>', {
    type: 'button',
    class: 'btn btn-primary',
    id: 'approve-selected-button',
    text: 'Select Items to Approve',
});

const cancelButton = $('<button>', {
    type: 'button',
    class: 'btn btn-danger d-none',
    id: 'cancel-selected-button',
    text: 'Deselect / Cancel',
});

const submitButton = $('<button>', {
    type: 'button',
    class: 'btn btn-success d-none',
    id: 'submit-selected-button',
    text: 'Submit Selected and Approved',
});

const receviedButton = $('<button>', {
    type: 'button',
    class: 'btn btn-success',
    id: 'submit-selected-button-approve',
    text: 'Mark selected as Received',
});

const submitButtonForReceicved = $('<button>', {
    type: 'button',
    class: 'btn btn-success d-none',
    id: 'received-button',
    text: 'Mark as Received',
});

const cancelSubmitForReceicved = $('<button>', {
    type: 'button',
    class: 'btn btn-danger d-none',
    id: 'cancel-received-button',
    text: 'Cancel / Deselect',
});

const proceedSubmitForReceived = $('<button>', {
    type: 'button',
    class: 'btn btn-success d-none',
    id: 'procedd-received-button',
    text: 'Submit',
})

const returnedAll = $('<button>', {
    type: 'button',
    class: 'btn btn-success d-none',
    id: 'returned-all-button',
    text: 'Mark all as Returned',
})

// Function to insert buttons after #cancel-select-items
function actionButtons() {
    $('#cancel-select-items').after(cancelButton);
    $('#cancel-select-items').after(submitButton);
    $('#cancel-select-items').after(approveButton);
    $('#cancel-select-items').after(receviedButton);
    $('#cancel-select-items').after(submitButtonForReceicved);
    $('#cancel-select-items').after(cancelSubmitForReceicved);
    $('#cancel-select-items').after(proceedSubmitForReceived);
    $('#cancel-select-items').after(returnedAll);
}
