<?php


namespace App\Utils;


class APIResponseHelper
{
	public static function getResponse(bool $success, $data, ?array $errors = null)
	{
		return [
			"success" => $success,
			"data" => $data,
			"errors" => $errors
		];
	}
}