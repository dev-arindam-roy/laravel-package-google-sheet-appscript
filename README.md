# LARAVEL PACKAGE - ACCESS GOOGLE SHEET

### A laravel package for access and manage any google sheet.

## Installation

> **No dependency on PHP version and LARAVEL version**

### STEP 1: Run the composer command:

```shell
composer require arindam/gsheet-appscript
```

### STEP 2: Laravel without auto-discovery:

If you don't use auto-discovery, add the ServiceProvider to the providers array in config/app.php

> In providers section
```php
Arindam\GsheetAppScript\GsheetAppScriptServiceProvider::class,
```

> In aliases section
```php
'GsheetAppScript' => Arindam\GsheetAppScript\Gsheet\GsheetAppScriptClassFacade::class,
```

### STEP 3: Publish the package config:

```php
php artisan vendor:publish --provider="Arindam\GsheetAppScript\GsheetAppScriptServiceProvider" --force
-OR-
php artisan vendor:publish --tag="gsheet-appscript:config"
```

## How to use?: It's Very Easy

> **FACADE HELPER FUNCTIONS**

```php
use GsheetAppScript;

GsheetAppScript::allRecords(); //get all records from google sheet

GsheetAppScript::addRow(['Text1', 'Text2', 'Text3' ...]); //add row in the google sheet

GsheetAppScript::setHeading(['Heading1', 'Heading2', 'Heading3' ...]); //set or edit heading in the google sheet

GsheetAppScript::editRow(['id' => 4, 'Text1', 'Text2', 'Text3' ...]); //edit data in the google sheet, just pass the row number as id with data

GsheetAppScript::removeRow(['id' => 4]); //remove row from google sheet, just pass the row number

GsheetAppScript::clearSheet(); //delete all records in google sheet
```


> **DIRECT USE BY ROUTE**

<dl>
  <dt>>> <code>Just install and run the below route </span></code></dt>
</dl>

```php
Ex: http://your-website/onex/gsheet

Ex: http://localhost:8000/onex/gsheet
```

![2024-03-10_220306](https://github.com/dev-arindam-roy/laravel-package-google-sheet-appscript/assets/24665327/185945de-0916-4809-997d-c3f0fa1972eb)

#### You can modify the configuration settings in - "config/gsheet-appscript.php":

```php
/** If you want to disable the route or this feature, then make it false */
'is_route_enabled' => true,
```

```php
/** If you want to change the route prefix */
'route_prefix' => 'onex',
```

```php
/** If you want to change the route name or path */
'route_name' => 'gsheet',
```

```php
/** If you want to change the page heading */
'page_heading' => 'Google Sheet',
```

```php
/** If you want to enable the securiry for access the google sheet information
 *  Then make it ('is_enabled') true and also you can set login-id and password through .env
 */
'authentication' => [
    'is_enabled' => env('GSHEET_APPSCRIPT_AUTH_ENABLED', false),
    'login_id' => env('GSHEET_APPSCRIPT_LOGIN_ID', 'onexadmin'),
    'password' => env('GSHEET_APPSCRIPT_LOGIN_PASSWORD', 'onexpassword')
]
```

![2024-03-10_220503](https://github.com/dev-arindam-roy/laravel-package-google-sheet-appscript/assets/24665327/f135b6ba-c83d-46a5-aaa1-c8fc7293b714)

## Google AppScript Code:

> **Just Copy & paste below code in your google sheet appscript section**

```javascript
/** All Get Request */
function doGet() {
  return ContentService.createTextOutput(getAllRows());
}

/** All Post Request */
function doPost(req) {
  let data = JSON.parse(req.postData.contents);
  if(data.actionkey == "SAVE") {
    return ContentService.createTextOutput(addSaveRow(data));
  }
  if(data.actionkey == "UPDATE") {
    return ContentService.createTextOutput(updateSaveRow(data));
  }
  if(data.actionkey == "DELETE") {
    return ContentService.createTextOutput(deleteRow(data));
  }
  if(data.actionkey == "VIEW") {
    return ContentService.createTextOutput(viewRow(data));
  }
  if(data.actionkey == "CLEAR") {
    return ContentService.createTextOutput(clearSheet());
  }
}

/** Add */
function addSaveRow(jsonObjData) {
  if (jsonObjData['actionkey']) {
    delete jsonObjData['actionkey'];
  }
  SpreadsheetApp.getActiveSheet().appendRow(Object.values(jsonObjData));
  return 'SUCCESS';
}

/** Edit */
function getRowId(textId) {
  let findData = SpreadsheetApp.getActiveSheet().createTextFinder(textId).matchEntireCell(true).findNext();
  if(findData) {
    return findData.getRow();
  }
  return 0;
}

/** Update */
function updateSaveRow(jsonObjData) {
  let sheetRowNo = jsonObjData.id;
  if (jsonObjData['actionkey']) {
    delete jsonObjData['actionkey'];
  }
  if (jsonObjData['id']) {
    delete jsonObjData['id'];
  }
  if(sheetRowNo) {
    Object.keys(jsonObjData).forEach(function(key, index) {
      SpreadsheetApp.getActiveSheet().getRange(sheetRowNo, index + 1).setValue(jsonObjData[key]);
    });
    return "SUCCESS";
  }
  return "ERROR";
}

/** View a Record */
function viewRow(jsonObjData) {
  let findData = SpreadsheetApp.getActiveSheet().createTextFinder(jsonObjData.id).matchEntireCell(true).findNext();
  if(findData) {
    return findData;
  }
  return 0;
}

/** Delete */
function deleteRow(jsonObjData) {
  if(jsonObjData.id) {
    SpreadsheetApp.getActiveSheet().deleteRow(jsonObjData.id);
    return "SUCCESS";
  } else {
    return "ERROR";
  }
}

/** Get All */
function getAllRows() {
  let obj = {};
  let data = SpreadsheetApp.getActiveSheet().getDataRange().getValues().filter(row => row.join(""));
  obj = {
    data: data,
    cols: SpreadsheetApp.getActiveSheet().getDataRange().getNumColumns()
  }
  return JSON.stringify(obj);
}

/** Clear All */
function clearSheet() {
  SpreadsheetApp.getActiveSheet().clear({contentsOnly: true});
  return "SUCCESS";
}
```


## license:
The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Post Issues: if found any
If have any issue please [write me](https://github.com/dev-arindam-roy/laravel-package-google-sheet-appscript/issues).
