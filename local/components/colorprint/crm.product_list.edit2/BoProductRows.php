<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;
use Bitrix\Main\Type\DateTime;
use \Bitrix\Main\Context\Culture;

class BoProductRows
{
	const TABLE_NAME = "bo_product_rows";

	public $LAST_ERROR = "";

	public function updateRow($bxRowId, $designPrice)
	{
		if (intval($bxRowId) > 0 && floatval($designPrice) >= 0)
		{
			$bxRowId = intval($bxRowId);
			$designPrice = floatval($designPrice);
		}
		else
		{
			$this->LAST_ERROR = "Invalid argument exception";
			return false;
		}
		$arFields = array(
			"UF_BX_ROW_ID" => $bxRowId,
			"UF_DESIGN_PRICE" => $designPrice
		);
		if ($arRow = $this->getRow($bxRowId))
		{
			return $this->_updateRow($arRow["ID"], $arFields);
		}
		else
		{
			return $this->_addRow($arFields);
		}
	}

	public function getRow($bxRowId)
	{
		if (intval($bxRowId) > 0)
		{
			$bxRowId = intval($bxRowId);
		}
		else
		{
			$this->LAST_ERROR = "Invalid argument exception";
			return false;
		}

		$hlblock = HL\HighloadBlockTable::getList(array("filter" => array("TABLE_NAME" => self::TABLE_NAME)))->fetch();
		if ($hlblock)
		{
			$entity = HL\HighloadBlockTable::compileEntity($hlblock);
			$main_query = new Entity\Query($entity);

			$main_query->setSelect(array("*"));
			$main_query->setFilter(array(
				"UF_BX_ROW_ID" => $bxRowId
			));

			$result = $main_query->exec();

			$result = new CDBResult($result);
			if ($row = $result->Fetch())
			{
				return array(
					"ID" => $row["ID"],
					"BX_ROW_ID" => $row["UF_BX_ROW_ID"],
					"DESIGN_PRICE" => $row["UF_DESIGN_PRICE"]
				);
			}
		}
		return false;
	}

	protected function _updateRow($id, $arFields)
	{
		$hlblock = HL\HighloadBlockTable::getList(array("filter" => array("TABLE_NAME" => self::TABLE_NAME)))->fetch();
		if ($hlblock)
		{
			$entity = HL\HighloadBlockTable::compileEntity($hlblock);
			$dataClass = $entity->getDataClass();
			$result = $dataClass::update($id, $arFields);
			if(!$result->isSuccess())
			{
				$this->LAST_ERROR = $result->getErrorMessages();
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			$this->LAST_ERROR = "Unknown error";
			return false;
		}
	}

	protected function _addRow($arFields)
	{
		$hlblock = HL\HighloadBlockTable::getList(array("filter" => array("TABLE_NAME" => self::TABLE_NAME)))->fetch();
		if ($hlblock)
		{
			$entity = HL\HighloadBlockTable::compileEntity($hlblock);
			$dataClass = $entity->getDataClass();
			$result = $dataClass::add($arFields);
			if(!$result->isSuccess())
			{
				$this->LAST_ERROR = $result->getErrorMessages();
				return false;
			}
			else
			{
				return $result->getId();
			}
		}
		else
		{
			$this->LAST_ERROR = "Unknown error";
			return false;
		}
	}
}