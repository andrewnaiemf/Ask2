<?php
namespace App\Dash\Resources;
use Dash\Resource;

class OtherServiceBookings extends Resource {

	/**
	 * define Model of resource
	 * @param Model Class
	 */
	public static $model = \App\Models\Booking::class ;

    public function query($model)
    {
        return $model->whereNotIn('sub_department_id', ['36']);
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
	public static $group = 'Booking';

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
		return __('dash.booking.OtherServiceBookings.OtherServiceBookings');
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
            belongsTo()->make(__('dash.booking.OtherServiceBookings.user'), 'user', Users::class),
            belongsTo()->make(__('dash.booking.OtherServiceBookings.provider'), 'provider', Providers::class),
            belongsTo()->make(__('dash.booking.OtherServiceBookings.department'), 'department', MainDepartments::class),
            belongsTo()->make(__('dash.booking.OtherServiceBookings.subdepartment'), 'subdepartment', SubDepartments::class),
            hasOne()->make(__('dash.booking.OtherServiceBookings.bookingDetail'), 'bookingDetail', BookingDetails::class)
            ->hideInIndex(),
            text()->make(__('dash.booking.status'),'status'),
            // hasOne()->make(__('dash.providers.hotelBookingDetail'), 'hotelBookingDetail', HotelBookings::class)
            // ->hideInIndex(),
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
