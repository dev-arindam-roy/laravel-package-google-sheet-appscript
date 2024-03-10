<?php

namespace Arindam\GsheetAppScript\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Arindam\GsheetAppScript\Traits\GsheetAppScriptApis as GsheetApis;

class GsheetAppScriptController extends Controller
{
    use GsheetApis;

    protected $configData;
    protected $apiUrl;

    public function __construct()
    {
        $this->configData = config('gsheet-appscript');
        $this->apiUrl = $this->configData['gsheet-api-url'];
    }

    public function index(Request $request)
    {
        $dataBag = [];

        Session::put('isGsheetAuthEnabled', 'NO');
        if ($this->configData['authentication']['is_enabled']) {
            Session::put('isGsheetAuthEnabled', 'YES');
        }

        $dataBag['config_data'] = $this->configData;
        $dataBag['all_records'] = self::getAllRecords($this->apiUrl);
        return view('gsheet-appscript::index', $dataBag);
    }

    public function deleteAll(Request $request)
    {
        self::deleteAllRecords($this->apiUrl);
        return redirect()->back();
    }

    public function saveRow(Request $request)
    {
        $data = array();
        $requestData = $request->all();
        $data = $requestData['sheet_row_content'];
        if (isset($requestData['edit_heading']) && !empty($requestData['edit_heading'])) {
            self::updateHeading($this->apiUrl, $data);
            return redirect()->back()->with('onex_msg', 'Heading Updated Successfully');
        }
        if (isset($requestData['edit_row']) && !empty($requestData['edit_row']) && !empty($requestData['id'])) {
            $data['id'] = $requestData['id'];
            self::updateRow($this->apiUrl, $data);
            return redirect()->back()->with('onex_msg', 'Row Updated Successfully');
        }
        self::addNewRow($this->apiUrl, $data);
        return redirect()->back()->with('onex_msg', 'New Row Added Successfully');
    }

    public function removeRow(Request $request)
    {
        $data = array();
        $requestData = $request->all();
        $data['id'] = $requestData['id'];
        self::deleteRow($this->apiUrl, $data);
        return redirect()->back()->with('onex_msg', 'Row Deleted Successfully');
    }

    public function accessLogin(Request $request)
    {
        if ($request->input('sheet_access_username') == $this->configData['authentication']['login_id'] && $request->input('sheet_access_password') == $this->configData['authentication']['password']) {
            Session::put('gsheetAccess', 'DONE');
            return redirect()->back()->with('onex_msg', 'Access Granted!');
        }
        return redirect()->back()->with('onex_err_msg', 'Access Denied!');
    }

    public function accessOff(Request $request)
    {
        Session::put('gsheetAccess', null);
        Session::forget('gsheetAccess');
        return redirect()->back();
    }
}
