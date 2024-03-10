<script type="text/javascript">
$(function() {	
  $('#onexdt').DataTable({
    "columnDefs": [
      { "orderable": false, "targets": [$("#onexdt").find("thead tr th").length - 1] }
    ]
  });
});

const apiUrl = "{{ $config_data['gsheet-api-url'] }}";

let _txtbNo = 0;
const replicateTextBox = (_txtbNo, _tdText = '', _deleteDisabled = 0, _isHeading = 0) => {
    return `<div class="row mt-2" id="sheetCol${_txtbNo + 1}">
        <div class="col-sm-10">
            <div class="form-group">
                <label class="onex-frm-lb">${(_isHeading == 0) ? 'Enter Content Col::' : 'Heading::'}${_txtbNo + 1} <span>${(_tdText != '' && _isHeading == 0) ? _tdText : ''}</span></label>
                <input type="text" name="sheet_row_content[${_txtbNo}]" id="sheetRowContent${_txtbNo}" class="form-control row-content" placeholder="Content..." required="required" value="${(_isHeading == 1) ? _tdText : ''}"/>
            </div>
        </div>
        <div class="col-sm-2">
            ${(_deleteDisabled == 0) ? '<a href="javascript:void(0);" class="sheet-col-remove-btn"><i class="fas fa-trash-alt mt-32 text-danger"></i></a>' : ''}
        </div>
    </div>`;
}
$('#addNewBtn').on('click', function() {
    _txtbNo = 0;
    $('#sheetRowContainer').html('');
    $('#onexdt thead tr th.sheet-header').each(function() {
        $('#sheetRowContainer').append(replicateTextBox(_txtbNo, $(this).text(), 1));
        addValidation(_txtbNo);
        _txtbNo++;
    });
    if ($('#onexdt thead tr th.sheet-header').length == 0) {
        $('#sheetRowContainer').append(replicateTextBox(_txtbNo));
        addValidation(_txtbNo);
        _txtbNo++;
    }
    $('#addDataModal').find('.modal-title').html('Add New Sheet Row');
    $('#addDataModal').modal('show');
});
$('#editHeader').on('click', function() {
    _txtbNo = 0;
    $('#sheetRowContainer').html('');
    $('#onexdt thead tr th.sheet-header').each(function() {
        $('#sheetRowContainer').append(replicateTextBox(_txtbNo, $(this).text(), 1, 1));
        addValidation(_txtbNo);
        _txtbNo++;
    });
    $('#sheetRowContainer').append(`<input type="hidden" name="edit_heading" value="1"/>`);
    $('#addDataModal').find('.modal-title').html('Edit Header Row');
    $('#addDataModal').modal('show');
});
$('.add-more-sheet-row').on('click', function() {
    console.log("inn == ", $("#sheetRowModalBody").innerHeight());
    console.log("hh == ", $("#sheetRowModalBody").height());
    console.log("oo == ", $("#sheetRowModalBody").outerHeight());
    $('#sheetRowContainer').append(replicateTextBox(_txtbNo));
    addValidation(_txtbNo);
    $('#sheetRowModalBody').animate({
        scrollTop: $("#sheetRowModalBody").innerHeight() + 'px'
    }, 600);
    _txtbNo++;
});
$('body').on('click', '.sheet-col-remove-btn', function() {
    $(this).parents('.row').remove();
});
$('#saveDataBtn').on('click', function() {
    if ($('#onexFrm').valid()) {
        displayLoading();
        $('#onexFrm').submit();
    }
});
$('body').on('click', '.onex-delete-btn', function() {
    let _rowId = $(this).data('row-id');
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete this row",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes'
    }).then((result) => {
        if(result.isConfirmed) {
            displayLoading();
            if (_rowId != '' && _rowId != null && _rowId != undefined) {
                $('#frmDelRow' + _rowId).submit();
            }
        }
    });
});
$('body').on('click', '.onex-edit-btn', function() {
    let _rowId = $(this).data('row-id');
    let _trId = $(this).attr('id').split('-')[1];
    let _trRow = $('#tableRow-' + _trId).find('td.sheet-row');
    _txtbNo = 0;
    $('#sheetRowContainer').html('');
    $('#onexdt thead tr th.sheet-header').each(function(i) {
        let _elem = `<div class="row mt-2">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="onex-frm-lb">Heading::<span>${$(this).text()}</span></label>
                    <input type="text" name="sheet_row_content[${_txtbNo}]" id="sheetRowContent${_txtbNo}" class="form-control row-content" placeholder="Content..." required="required" value="${_trRow[i].innerHTML}"/>
                </div>
            </div>
        </div>`;
        $('#sheetRowContainer').append(_elem);
        addValidation(_txtbNo);
        _txtbNo++;
    });
    $('#sheetRowContainer').append(`<input type="hidden" name="edit_row" value="1"/>`);
    $('#sheetRowContainer').append(`<input type="hidden" name="id" value="${_rowId}"/>`);
    $('#addDataModal').find('.modal-title').html('Edit Row Content');
    $('#addDataModal').modal('show');
});
$('#reloadBtn').on('click', function() {
    displayLoading();
    window.location.reload();
});
$('#deleteAllBtn').on('click', function() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You want to delete all records",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Delete All'
    }).then((result) => {
        if(result.isConfirmed) {
            displayLoading();
            $('#onexDelAll').submit();
        }
    });
});

/** Validation */
$("#onexFrm").validate({
    errorClass: 'onex-error',
    errorElement: 'div',
    rules: {
    },
    messages: {
    }
});

const addValidation = (elemKey) => {
    $(`input[name="sheet_row_content[${elemKey}]"]`).rules('add', {
        required: true,
        messages: { 
            required: 'Please enter content'
        }
    });
}

/** SweetAlert2 loading */
const displayLoading = (title = 'Please Wait...', text = "System Processing Your Request", timer = 10000) => {
    Swal.fire({
        title: title,
        text: text,
        allowEscapeKey: false,
        allowOutsideClick: false,
        timer: timer,
        didOpen: () => {
            Swal.showLoading()
        }
    });
}

/** SweetAlert2 like toast */
const displayToast = (position = 'top-end', title = 'Its Done!') => {
    Swal.fire({
        position: position,
        icon: 'success',
        title: title,
        showConfirmButton: false,
        timer: 1000
    });
}

/** SweetAlert2 custom function */
const displayAlert = (icon = 'success', title = '', text = '', confirmButtonText = 'OK') => {
    Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonColor: '#0d6efd',
        confirmButtonText: confirmButtonText
    });
}

const checkAuth = "{{ Session::get('isGsheetAuthEnabled') }}";
const loginDone = "{{ Session::get('gsheetAccess') }}";
$(window).on('load', function() {
    if (checkAuth == "YES" && loginDone != "DONE") {
        $('#onexLoginModal').modal('show');
    }
});
$("#onexLoginFrm").validate({
    errorClass: 'onex-error',
    errorElement: 'div',
    rules: {
        "sheet_access_username": {
            required: true
        },
        "sheet_access_password": {
            required: true
        }
    },
    messages: {
        "sheet_access_username": {
            required: 'Please enter username'
        },
        "sheet_access_password": {
            required: 'Please enter password'
        }
    }
});
$('#userLoginBtn').on('click', function() {
    if ($('#onexLoginFrm').valid()) {
        displayLoading();
        $('#onexLoginFrm').submit();
    }
});
</script>

@if (Session::has('onex_msg') && !empty(Session::get('onex_msg')))
<script>
displayAlert('success', "{{ Session::get('onex_msg') }}");
</script>
@endif

@if (Session::has('onex_err_msg') && !empty(Session::get('onex_err_msg')))
<script>
displayAlert('error', "{{ Session::get('onex_err_msg') }}");
</script>
@endif