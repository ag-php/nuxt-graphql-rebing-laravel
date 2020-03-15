<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\DB;
use GraphQL\Type\Definition\ResolveInfo;
use Rebing\GraphQL\Support\Facades\GraphQL;
use App\GraphQL\Support\AuthenticatedQuery;
use App\Services\MultiTenancyService;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Worksheet;
use PHPExcel_Cell_DataValidation;

class ReportController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    //$this->middleware(['auth', 'verified']);
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function getInvestorReport(Request $request)
  {
    $user = auth('api')->user();

    (new MultiTenancyService($user))->selectDefaultOrganization();

    $rollup_query = <<<SQL
    SELECT * from `PL_rollups`
SQL;
    $version_query = <<<SQL
    Select
    `versions`.`FCSTDesc`,
    `versions`.`FCSTVersion`
    From `versions`
    Order by `versions`.`FCSTVersion` desc
SQL;

    $dbName = (new MultiTenancyService($user))->getDefaultOrganizationName();

    $result = DB::connection('tenant')->select($rollup_query);
    $result_versions =  DB::connection('tenant')->select($version_query);
    $objPHPExcel = new PHPExcel();
    // Set the active Excel worksheet to sheet 0
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('IS_Data');
    // Initialise the Excel row number
    $rowCount = 1;
    //start of printing column names as names of MySQL fields
    $column = 'A';
    $attributes = array_keys(get_object_vars($result[0]));
    $count1 = count($attributes);
    for ($i = 0; $i < $count1; $i++) {
      $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $attributes[$i]);
      $column++;
    }
    //end of adding column names
    //start while loop to get data
    $rowCount = 2;
    foreach ($result as $row) {
      $column = 'A';
      $row_result = get_object_vars($row);
      for ($j = 0; $j < $count1; $j++) {
        if (!isset($row_result[$attributes[$j]]))
          $value = NULL;
        elseif ($row_result[$attributes[$j]] != "")
          $value = strip_tags($row_result[$attributes[$j]]);
        else
          $value = "";
        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $value);
        $column++;
      }
      $rowCount++;
    }
    //create versions 2nd table

    $objPHPExcel->createSheet(1);
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('Versions');
    // Initialise the Excel row number
    $attributes = array_keys(get_object_vars($result_versions[0]));
    $count2 = count($attributes);
    $rowCount = 1;
    //start of printing column names as names of MySQL fields
    $column = 'A';
    for ($i = 0; $i < $count2; $i++) {
      $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $attributes[$i]);
      $column++;
    }
    //end of adding column names
    //start while loop to get data
    $rowCount = 2;
    foreach ($result_versions as $row) {
      $column = 'A';
      $row_result = get_object_vars($row);
      for ($j = 0; $j < $count2; $j++) {
        if (!isset($row_result[$attributes[$j]]))
          $value = NULL;
        elseif ($row_result[$attributes[$j]] != "")
          $value = strip_tags($row_result[$attributes[$j]]);
        else
          $value = "";
        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $value);
        $column++;
      }
      $rowCount++;
    }

    $file1 = storage_path("report_template/".$dbName."/Investor Report.xlsx");

    $outputFile = "Investor Report.xlsx";

    // Files are loaded to PHPExcel using the IOFactory load() method
    $objPHPExcel1 = PHPExcel_IOFactory::load($file1);

    $objPHPExcel1->removeSheetByIndex(3);
    $objPHPExcel1->removeSheetByIndex(3);

    // Copy worksheets from $objPHPExcel to $objPHPExcel1
    foreach ($objPHPExcel->getAllSheets() as $sheet) {
      $objPHPExcel1->addExternalSheet($sheet);
    }

    $objPHPExcel1->getSheetByName('IS_Data')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $objPHPExcel1->getSheetByName('IS_Reformat')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $objPHPExcel1->getSheetByName('Versions')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $objPHPExcel1->getSheetByName('Stores')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    //Set data validation for scenario selection
    $objPHPExcel1->setActiveSheetIndex(0);

    $objValidation = $objPHPExcel1->getActiveSheet()->getCell('F4')->getDataValidation();
    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
    $objValidation->setAllowBlank(false);
    $objValidation->setShowInputMessage(true);
    $objValidation->setShowErrorMessage(true);
    $objValidation->setShowDropDown(true);
    $objValidation->setErrorTitle('Input error');
    $objValidation->setError('Value is not in list.');
    $objValidation->setPromptTitle('Pick from list');
    $objValidation->setPrompt('Please pick a value from the drop-down list.');
    $objValidation->setFormula1('Stores!$A$1:$A$10');  // Make sure to put the list items between " and "  !!!

    $objValidation = $objPHPExcel1->getActiveSheet()->getCell('J4')->getDataValidation();
    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
    $objValidation->setAllowBlank(false);
    $objValidation->setShowInputMessage(true);
    $objValidation->setShowErrorMessage(true);
    $objValidation->setShowDropDown(true);
    $objValidation->setErrorTitle('Input error');
    $objValidation->setError('Value is not in list.');
    $objValidation->setPromptTitle('Pick from list');
    $objValidation->setPrompt('Please pick a value from the drop-down list.');
    $objValidation->setFormula1('Versions!$A$2:$A$10');  // Make sure to put the list items between " and "  !!!

    $objValidation = $objPHPExcel1->getActiveSheet()->getCell('O4')->getDataValidation();
    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
    $objValidation->setAllowBlank(false);
    $objValidation->setShowInputMessage(true);
    $objValidation->setShowErrorMessage(true);
    $objValidation->setShowDropDown(true);
    $objValidation->setErrorTitle('Input error');
    $objValidation->setError('Value is not in list.');
    $objValidation->setPromptTitle('Pick from list');
    $objValidation->setPrompt('Please pick a value from the drop-down list.');
    $objValidation->setFormula1('Versions!$A$2:$A$10');  // Make sure to put the list items between " and "  !!!

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel1, 'Excel2007');
    $objWriter->setPreCalculateFormulas(false);
    header("Access-Control-Allow-Origin: *");
    header("Content-Type:  application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=$outputFile");
    header("Cache-Control: max-age=0");
    ob_end_clean();
    $objWriter->save('php://output');
    exit();
  }


  public function getDetailReport(Request $request)
  {
    $user = auth('api')->user();
    (new MultiTenancyService($user))->selectDefaultOrganization();

    $rollup_query = <<<SQL
    SELECT * from `PL_Detail`
SQL;
    $version_query = <<<SQL
    Select
    `versions`.`FCSTDesc`,
    `versions`.`FCSTVersion`
    From `versions`
    Order by `versions`.`FCSTVersion` desc
SQL;

    $dbName = (new MultiTenancyService($user))->getDefaultOrganizationName();

    $result = DB::connection('tenant')->select($rollup_query);
    $result_versions =  DB::connection('tenant')->select($version_query);
    $objPHPExcel = new PHPExcel();
    // Set the active Excel worksheet to sheet 0
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->setTitle('IS_Data');
    // Initialise the Excel row number
    $rowCount = 1;
    //start of printing column names as names of MySQL fields
    $column = 'A';
    $attributes = array_keys(get_object_vars($result[0]));
    $count1 = count($attributes);
    for ($i = 0; $i < $count1; $i++) {
      $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $attributes[$i]);
      $column++;
    }
    //end of adding column names
    //start while loop to get data
    $rowCount = 2;
    foreach ($result as $row) {
      $column = 'A';
      $row_result = get_object_vars($row);
      for ($j = 0; $j < $count1; $j++) {
        if (!isset($row_result[$attributes[$j]]))
          $value = NULL;
        elseif ($row_result[$attributes[$j]] != "")
          $value = strip_tags($row_result[$attributes[$j]]);
        else
          $value = "";
        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $value);
        $column++;
      }
      $rowCount++;
    }
    //create versions 2nd table

    $objPHPExcel->createSheet(1);
    $objPHPExcel->setActiveSheetIndex(1);
    $objPHPExcel->getActiveSheet()->setTitle('Versions');
    // Initialise the Excel row number
    $attributes = array_keys(get_object_vars($result_versions[0]));
    $count2 = count($attributes);
    $rowCount = 1;
    //start of printing column names as names of MySQL fields
    $column = 'A';
    for ($i = 0; $i < $count2; $i++) {
      $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $attributes[$i]);
      $column++;
    }
    //end of adding column names
    //start while loop to get data
    $rowCount = 2;
    foreach ($result_versions as $row) {
      $column = 'A';
      $row_result = get_object_vars($row);
      for ($j = 0; $j < $count2; $j++) {
        if (!isset($row_result[$attributes[$j]]))
          $value = NULL;
        elseif ($row_result[$attributes[$j]] != "")
          $value = strip_tags($row_result[$attributes[$j]]);
        else
          $value = "";
        $objPHPExcel->getActiveSheet()->setCellValue($column . $rowCount, $value);
        $column++;
      }
      $rowCount++;
    }

    $file1 = storage_path("report_template/".$dbName."/Detail Report.xlsx");

    $outputFile = "Detail Report.xlsx";

    // Files are loaded to PHPExcel using the IOFactory load() method
    $objPHPExcel1 = PHPExcel_IOFactory::load($file1);

    $objPHPExcel1->removeSheetByIndex(3);
    $objPHPExcel1->removeSheetByIndex(3);

    // Copy worksheets from $objPHPExcel to $objPHPExcel1
    foreach ($objPHPExcel->getAllSheets() as $sheet) {
      $objPHPExcel1->addExternalSheet($sheet);
    }

    $objPHPExcel1->getSheetByName('IS_Data')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $objPHPExcel1->getSheetByName('IS_Reformat')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $objPHPExcel1->getSheetByName('Versions')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    $objPHPExcel1->getSheetByName('Ref')
      ->setSheetState(PHPExcel_Worksheet::SHEETSTATE_HIDDEN);

    //Set data validation for scenario selection
    $objPHPExcel1->setActiveSheetIndex(0);

    $objValidation = $objPHPExcel1->getActiveSheet()->getCell('F3')->getDataValidation();
    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
    $objValidation->setAllowBlank(false);
    $objValidation->setShowInputMessage(true);
    $objValidation->setShowErrorMessage(true);
    $objValidation->setShowDropDown(true);
    $objValidation->setErrorTitle('Input error');
    $objValidation->setError('Value is not in list.');
    $objValidation->setPromptTitle('Pick from list');
    $objValidation->setPrompt('Please pick a value from the drop-down list.');
    $objValidation->setFormula1('Ref!$B$2:$B$4');  // Make sure to put the list items between " and "  !!!

    $objValidation = $objPHPExcel1->getActiveSheet()->getCell('J4')->getDataValidation();
    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
    $objValidation->setAllowBlank(false);
    $objValidation->setShowInputMessage(true);
    $objValidation->setShowErrorMessage(true);
    $objValidation->setShowDropDown(true);
    $objValidation->setErrorTitle('Input error');
    $objValidation->setError('Value is not in list.');
    $objValidation->setPromptTitle('Pick from list');
    $objValidation->setPrompt('Please pick a value from the drop-down list.');
    $objValidation->setFormula1('Versions!$A$2:$A$6');  // Make sure to put the list items between " and "  !!!

    $objValidation = $objPHPExcel1->getActiveSheet()->getCell('J3')->getDataValidation();
    $objValidation->setType(PHPExcel_Cell_DataValidation::TYPE_LIST);
    $objValidation->setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION);
    $objValidation->setAllowBlank(false);
    $objValidation->setShowInputMessage(true);
    $objValidation->setShowErrorMessage(true);
    $objValidation->setShowDropDown(true);
    $objValidation->setErrorTitle('Input error');
    $objValidation->setError('Value is not in list.');
    $objValidation->setPromptTitle('Pick from list');
    $objValidation->setPrompt('Please pick a value from the drop-down list.');
    $objValidation->setFormula1('Versions!$A$2:$A$6');  // Make sure to put the list items between " and "  !!!

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel1, 'Excel2007');
    $objWriter->setPreCalculateFormulas(false);
    header("Access-Control-Allow-Origin: *");
    header("Content-Type:  application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header("Content-Disposition: attachment; filename=$outputFile");
    header("Cache-Control: max-age=0");
    ob_end_clean();
    $objWriter->save('php://output');
    exit();
  }


}
