<?

class Model_TimeFormatException extends Exception {}

class Model_TimeFormat {

	public static function mins_to_string($mins)
	{
		if(!is_numeric ($mins)){
			throw new Model_TimeFormatException("Argument is not a number");
		}
		if($mins < 0){
			throw new Model_TimeFormatException("Negative numbers cannot be used");
		}

		$hours = (int)($mins/60);
		$mins_remainder = (int)($mins%60);

		if($hours == 0){
			return $mins_remainder.'mins';
		}
		if($mins_remainder == 0){
			return $hours.'hr';
		}
		return $hours.'hr '.$mins_remainder.'mins';
	}
}