<?php
namespace App\Dash\Resources;
use Dash\Resource;

class PharmacyProducts extends Resource {

	/**
	 * define Model of resource
	 * @param Model Class
	 */
	public static $model = \App\Models\Product::class ;

    public function query($model)
    {
        return $model->whereHas('provider', function ($query) {
            $query->where('subdepartment_id', 40);
        });
    }

	/**
	 * Policy Permission can handel
	 * (viewAny,view,create,update,delete,forceDelete,restore) methods
	 * @param static property as Policy Class
	 */
	//public static $policy = \App\Policies\UserPolicy::class ;

	/**
	 * define this resource in group to show in navigation menu
	 * if you need to translate a dynamic name
	 * define dash.php in /resources/views/lang/en/dash.php
	 * and add this key directly users
	 * @param static property
	 */
	public static $group = 'Health';

	/**
	 * show or hide resouce In Navigation Menu true|false
	 * @param static property string
	 */
	public static $displayInMenu = true;

	/**
	 * change icon in navigation menu
	 * you can use font awesome icons LIKE (<i class="fa fa-users"></i>)
	 * @param static property string
	 */
	public static $icon = ''; // put <i> tag or icon name

	/**
	 * title static property to labels in Rows,Show,Forms
	 * @param static property string
	 */
	public static $title = 'name';

	/**
	 * defining column name to enable or disable search in main resource page
	 * @param static property array
	 */
	public static $search = [
		'id',
		'name',
	];

	/**
	 *  if you want define relationship searches
	 *  one or Multiple Relations
	 * 	Example: method=> 'invoices'  => columns=>['title'],
	 * @param static array
	 */
	public static $searchWithRelation = [];

	/**
	 * if you need to custom resource name in menu navigation
	 * @return string
	 */
	public static function customName() {
		return __('dash.product.PharmacyProducts');
	}

	/**
	 * you can define vertext in header of page like (Card,HTML,view blade)
	 * @return array
	 */
	public static function vertex() {
		return [];
	}

	/**
	 * define fields by Helpers
	 * @return array
	 */
	public function fields() {
		return [
			id()->make(__('dash::dash.id'), 'id'),
            belongsTo()->make( __('dash.product.provider'), 'provider', Providers::class)->rule('required'),
            belongsTo()->make( __('dash.product.category'), 'category', Categories::class)->rule('required'),
            text() ->make(__('dash.product.name'), 'name')->translatable([
                'ar' => __('dash.ar'),
                'en' => 'English'
                ])->showInShow(),
            text() ->make(__('dash.product.info'), 'info')->translatable([
                'ar' => __('dash.ar'),
                'en' => 'English'
                ])->showInShow(),
            text() ->make(__('dash.product.description'), 'description'),
            number()->make(__('dash.product.numbers'), 'price'),
            number()->make(__('dash.product.stock'), 'stock'),
            // image()
            // ->make(__('dash.images'), 'path')
            //     ->path(function($model){
            //         $providerId = $model->provider->id;
            //         return ('Provider/'.$providerId.'/productImages/');
            //     })
            //     ->accept('image/*')

		];
	}

	/**
	 * define the actions To Using in Resource (index,show)
	 * php artisan dash:make-action ActionName
	 * @return array
	 */
	public function actions() {
		return [];
	}

	/**
	 * define the filters To Using in Resource (index)
	 * php artisan dash:make-filter FilterName
	 * @return array
	 */
	public function filters() {
		return [];
	}

}
