<?php

return [
	/*
		|--------------------------------------------------------------------------
		| Validation Language Lines
		|--------------------------------------------------------------------------
		|
		| The following language lines contain the default error messages used by
		| the validator class. Some of these rules have multiple versions such
		| as the size rules. Feel free to tweak each of these messages.
		|
	*/

    'month' => [
        'required' => ':attribute کا میدان ضروری ہے۔',
        'digits' => ':attribute بالکل :digits ڈیجٹز ہونے چاہیے۔',
        'between' => ':attribute :min اور :max کے درمیان ہونا چاہئے۔',
        'custom_validation' => ':attribute موجودہ مہینہ یا اگلے مہینے ہونا چاہئے۔',
    ],
    'day' => [
        'required' => ':attribute کا میدان ضروری ہے۔',
        'digits' => ':attribute بالکل :digits ڈیجٹز ہونے چاہیے۔',
        'between' => ':attribute :min اور :max کے درمیان ہونا چاہئے۔',
        'custom_validation' => ':attribute موجودہ تاریخ یا آئندہ تاریخ ہونا چاہئے۔',
    ],
    'accepted' => ':attribute قبول کیا جانا چاہئے۔',
    'accepted_if' => ':attribute کو قبول کیا جانا چاہئے جب :other :value ہو۔',
    'active_url' => ':attribute درست URL نہیں ہے۔',
    'after' => ':attribute کی تاریخ :date کے بعد کی ہونی چاہئے۔',
    'after_or_equal' => ':attribute کی تاریخ :date کے بعد یا اس کے برابر ہونی چاہئے۔',
    'alpha' => ':attribute میں صرف حروف شامل ہو سکتے ہیں۔',
    'alpha_dash' => ':attribute میں صرف حروف، اعداد، ڈیشز اور انڈر اسکورز شامل ہو سکتے ہیں۔',
    'alpha_num' => ':attribute میں صرف حروف اور اعداد شامل ہو سکتے ہیں۔',
    'array' => ':attribute ایک میٹرکس ہونا چاہئے۔',
    'before' => ':attribute کی تاریخ :date سے پہلے کی ہونی چاہئے۔',
    'before_or_equal' => ':attribute کی تاریخ :date سے پہلے یا اس کے برابر ہونی چاہئے۔',
    'between' => [
        'numeric' => ':attribute :min اور :max کے درمیان ہونا چاہئے۔',
        'file' => ':attribute :min اور :max کلوبائٹ کے درمیان ہونا چاہئے۔',
        'string' => ':attribute :min اور :max حروف کے درمیان ہونا چاہئے۔',
        'array' => ':attribute میں :min اور :max اشیاء ہونی چاہئیں۔',
    ],
    'boolean' => ':attribute کا میدان صحیح یا غلط ہونا چاہئے۔',
    'confirmed' => ':attribute کی تصدیق مماثل نہیں ہوتی۔',
    'current_password' => 'موجودہ پاس ورڈ غلط ہے۔',
    'date' => ':attribute درست تاریخ نہیں ہے۔',
    'date_equals' => ':attribute کی تاریخ :date کے برابر ہونی چاہئے۔',
    'date_format' => ':attribute کی شکل :format سے مماثل نہیں ہوتی۔',
    'declined' => ':attribute کو منظور نہیں کیا جا سکتا۔',
    'declined_if' => ':attribute کو منظور نہیں کیا جا سکتا جب :other :value ہو۔',
    'different' => ':attribute اور :other مختلف ہونے چاہیے۔',
    'digits' => ':attribute بالکل :digits ڈیجٹز ہونے چاہئے۔',
    'digits_between' => ':attribute کی تعداد :min اور :max کے درمیان ہونی چاہئے۔',
    'dimensions' => ':attribute کی غلط تصویر کی طول و عرض ہیں۔',
    'distinct' => ':attribute کا میدان دوہرا قیمت رکھتا ہے۔',
    'email' => ':attribute ایک درست ای میل ایڈریس ہونی چاہئے۔',
    'ends_with' => ':attribute کا اختتام :values میں سے کسی ایک کے ساتھ ہونا چاہئے۔',
    'enum' => 'منتخب کردہ :attribute غلط ہے۔',
    'exists' => 'منتخب کردہ :attribute غلط ہے۔',
    'file' => ':attribute ایک فائل ہونی چاہئی۔',
    'filled' => ':attribute کا میدان ایک قیمت رکھنا ضروری ہوتا ہے۔',
    'gt' => [
        'numeric' => ':attribute :value سے بڑا ہونا چاہئے۔',
        'file' => ':attribute :value کلوبائٹ سے بڑا ہونا چاہئے۔',
        'string' => ':attribute :value حروف سے بڑا ہونا چاہئے۔',
        'array' => ':attribute میں :value اشیاء سے زیادہ ہونا چاہئیں۔',
    ],
    'gte' => [
        'numeric' => ':attribute :value کے برابر یا اس سے بڑا ہونا چاہئے۔',
        'file' => ':attribute :value کلوبائٹ کے برابر یا اس سے بڑا ہونا چاہئے۔',
        'string' => ':attribute :value حروف کے برابر یا اس سے بڑا ہونا چاہئے۔',
        'array' => ':attribute میں :value اشیاء یا ان سے زیادہ ہونا چاہئیں۔',
    ],
    'image' => ':attribute ایک تصویر ہونی چاہئی۔',
    'in' => 'منتخب کردہ :attribute غلط ہے۔',
    'in_array' => ':attribute کا میدان :other میں موجود نہیں ہے۔',
    'integer' => ':attribute ایک عدد ہونا چاہئے۔',
    'ip' => ':attribute ایک درست IP ایڈریس ہونی چاہئی۔',
    'ipv4' => ':attribute ایک درست IPv4 ایڈریس ہونی چاہئی۔',
    'ipv6' => ':attribute ایک درست IPv6 ایڈریس ہونی چاہئی۔',
    'json' => ':attribute ایک درست JSON سٹرنگ ہونی چاہئی۔',
    'lt' => [
        'numeric' => ':attribute :value سے کم ہونا چاہئے۔',
        'file' => ':attribute :value کلوبائٹ سے کم ہونا چاہئے۔',
        'string' => ':attribute :value حروف سے کم ہونا چاہئے۔',
        'array' => ':attribute میں :value اشیاء سے کم ہونی چاہئیں۔',
    ],
    'lte' => [
        'numeric' => ':attribute :value سے کم یا اس کے برابر ہونا چاہئے۔',
        'file' => ':attribute :value کلوبائٹ سے کم یا اس کے برابر ہونا چاہئے۔',
        'string' => ':attribute :value حروف سے کم یا اس کے برابر ہونا چاہئے۔',
        'array' => ':attribute میں :value اشیاء سے زیادہ نہیں ہونی چاہئیں۔',
    ],

	/*
		|--------------------------------------------------------------------------
		| Custom Validation Language Lines
		|--------------------------------------------------------------------------
		|
		| Here you may specify custom validation messages for attributes using the
		| convention "attribute.rule" to name the lines. This makes it quick to
		| specify a specific custom language line for a given attribute rule.
		|
	*/

	'custom' => [
		'attribute-name' => [
			'rule-name' => 'custom-message',
		],
	],

	/*
		|--------------------------------------------------------------------------
		| Custom Validation Attributes
		|--------------------------------------------------------------------------
		|
		| The following language lines are used to swap attribute place-holders
		| with something more reader friendly such as E-Mail Address instead
		| of "email". This simply helps us make messages a little cleaner.
		|
	*/

    'attributes' => [
        'name' => 'نام',
        'username' => 'صارف نام',
        'email' => 'ای میل',
        'first_name' => 'پہلا نام',
        'last_name' => 'خاندانی نام',
        'password' => 'پاس ورڈ',
        'password_confirmation' => 'پاس ورڈ کی تصدیق',
        'city' => 'شہر',
        'country' => 'ملک',
        'address' => 'پتہ',
        'phone' => 'فون',
        'mobile' => 'موبائل',
        'age' => 'عمر',
        'sex' => 'جنس',
        'gender' => 'جنسیت',
        'day' => 'دن',
        'month' => 'مہینہ',
        'year' => 'سال',
        'hour' => 'گھنٹہ',
        'minute' => 'منٹ',
        'second' => 'سیکنڈ',
        'title' => 'عنوان',
        'content' => 'مواد',
        'description' => 'تفصیل',
        'excerpt' => 'خلاصہ',
        'date' => 'تاریخ',
        'time' => 'وقت',
        'available' => 'دستیاب',
        'size' => 'سائز',
    ],
];
