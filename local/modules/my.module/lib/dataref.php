<?php

namespace My\Module;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * Class TableTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(45) mandatory
 * <li> REF int mandatory
 * </ul>
 *
 * @package Bitrix\Ref
 **/
class DataRefTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'my_ref_table';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('MM_TABLE_ENTITY_ID_FIELD'),
			),
			'NAME' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateName'),
				'title' => Loc::getMessage('MM_TABLE_ENTITY_NAME_FIELD'),
			),
			'REF' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('MM_TABLE_ENTITY_REF_FIELD'),
			),
			new Main\Entity\ReferenceField(
				'REFERER',
				'My\Module\DataTable',
				array('=this.REF' => 'ref.ID')
            )
		);
	}

	/**
	 * Returns validators for NAME field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return array(
			new Main\Entity\Validator\Length(null, 45),
		);
	}
}