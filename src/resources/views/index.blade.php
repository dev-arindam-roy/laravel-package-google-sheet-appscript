<!DOCTYPE html>
<html lang="en">
<head>
  <title>{{ env('APP_NAME') }} | Gsheet-Manager</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.16/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/placeholder-loading/dist/css/placeholder-loading.min.css">
  @include('gsheet-appscript::assets.style')
</head>
<body>

<div class="container mt-3">
  <h2>{{ $config_data['page_heading'] }}</h2>
  <div class="row mt-3">
    <div class="col-sm-9">
      <div class="btn-group" role="group">
        <button type="button" class="btn btn-primary" id="addNewBtn">@if(empty($all_records['data'])) Add Header @else Add New Row @endif</button>
        <button type="button" class="btn btn-warning @if(empty($all_records['data'])) disabled @endif" id="editHeader" @if(empty($all_records['data'])) disabled="disabled" @endif>Edit Header</button>
        <button type="button" class="btn btn-danger" id="deleteAllBtn">Delete All</button>
        <button type="button" class="btn btn-success" id="reloadBtn">Reload Data</button>
      </div>
    </div>
    <div class="col-sm-3" style="text-align: right;">
      @if (Session::has('isGsheetAuthEnabled') && Session::get('isGsheetAuthEnabled') == 'YES')
        <a href="{{ route('gsheet-appscript.accessOff') }}" class="logoff"><i class="fas fa-sign-out-alt fa-2x text-secondary"></i></a>
      @endif
    </div>
  </div>

  <form name="onex-delete-all" id="onexDelAll" action="{{ route('gsheet-appscript.deleteAll') }}" method="post">
    {{ csrf_field() }}
  </form>

  <div class="row mt-3">
    <div class="col-sm-12">
        <div class="table-responsive">
          @if(!empty($all_records['data']) && !empty($all_records['cols']))
          <table class="table table-sm table-bordered table-striped" id="onexdt">
            <thead>
              <tr>
                <th style="width: 120px;">SL</th>
                @for($i = 0; $i < $all_records['cols']; $i++)
                  <th class="sheet-header">{{ $all_records['data'][0][$i] }}</th>
                @endfor
                <th style="width: 120px;">Action</th>
              </tr>
            </thead>
            <tbody>
              @if(is_array($all_records['data']) && count($all_records['data']))
                @for($row = 1; $row < count($all_records['data']); $row++)
                  <tr id="tableRow-{{ $row }}">
                    <th>SheetRowNo-{{ $row + 1 }}</th>
                    @for($cols = 0; $cols < $all_records['cols']; $cols++)
                      <td class="sheet-row">{{ $all_records['data'][$row][$cols] }}</td>
                    @endfor
                    <td>
                      <button type="button" class="btn btn-sm btn-success onex-edit-btn" id="editBtn-{{ $row }}" data-row-id="{{ $row + 1 }}">Edit</button>
                      <button type="button" class="btn btn-sm btn-danger onex-delete-btn" id="deleteBtn-{{ $row }}" data-row-id="{{ $row + 1 }}">Delete</button>
                      <form name="frm-del-row" id="frmDelRow{{ $row + 1 }}" action="{{ route('gsheet-appscript.deleteRow') }}" method="post">
                        <input type="hidden" name="id" value="{{ $row + 1 }}"/>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                      </form>
                    </td>
                  </tr>
                @endfor
              @else
                <td colspan="{{ $all_records['cols'] + 2 }}">
                  <div class="alert alert-warning" role="alert">
                    <p><strong>Sorry!</strong> No Records Found! <br/> Please add rows / contents in sheet.</p>
                  </div>
                </td>
              @endif
            </tbody>
          </table>
          @else
            <div class="alert alert-warning" role="alert">
              <p><strong>Sorry!</strong> No Records Found! <br/> Please add rows / contents in sheet.</p>
            </div>
          @endif
        </div>
      </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addDataModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Row In Sheet</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="sheetRowModalBody">
        <form name="onex-frm" id="onexFrm" action="{{ route('gsheet-appscript.saveRow') }}" method="POST">
          {{ csrf_field() }}
          <div id="sheetRowContainer"></div>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-primary add-more-sheet-row"><i class="fas fa-plus"></i> Add More</button>
        <button type="button" class="btn btn-success" id="saveDataBtn"><i class="fas fa-save"></i> Save</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<!-- Modal -->
<div class="modal fade" id="onexLoginModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Login</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form name="onex-login-frm" id="onexLoginFrm" action="{{ route('gsheet-appscript.accessLogin') }}" method="POST">
          {{ csrf_field() }}
          <div class="form-group">
            <label class="onex-frm-lb">Username:</label>
            <input type="text" name="sheet_access_username" id="sheetAccessUsername" class="form-control" placeholder="Username" required="required"/>
          </div>
          <div class="form-group">
            <label class="onex-frm-lb">Password:</label>
            <input type="password" name="sheet_access_password" id="sheetAccessPassword" class="form-control" placeholder="Password" required="required"/>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="userLoginBtn"><i class="fas fa-sign-in-alt"></i> Login</button>
      </div>
    </div>
  </div>
</div>
<!-- End Modal -->

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.16/dist/sweetalert2.all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
@include('gsheet-appscript::assets.script')
</body>
</html>