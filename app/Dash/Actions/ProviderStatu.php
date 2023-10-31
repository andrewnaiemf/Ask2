<?php
namespace App\Dash\Actions;
use Dash\Extras\Inspector\Action;

class ProviderStatu extends Action {

	/**
	 * options to do some action with type message
	 * like danger,info,warning,success
	 * @return array
	 */
	public static function options() {
		//Example
		return [
            'status' => [
                __('dash.providers.Accepted') =>['Accepted' => ['success'=>'updated sccessfully']],
                __('dash.providers.Rejected')=>['New' => ['success'=>'updated sccessfully']],
			],
		];
	}

}
