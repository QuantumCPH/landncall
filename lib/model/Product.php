<?php

class Product extends BaseProduct
{
	function __toString()
	{
		return $this->getName();
	}
}
