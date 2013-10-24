<?php 

	class Helper {

		/**
	     * Return found value if in array key
	     *
	     * @param Value the index value in array to find
	     * @param Array the array to parse
	     * @return string
	     */
		public function getArrayValue($value = '', $ar = array())
		{	

			if (!is_array($ar)) return 'Please send an array as second argument';

			foreach ($ar as $_key => $_value) {

				if ($value == $_key) {
					return $_value;
				}		
			}

			return 'No value Found';
		}


	}
?>