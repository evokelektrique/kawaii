<?php

class MangaReader {
	
	function create_response($status, $redirect, $data) {
		$response_object = new stdClass();
		$response_object->status =$status;
		$response_object->redirect =$redirect;
		if(!empty($data)) {
			$response_object->data =$data;
		}
		$response_json = json_encode($response_object);
		return $response_json;
	}
	
	function unique_array($array,$keyname){

		$new_array = array();
		foreach($array as $key=>$value){

			if(!isset($new_array[$value[$keyname]])){
				$new_array[$value[$keyname]] = $value;
			}

		}
		$new_array = array_values($new_array);
		return $new_array;
	}

    public function view_image($image_url) {
        $img = file_get_contents(base_url('public/img/episodes_images/').$image_url);
        return base64_encode($img);
    }

}