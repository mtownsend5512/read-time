<?php

if (!function_exists('read_time')) {
    /**
     * Create a new instance of the Mtownsend\ReadTime\ReadTime class
     *
     * @return Mtownsend\ReadTime\ReadTime
     */
    function read_time($data)
    {
        // A string is passed, assign it to the content key
    	if (is_string($data)) {
    		$data = ['content' => $data];
    	}
        // An array is passed that is not associative, assume it is multiple pieces of content
        elseif (is_array($data) && !is_assoc_array($data)) {
    		$data = ['content' => $data];
    	}
        return app()->makeWith('read_time', $data);
    }
}

if (!function_exists('is_assoc_array')) {
    /**
     * Determine if an array is associative or sequential
     *
     * @return bool
     */
    function is_assoc_array($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
