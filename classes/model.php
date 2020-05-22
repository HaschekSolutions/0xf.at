<?php

/**
 * Model class for all models
 *
 * @author Christian
 */
class Model
{
	protected $_model;

	function __construct($id=0)
    {
		$this->_model = substr(get_class($this),0,-5);
        $this->_id = $id;
	}

	function __destruct()
	{
            
	}
}
