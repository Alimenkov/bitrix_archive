<?

namespace My\Module;

use My\Module\DataTable,
	My\Module\DataRefTable,
	Bitrix\Main;

class GetSomeData
{

	public static function getListFirst($arrOrder = array(), $arrFilter = array(), $arrSelect = array())
	{
		if (!is_array($arrOrder) || !is_array($arrFilter) || !is_array($arrSelect))
		{
			throw new Main\ArgumentTypeException('$arrOrder || $arrFilter || $arrSelect');
		}

		$arrResult = array();

		$objResult = DataTable::getList(
			array(

				'select' => $arrSelect,
				'filter' => $arrFilter,
				'order' => $arrOrder,
				'limit' => 10000,
			));

		while ($arrRow = $objResult->Fetch())
		{
			$arrResult[] = $arrRow;
		}


		return $arrResult;
	}

	/**
	 * @param array $arrOrder
	 * @param array $arrFilter
	 * @param array $arrSelect
	 * @return array
	 */

	public static function getIdSecond($intId = 0)
	{
		if (!empty($intId))
		{
			$intId = intval($intId);
		}

		if (empty($intId))
		{
			throw new Main\ArgumentNullException('ID');
		}

		$arrResult = array();

		$objResult = DataRefTable::getList(
			array(

				'select' => array('ID', 'NAME', 'REF_TITLE' => 'REFERER.TITLE'),
				'filter' => array('ID' => $intId),
				'order' => array(),
				'limit' => 1,
			));

		$arrResult = $objResult->Fetch();


		return $arrResult;
	}

	public static function expressionGet()
	{

		$arrResult = array('MAX_ID' => 0);

		$objResult = DataTable::getList(array(
			'select' => array(
				new Main\Entity\ExpressionField('MAX_ID', 'MAX(%s)', array('ID'))
			),
			'limit' => 1
		));

		$arrResult = $objResult->Fetch();

		return $arrResult['MAX_ID'];
	}

}