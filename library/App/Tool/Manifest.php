<?php
class App_Tool_Manifest
implements Zend_Tool_Framework_Manifest_ProviderManifestable
{
	public function getProviders()
	{
		return array(
			new App_Tool_AppDbProvider()
		);
	}
}