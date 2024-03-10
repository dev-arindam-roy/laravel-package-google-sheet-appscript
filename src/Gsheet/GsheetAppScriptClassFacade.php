<?php
  
namespace Arindam\GsheetAppScript\Gsheet;
use Illuminate\Support\Facades\Facade;
  
class GsheetAppScriptClassFacade extends Facade
{
    protected static function getFacadeAccessor() 
    { 
        return 'gsheetappscriptclass'; 
    }
}