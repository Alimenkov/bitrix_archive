<?

use Bitrix\Main\ModuleManager,
	Bitrix\Main\Application,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\EventManager,
	Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

Class My_Module extends \CModule
{

	public $MODULE_ID;
	public $COMPONENT_PATH_ID = 'mymodule';
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $errors;

	public function __construct()
	{
		$this->MODULE_ID = str_replace('_', '.', toLower(__CLASS__));

		$this->MODULE_VERSION = '0.0.1';

		$this->MODULE_VERSION_DATE = '27.08.2018';

		$this->MODULE_NAME = Loc::getMessage('MM_MODULE_NAME');

		$this->MODULE_DESCRIPTION = Loc::getMessage('MM_MODULE_DESCR');

		$this->PARTNER_NAME = '';

		$this->PARTNER_URI = '';
	}

	public function DoInstall()
	{
		$this->InstallDB();

		$this->InstallEvents();

		$this->InstallFiles();

		ModuleManager::RegisterModule($this->MODULE_ID);

		$GLOBALS['APPLICATION']->IncludeAdminFile(
			Loc::getMessage('MM_INSTALL_TITLE').' "'.Loc::getMessage('MM_MODULE_NAME').'"',
			__DIR__.'/step.php'
		);

		return true;
	}

	public function DoUninstall()
	{
		$this->UnInstallDB();

		$this->UnInstallEvents();

		$this->UnInstallFiles();

		ModuleManager::unRegisterModule($this->MODULE_ID);

		$GLOBALS['APPLICATION']->IncludeAdminFile(
			Loc::getMessage('MM_UNINSTALL_TITLE').' "'.Loc::getMessage('MM_MODULE_NAME').'"',
			__DIR__.'/unstep.php'
		);

		return true;
	}

	public function InstallDB()
	{
		global $DB;

		$this->errors = false;

		$this->errors = $DB->RunSQLBatch(__DIR__ . '/db/install.sql');

		if (empty($this->errors))
		{
			return true;
		}
		else
			return $this->errors;
	}

	public function UnInstallDB()
	{
		global $DB;

		$this->errors = false;

		$this->errors = $DB->RunSQLBatch(__DIR__ . '/db/uninstall.sql');

		if (empty($this->errors))
		{
			return true;
		}
		else
			return $this->errors;
	}

	public function InstallEvents()
	{
		EventManager::getInstance()->registerEventHandler(
			'main',
			'OnBeforeEndBufferContent',
			$this->MODULE_ID,
			'My\Module\ExampleEvent',
			'exampleEventMethod'
		);


		return true;
	}

	public function UnInstallEvents()
	{
		EventManager::getInstance()->unRegisterEventHandler(
			'main',
			'OnBeforeEndBufferContent',
			$this->MODULE_ID,
			'My\Module\ExampleEvent',
			'exampleEventMethod'
		);

		return true;
	}

	public function InstallFiles()
	{

		$boolDir = Directory::isDirectoryExists(
			Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/'
		);

		if (!empty($boolDir))
		{
			$this->copyOnlyComponents();
		}
		else
		{
			CopyDirFiles(
				__DIR__ . '/components/',
				Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/',
				true,
				true
			);
		}


		return true;
	}

	public function UnInstallFiles()
	{
		$this->deleteOnlyComponents();

		$this->deleteComponentPath();

		return true;
	}

	public function copyOnlyComponents()
	{
		$arrDirectory = array_diff(scandir(__DIR__ . '/components/'), array('..', '.'));

		if (!empty($arrDirectory))
		{
			foreach ($arrDirectory as $strDirectory)
			{

				$objNewDirectory = Directory::createDirectory(Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/' . $strDirectory);

				CopyDirFiles(
					__DIR__ . '/components/' . $strDirectory . '/',
					$objNewDirectory->getPath(),
					true,
					true
				);
			}
		}
	}

	public function deleteOnlyComponents()
	{
		$arrDirectory = array_diff(scandir(__DIR__ . '/components/'), array('..', '.'));

		if (!empty($arrDirectory))
		{
			foreach ($arrDirectory as $strDirectory)
			{
				$boolDir = Directory::isDirectoryExists(
					Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/' . $strDirectory
				);

				if (!empty($boolDir))
				{
					Directory::deleteDirectory(
						Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/' . $strDirectory
					);
				}
			}
		}
	}

	public function deleteComponentPath()
	{
		$boolDir = Directory::isDirectoryExists(
			Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/'
		);

		if (!empty($boolDir))
		{
			$objDirectory = new Bitrix\Main\IO\Directory(Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/');

			$arrChildren = $objDirectory->getChildren();

			if (empty($arrChildren))
			{
				Directory::deleteDirectory(
					Application::getDocumentRoot() . '/local/components/' . $this->COMPONENT_PATH_ID . '/'
				);
			}
		}
	}
}