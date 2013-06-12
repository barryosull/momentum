<?

class BaseController_Rest extends Controller_Rest
{
	protected function respond($data = array())
	{
		$result = (object)array();
		$result->success = true;
		$result->data = $data;
		$this->response($result);
	}

	protected function error_respond($error = array())
	{
		$result = (object)array();
		$result->error = $error;
		$this->response($result);
	}

	protected function convert_models_to_data_objects($objects)
	{
		$result = array();
		foreach($objects as $obj){
			$result[] = $obj->to_object();
		}
		return $result;
	}
}