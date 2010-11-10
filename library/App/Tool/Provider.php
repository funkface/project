<?php
class App_Tool_AppDbProvider implements Zend_Tool_Framework_Provider_Interface
{
	public function greet()
	{
		echo 'Hello from AppDbProvider!';
	}
}