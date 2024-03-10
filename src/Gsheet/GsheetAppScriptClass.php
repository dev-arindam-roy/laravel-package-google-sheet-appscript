<?php
  
namespace Arindam\GsheetAppScript\Gsheet;
use Arindam\GsheetAppScript\Http\Controllers\GsheetAppScriptController;
use Arindam\GsheetAppScript\Traits\GsheetAppScriptApis as GsheetApis;
  
class GsheetAppScriptClass 
{
    use GsheetApis;
    protected $configData;
    protected $apiUrl;

    public function __construct()
    {
        $this->configData = config('gsheet-appscript');
        $this->apiUrl = $this->configData['gsheet-api-url'];
    }

    public function hey()
    {
        return 'Hello Arindam, Gsheet APIs has been ready for you !!';
    }

    public function allRecords()
    {
        return self::getAllRecords($this->apiUrl);
    }

    public function clearSheet()
    {
        self::deleteAllRecords($this->apiUrl);
        return 'success';
    }

    public function addRow($data)
    {
        self::addNewRow($this->apiUrl, $data);
        return 'success';
    }

    public function setHeading($data)
    {
        return self::updateHeading($this->apiUrl, $data);
    }

    public function removeRow($data)
    {
        return self::deleteRow($this->apiUrl, $data);
    }

    public function editRow($data)
    {
        return self::updateRow($this->apiUrl, $data);
    }
}