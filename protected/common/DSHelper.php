<?php
class DSHelper
{
	public static function disableObjectOnValue($object, $value, $minValue, $maxValue) 
	{
		if ($minValue !== NULL && $maxValue !== NULL)
		{
			if (intval($value) <= intval($minValue) || intval($value) >= intval($maxValue)) 
			{
				$object->Enabled = false;
			} 
			else 
			{
				$object->Enabled = true;
			}
		} 
		else if ($minValue !== NULL) 
		{
			if (intval($value) <= intval($minValue)) 
			{
				$object->Enabled = false;
			} 
			else 
			{
				$object->Enabled = true;
			}
		} 
		else if ($maxValue !== NULL) 
		{
			if (intval($value) >= intval($maxValue)) 
			{
				$object->Enabled = false;
			} 
			else 
			{
				$object->Enabled = true;
			}
		}
	}
}
?>