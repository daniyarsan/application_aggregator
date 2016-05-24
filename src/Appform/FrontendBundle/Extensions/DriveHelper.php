<?php

namespace Appform\FrontendBundle\Extensions;

class DriveHelper
{

	protected $scope = array('https://www.googleapis.com/auth/drive');
	private $_service;
	public $folderId;
	private $appName;
	private $shareEmail;


	public function __construct($clientId, $serviceAccountName, $key, $appName, $shareEmail)
	{
		$client = new \Google_Client();
		$client->setClientId($clientId);

		$client->setAssertionCredentials(new \Google_Auth_AssertionCredentials(
				$serviceAccountName,
				$this->scope,
				file_get_contents($key))
		);
		$this->appName = $appName;
		$this->shareEmail = $shareEmail;
		$this->_service = new \Google_Service_Drive($client);
	}

	public function __get($name)
	{
		return $this->_service->$name;
	}

	public function createFile($name, $mime, $description, $content, $fileParent = null)
	{
		$file = new \Google_Service_Drive_DriveFile();
		$file->setName($name);
		$file->setDescription($description);
		$file->setMimeType($mime);

		$params = array(
				'data' => $content,
				'mimeType' => $mime,
		);
		if ($fileParent) {
			$params['uploadType'] = 'media';
			$file->setParents(array($fileParent));
		}

		$createdFile = $this->_service->files->create($file, $params);
		$this->setPermissions($createdFile[ 'id' ], $this->shareEmail);
		return $createdFile[ 'id' ];
	}

	public function createFileFromPath($path, $description, $fileParent = null)
	{
		$fi = new \finfo(FILEINFO_MIME);
		$mimeType = explode(';', $fi->buffer(file_get_contents($path)));
		$fileName = preg_replace('/.*\//', '', $path);

		return $this->createFile($fileName, $mimeType[ 0 ], $description, file_get_contents($path), $fileParent);
	}


	public function createFolder($name)
	{
		return $this->createFile($name, 'application/vnd.google-apps.folder', null, null);
	}

	public function setPermissions($fileId, $value, $role = 'writer', $type = 'user')
	{
		$perm = new \Google_Service_Drive_Permission();
		$perm->setEmailAddress($value);
		$perm->setType($type);
		$perm->setRole($role);

		$this->_service->permissions->create($fileId, $perm);
	}

	public function getFileIdByName($name)
	{
		$files = $this->_service->files->listFiles();

		foreach ($files[ 'files' ] as $item) {
			if ($item[ 'name' ] == $name) {
				return $item[ 'id' ];
			}
		}

		return false;
	}

}